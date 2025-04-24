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
            'comments'
        ])->latest();

        // Cambia esto:
        // if ($request->has('id')) {
        //     $query->where('id', $request->input('id'));
        // }

        // Por esto:
        if ($request->has('userId')) {
            $query->where('user_id', $request->input('userId'));
        }

        $posts = $query->paginate(15);

        return PostResource::collection($posts);
    }

    public function show($id)
    {
        // Eager loading de relaciones para un solo post
        $post = Post::with(['user', 'beer', 'likes', 'comments'])->findOrFail($id);
        return new PostResource($post);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'beer_id'     => 'required|exists:beers,id',
            'location_id' => 'nullable|exists:locations,id',
            'review'      => 'nullable|string',
            'rating'      => 'nullable|integer|min:1|max:5',
        ]);
        $data['user_id'] = $request->user()->id;
        $post = Post::create($data);
        // Cargar relaciones para la respuesta
        $post->load(['user', 'beer', 'likes', 'comments']);
        return new PostResource($post);
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
