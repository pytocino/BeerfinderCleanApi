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
            'content' => 'nullable|string|max:2000',
            'photo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'additional_photos' => 'nullable|array|max:5',
            'additional_photos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'user_tags' => 'nullable|array',
            'user_tags.*' => 'exists:users,id'
        ]);

        $data = $validated;
        $data['user_id'] = $this->authenticatedUser()->id;

        // Procesar foto principal
        if ($request->hasFile('photo_url')) {
            $photoPath = $request->file('photo_url')->store('posts/main', 'public');
            $data['photo_url'] = Storage::url($photoPath);
        }

        // Procesar fotos adicionales
        if ($request->hasFile('additional_photos')) {
            $additionalPhotosPaths = [];
            foreach ($request->file('additional_photos') as $photo) {
                $path = $photo->store('posts/additional', 'public');
                $additionalPhotosPaths[] = Storage::url($path);
            }
            $data['additional_photos'] = $additionalPhotosPaths;
        } else if (isset($data['additional_photos'])) {
            // Si viene el campo pero vacío, guardar como array vacío
            $data['additional_photos'] = [];
        }

        // Si user_tags viene vacío, guardar como array vacío
        if (isset($data['user_tags']) && empty($data['user_tags'])) {
            $data['user_tags'] = [];
        }
        // Convertir user_tags de IDs a objetos con ID y username
        elseif (isset($data['user_tags']) && !empty($data['user_tags'])) {
            $taggedUsers = User::whereIn('id', $data['user_tags'])->get(['id', 'username']);
            $formattedTags = $taggedUsers->map(function($user) {
                return [
                    'id' => (int)$user->getAttribute('id'), // Acceder a través de getAttribute
                    'username' => (string)$user->getAttribute('username') // Acceder a través de getAttribute
                ];
            })->toArray();
            // Verificar que el JSON sea válido
            $data['user_tags'] = json_decode(json_encode($formattedTags)) ? $formattedTags : [];
            // Log para debugging
            Log::info('Tags formateados: ' . json_encode($data['user_tags']));
        }

        try {
            $post = Post::create($data);
            $post->load(['user.profile', 'user', 'likes', 'comments.user']);
            $post->loadCount(['likes', 'comments']);
            return response()->json([
                'success' => true,
                'message' => 'Post creado correctamente',
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error al crear post: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'No se pudo crear el post. Intenta de nuevo.',
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

        // Si se están actualizando las etiquetas de usuario, formatearlas correctamente
        if (isset($data['user_tags'])) {
            if (empty($data['user_tags'])) {
                $data['user_tags'] = [];
            } else {
                $taggedUsers = User::whereIn('id', $data['user_tags'])->get(['id', 'username']);
                $formattedTags = $taggedUsers->map(function($user) {
                    return [
                        'id' => (int)$user->getAttribute('id'), // Acceder a través de getAttribute
                        'username' => (string)$user->getAttribute('username') // Acceder a través de getAttribute
                    ];
                })->toArray();
                // Verificar que el JSON sea válido
                $data['user_tags'] = json_decode(json_encode($formattedTags)) ? $formattedTags : [];
                // Log para debugging
                Log::info('Tags actualizados: ' . json_encode($data['user_tags']));
            }
        }

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

    /**
     * Crear un post a partir de una review existente
     */
    public function createPostFromReview($id, Request $request)
    {
        $review = \App\Models\BeerReview::findOrFail($id);
        $authUser = $this->authenticatedUser();
        if ($review->user_id !== $authUser->id) {
            return response()->json(['message' => 'No tienes permiso para crear un post de esta review.'], 403);
        }
        if ($review->post_id) {
            return response()->json(['message' => 'Ya existe un post asociado a esta review.'], 409);
        }
        $validated = $request->validate([
            'content' => 'required|string|max:2000',
            'photo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'additional_photos' => 'nullable|array|max:5',
            'additional_photos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'user_tags' => 'nullable|array',
            'user_tags.*' => 'exists:users,id'
        ]);
        $data = $validated;
        $data['user_id'] = $authUser->id;
        $data['beer_id'] = $review->beer_id;
        $data['location_id'] = $review->location_id;
        
        // Procesar etiquetas de usuario
        if (isset($data['user_tags']) && !empty($data['user_tags'])) {
            $taggedUsers = User::whereIn('id', $data['user_tags'])->get(['id', 'username']);
            $formattedTags = $taggedUsers->map(function($user) {
                return [
                    'id' => (int)$user->getAttribute('id'), // Acceder a través de getAttribute
                    'username' => (string)$user->getAttribute('username') // Acceder a través de getAttribute
                ];
            })->toArray();
            // Verificar que el JSON sea válido
            $data['user_tags'] = json_decode(json_encode($formattedTags)) ? $formattedTags : [];
            // Log para debugging
            Log::info('Tags en review: ' . json_encode($data['user_tags']));
        } elseif (isset($data['user_tags'])) {
            $data['user_tags'] = [];
        }
        
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
        $post = $review->createAssociatedPost($data);
        if (!$post) {
            return response()->json(['message' => 'No se pudo crear el post.'], 500);
        }
        $post->load(['user.profile', 'user', 'beer', 'likes', 'comments.user', 'location', 'beerReview']);
        $post->loadCount(['likes', 'comments']);
        return new PostResource($post);
    }
}
