<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with([
            'user',
            'beer',
            'likes',
            'comments',
            'location'
        ])->latest();

        // Cambia esto:
        // if ($request->has('id')) {
        //     $query->where('id', $request->input('id'));
        // }

        // Por esto:
        if ($request->has('userId')) {
            $query->where('user_id', $request->input('userId'));
        }
        $posts = $query->orderBy('created_at', 'desc')->paginate(15);

        return PostResource::collection($posts);
    }

    public function show($id)
    {
        // Eager loading de relaciones para un solo post
        $post = Post::with(['user', 'beer', 'likes', 'comments', 'location'])->findOrFail($id);
        return new PostResource($post);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'beer_id' => 'required|exists:beers,id',
            'location_id' => 'nullable|exists:locations,id',
            'review' => 'nullable|string|max:1000',
            'rating' => 'nullable|integer|min:1|max:5',
            'photo' => 'nullable|image|max:5120', // 5MB máximo
            'additional_photos' => 'nullable|array',
            'additional_photos.*' => 'image|max:5120',
            'serving_type' => 'nullable|string|in:botella,lata,barril,grifo',
            'purchase_price' => 'nullable|numeric|min:0',
            'purchase_currency' => 'nullable|string|size:3',
            'user_tags' => 'nullable|array',
            'user_tags.*' => 'exists:users,id'
        ]);

        // Asignar el usuario actual
        $data = $validated;
        $data['user_id'] = $request->user()->id;

        // Manejar la foto principal si existe
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('posts', 'public');
            $data['photo_url'] = asset('storage/' . $photoPath);
        }

        // Manejar fotos adicionales si existen
        if ($request->hasFile('additional_photos')) {
            $additionalPhotos = [];
            foreach ($request->file('additional_photos') as $photo) {
                $path = $photo->store('posts/additional', 'public');
                $additionalPhotos[] = asset('storage/' . $path);
            }
            $data['additional_photos'] = $additionalPhotos;
        }

        try {
            // Crear el post
            $post = Post::create($data);

            // Cargar relaciones para la respuesta
            $post->load(['user', 'beer', 'likes', 'comments', 'location']);

            return new PostResource($post);
        } catch (\Exception $e) {
            \Log::error('Error al crear post: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al crear el post',
                'errors' => [$e->getMessage()]
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== $request->user()->id) {
            return response()->json(['message' => 'No tienes permiso para editar este post'], 403);
        }

        $data = $request->validate([
            'review' => 'nullable|string',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);
        $post->update($data);
        $post->load(['user', 'beer', 'likes', 'comments']);
        return new PostResource($post);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== request()->user()->id) {
            return response()->json(['message' => 'No tienes permiso para eliminar este post'], 403);
        }
        $post->delete();
        return response()->json(['message' => 'Post eliminado']);
    }
}
