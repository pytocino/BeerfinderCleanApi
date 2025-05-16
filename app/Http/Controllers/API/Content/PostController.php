<?php

namespace App\Http\Controllers\API\Content;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Http\Resources\PostResource;
use App\Models\User; // Asegúrate de importar User si lo usas directamente
use App\Traits\HasUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage; // Para eliminar archivos

class PostController extends Controller
{
    use HasUser;

    public function getPosts(Request $request)
    {

        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 15); // Nuevo parámetro

        $authUser = $this->authenticatedUser();

        // Consulta base para posts de usuarios con perfil público
        $query = Post::with(['user', 'beer.style', 'location'])
            ->withCount(['likes', 'comments'])
            ->whereHas('user', function ($userQuery) {
                $userQuery->where('private_profile', false); // Solo usuarios con perfil público
            });

        // Si hay un usuario autenticado, incluir posts de usuarios seguidos con perfil privado
        if ($authUser) {
            $query->orWhereHas('user', function ($userQuery) use ($authUser) {
                $userQuery->where('private_profile', true) // Usuarios con perfil privado
                    ->whereHas('followers', function ($followerQuery) use ($authUser) {
                        $followerQuery->where('user_follows.follower_id', $authUser->id) // Especificar la tabla
                            ->where('user_follows.status', 'accepted'); // Especificar la tabla
                    });
            });
        }

        // Ordenar por fecha de creación y paginar
        $posts = $query->latest()->paginate($perPage, ['*'], 'page', $page);

        return PostResource::collection($posts);
    }

    public function show($id)
    {
        $post = Post::with([
            'user',
            'beer',
            'beer.style',
            'location',
            'beerReview',
            'beerReview.beer',
            'beerReview.location',
        ])->withCount(['likes', 'comments'])->findOrFail($id);

        return response()->json([
            'post' => new PostResource($post),
        ]);
    }

    public function getPostById($id)
    {
        return $this->show($id);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:2000',
            'beer_id' => 'nullable|exists:beers,id',
            'location_id' => 'nullable|exists:locations,id',
            'photo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB
            'additional_photos' => 'nullable|array|max:5', // Máximo 5 fotos adicionales
            'additional_photos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'user_tags' => 'nullable|array',
            'user_tags.*' => 'exists:users,id'
            // Campos de BeerReview que podrían venir si el post está asociado a una review
            // 'rating' => 'nullable|integer|min:1|max:5',
            // 'serving_type' => 'nullable|string|in:bottle,can,draft,growler,taster,crowler',
            // 'purchase_price' => 'nullable|numeric|min:0',
            // 'purchase_currency' => 'nullable|string|size:3',
        ]);

        $data = $validated;
        $data['user_id'] = $this->authenticatedUser()->id; // Usar el trait

        if ($request->hasFile('photo_url')) {
            $photoPath = $request->file('photo_url')->store('posts/main', 'public');
            $data['photo_url'] = Storage::url($photoPath);
        }

        if ($request->hasFile('additional_photos')) {
            $additionalPhotosPaths = [];
            foreach ($request->file('additional_photos') as $photo) {
                $path = $photo->store('posts/additional', 'public');
                $additionalPhotosPaths[] = Storage::url($path);
            }
            $data['additional_photos'] = $additionalPhotosPaths;
        }

        try {
            $post = Post::create($data);
            $post->load(['user.profile', 'user', 'beer', 'likes', 'comments.user', 'location']);
            $post->loadCount(['likes', 'comments']);
            return new PostResource($post);
        } catch (\Exception $e) {
            Log::error('Error al crear post: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'message' => 'Error al crear el post.',
                'errors' => [$e->getMessage()]
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $authUser = $this->authenticatedUser();

        if ($post->user_id !== $authUser->id) {
            return response()->json(['message' => 'No tienes permiso para editar este post.'], 403);
        }

        $validated = $request->validate([
            'content' => 'sometimes|required|string|max:2000',
            'beer_id' => 'nullable|exists:beers,id',
            'location_id' => 'nullable|exists:locations,id',
            // No permitir cambiar fotos directamente aquí, usar endpoints dedicados si es necesario
            'user_tags' => 'nullable|array',
            'user_tags.*' => 'exists:users,id'
        ]);

        $data = $validated;
        $data['edited'] = true; // Marcar como editado

        try {
            $post->update($data);
            $post->load(['user.profile', 'user', 'beer', 'likes', 'comments.user', 'location']);
            $post->loadCount(['likes', 'comments']);
            return new PostResource($post);
        } catch (\Exception $e) {
            Log::error('Error al actualizar post: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'message' => 'Error al actualizar el post.',
                'errors' => [$e->getMessage()]
            ], 500);
        }
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $authUser = $this->authenticatedUser();

        if ($post->user_id !== $authUser->id) {
            // O si es un admin, permitirlo
            // if (!$authUser->isAdmin()) {
            return response()->json(['message' => 'No tienes permiso para eliminar este post.'], 403);
            // }
        }

        try {
            // Opcional: Eliminar fotos del storage si existen
            if ($post->photo_url) {
                Storage::disk('public')->delete(str_replace(Storage::url(''), '', $post->photo_url));
            }
            if ($post->additional_photos && is_array($post->additional_photos)) {
                foreach ($post->additional_photos as $photoPath) {
                    Storage::disk('public')->delete(str_replace(Storage::url(''), '', $photoPath));
                }
            }

            $post->delete(); // Soft delete si está configurado en el modelo
            return response()->json(['message' => 'Post eliminado correctamente.']);
        } catch (\Exception $e) {
            Log::error('Error al eliminar post: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'message' => 'Error al eliminar el post.',
                'errors' => [$e->getMessage()]
            ], 500);
        }
    }
}
