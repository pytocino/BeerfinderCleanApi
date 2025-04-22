<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostController extends Controller
{
    public function index()
    {
        return PostResource::collection(Post::latest()->paginate(15));
    }

    public function show($id)
    {
        return new PostResource(Post::findOrFail($id));
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
