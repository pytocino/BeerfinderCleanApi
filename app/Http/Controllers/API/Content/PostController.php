<?php

namespace App\Http\Controllers\API\Content;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\{Post, User, BeerReview, Beer, Location};
use App\Services\CloudinaryService;
use App\Traits\HasUser;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\{Log, Storage, DB};
use Illuminate\Database\Eloquent\Builder;

class PostController extends Controller
{
    use HasUser;

    protected $cloudinaryService;

    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }

    /**
     * Obtiene los posts con paginación optimizada.
     */
    public function getPosts(Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'page' => 'integer|min:1',
            'per_page' => 'integer|min:1|max:50',
        ]);

        $perPage = $request->input('per_page', 15);
        $authUser = $this->authenticatedUser();

        $posts = $this->buildPostsQuery($authUser)
            ->latest()
            ->paginate($perPage);

        return PostResource::collection($posts);
    }

    /**
     * Muestra un post específico.
     */
    public function show(int $id): JsonResponse
    {
        $post = $this->getPostWithRelations($id);

        return response()->json([
            'post' => new PostResource($post)
        ]);
    }

    /**
     * Alias para el método show.
     */
    public function getPostById(int $id): JsonResponse
    {
        return $this->show($id);
    }

    /**
     * Crea un nuevo post.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $this->validatePostData($request);

        try {
            DB::beginTransaction();

            $data = $this->preparePostData($validated, $request);
            $post = Post::create($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Post creado correctamente',
                'post' => new PostResource($this->getPostWithRelations($post->id))
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear post: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'success' => false,
                'message' => 'No se pudo crear el post. Intenta de nuevo.',
                'errors' => [$e->getMessage()]
            ], 500);
        }
    }

    /**
     * Actualiza un post existente.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $post = Post::findOrFail($id);
        $authUser = $this->authenticatedUser();

        if ($post->user_id !== $authUser->id) {
            return response()->json(['message' => 'No tienes permiso para editar este post.'], 403);
        }

        $validated = $this->validateUpdateData($request);

        try {
            DB::beginTransaction();

            $data = $this->prepareUpdateData($validated, $request, $post);
            $post->update($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Post actualizado correctamente',
                'post' => new PostResource($this->getPostWithRelations($post->id))
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar post: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el post.',
                'errors' => [$e->getMessage()]
            ], 500);
        }
    }

    /**
     * Elimina un post.
     */
    public function destroy(int $id): JsonResponse
    {
        $post = Post::findOrFail($id);
        $authUser = $this->authenticatedUser();

        if ($post->user_id !== $authUser->id) {
            return response()->json(['message' => 'No tienes permiso para eliminar este post.'], 403);
        }

        try {
            DB::beginTransaction();

            $this->deletePostFiles($post);
            $post->delete();

            DB::commit();

            return response()->json(['message' => 'Post eliminado correctamente.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar post: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'message' => 'Error al eliminar el post.',
                'errors' => [$e->getMessage()]
            ], 500);
        }
    }

    /**
     * Crea un post a partir de una review existente.
     */
    public function createPostFromReview(int $id, Request $request): JsonResponse
    {
        $review = BeerReview::findOrFail($id);
        $authUser = $this->authenticatedUser();

        if ($review->user_id !== $authUser->id) {
            return response()->json(['message' => 'No tienes permiso para crear un post de esta review.'], 403);
        }

        if ($review->post_id) {
            return response()->json(['message' => 'Ya existe un post asociado a esta review.'], 409);
        }

        $validated = $this->validatePostData($request, true);

        try {
            DB::beginTransaction();

            $data = $this->preparePostFromReviewData($validated, $request, $review);
            $post = $review->createAssociatedPost($data);

            if (!$post) {
                throw new \Exception('No se pudo crear el post asociado.');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Post creado desde review correctamente',
                'post' => new PostResource($this->getPostWithRelations($post->id))
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear post desde review: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'success' => false,
                'message' => 'No se pudo crear el post.',
                'errors' => [$e->getMessage()]
            ], 500);
        }
    }

    /**
     * Construye la consulta base para posts.
     */
    private function buildPostsQuery(?User $authUser): Builder
    {
        $query = Post::with([
            'user:id,name,username,profile_picture,private_profile',
            'beer:id,name,brewery_id,style_id',
            'beer.brewery:id,name',
            'beer.style:id,name',
            'location:id,name,address,city,latitude,longitude',
            'beerReview:id,post_id,beer_id,location_id,rating,review_text',
            'beerReview.beer:id,name,brewery_id,style_id',
            'beerReview.beer.brewery:id,name',
            'beerReview.beer.style:id,name',
            'beerReview.location:id,name,address,city,latitude,longitude'
        ])
            ->withCount(['likes', 'comments'])
            ->whereHas('user', function ($userQuery) {
                $userQuery->where('private_profile', false);
            });

        if ($authUser) {
            $query->orWhereHas('user', function ($userQuery) use ($authUser) {
                $userQuery->where('private_profile', true)
                    ->whereHas('followers', function ($followerQuery) use ($authUser) {
                        $followerQuery->where('user_follows.follower_id', $authUser->id)
                            ->where('user_follows.status', 'accepted');
                    });
            });
        }

        return $query;
    }

    /**
     * Obtiene un post con todas sus relaciones cargadas.
     */
    private function getPostWithRelations(int $id): Post
    {
        return Post::with([
            'user:id,name,username,profile_picture,private_profile',
            'beer:id,name,brewery_id,style_id',
            'beer.style:id,name',
            'beer.brewery:id,name',
            'location:id,name,address,city,latitude,longitude',
            'beerReview:id,post_id,beer_id,location_id,rating,review_text',
            'beerReview.beer:id,name,brewery_id,style_id',
            'beerReview.beer.brewery:id,name',
            'beerReview.beer.style:id,name',
            'beerReview.location:id,name,address,city,latitude,longitude',
        ])
            ->withCount(['likes', 'comments'])
            ->findOrFail($id);
    }

    /**
     * Valida los datos del post.
     */
    private function validatePostData(Request $request, bool $requireContent = false): array
    {
        $rules = [
            'content' => $requireContent ? 'required|string|max:2000' : 'nullable|string|max:2000',
            'photo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'additional_photos' => 'nullable|array|max:5',
            'additional_photos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'tags' => 'nullable|array',
            'tags.*.type' => 'required|in:user,beer,location',
            'tags.*.id' => 'required|integer',
            'beer_id' => 'nullable|exists:beers,id',
            'location_id' => 'nullable|exists:locations,id',
        ];

        return $request->validate($rules);
    }

    /**
     * Valida los datos para actualización.
     */
    private function validateUpdateData(Request $request): array
    {
        return $request->validate([
            'content' => 'sometimes|required|string|max:2000',
            'photo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'additional_photos' => 'nullable|array|max:5',
            'additional_photos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'beer_id' => 'nullable|exists:beers,id',
            'location_id' => 'nullable|exists:locations,id',
            'tags' => 'nullable|array',
            'tags.*.type' => 'required|in:user,beer,location',
            'tags.*.id' => 'required|integer',
        ]);
    }

    /**
     * Prepara los datos del post para creación.
     */
    private function preparePostData(array $validated, Request $request): array
    {
        $data = $validated;
        $data['user_id'] = $this->authenticatedUser()->id;

        // Procesar archivos
        $data = $this->processFiles($data, $request);

        // Procesar etiquetas
        $data['tags'] = $this->processTags($data['tags'] ?? []);

        return $data;
    }

    /**
     * Prepara los datos para actualización.
     */
    private function prepareUpdateData(array $validated, Request $request, Post $post): array
    {
        $data = $validated;
        $data['edited'] = true;

        // Procesar archivos si se enviaron
        $data = $this->processFilesForUpdate($data, $request, $post);

        if (isset($data['tags'])) {
            $data['tags'] = $this->processTags($data['tags']);
        }

        return $data;
    }

    /**
     * Prepara los datos del post desde una review.
     */
    private function preparePostFromReviewData(array $validated, Request $request, BeerReview $review): array
    {
        $data = $this->preparePostData($validated, $request);
        $data['beer_id'] = $review->beer_id;
        $data['location_id'] = $review->location_id;

        return $data;
    }

    /**
     * Procesa los archivos del post usando Cloudinary.
     */
    private function processFiles(array $data, Request $request): array
    {
        if ($request->hasFile('photo_url')) {
            $result = $this->cloudinaryService->uploadImage(
                $request->file('photo_url'),
                'posts/main'
            );

            if ($result['success']) {
                $data['photo_url'] = $result['url'];
                $data['photo_public_id'] = $result['public_id'];
            } else {
                throw new \Exception('Error al subir imagen principal: ' . $result['error']);
            }
        }

        if ($request->hasFile('additional_photos')) {
            $additionalPhotosUrls = [];
            $additionalPhotosPublicIds = [];

            foreach ($request->file('additional_photos') as $photo) {
                $result = $this->cloudinaryService->uploadImage(
                    $photo,
                    'posts/additional'
                );

                if ($result['success']) {
                    $additionalPhotosUrls[] = $result['url'];
                    $additionalPhotosPublicIds[] = $result['public_id'];
                } else {
                    throw new \Exception('Error al subir imagen adicional: ' . $result['error']);
                }
            }

            $data['additional_photos'] = $additionalPhotosUrls;
            $data['additional_photos_public_ids'] = $additionalPhotosPublicIds;
        } elseif (isset($data['additional_photos'])) {
            $data['additional_photos'] = [];
            $data['additional_photos_public_ids'] = [];
        }

        return $data;
    }

    /**
     * Procesa los archivos del post para actualización usando Cloudinary.
     */
    private function processFilesForUpdate(array $data, Request $request, Post $post): array
    {
        if ($request->hasFile('photo_url')) {
            // Eliminar imagen anterior si existe
            if ($post->photo_public_id) {
                $this->cloudinaryService->deleteImage($post->photo_public_id);
            }

            $result = $this->cloudinaryService->uploadImage(
                $request->file('photo_url'),
                'posts/main'
            );

            if ($result['success']) {
                $data['photo_url'] = $result['url'];
                $data['photo_public_id'] = $result['public_id'];
            } else {
                throw new \Exception('Error al subir imagen principal: ' . $result['error']);
            }
        }

        if ($request->hasFile('additional_photos')) {
            // Eliminar fotos adicionales anteriores si existen
            if ($post->additional_photos_public_ids && is_array($post->additional_photos_public_ids)) {
                foreach ($post->additional_photos_public_ids as $publicId) {
                    $this->cloudinaryService->deleteImage($publicId);
                }
            }

            $additionalPhotosUrls = [];
            $additionalPhotosPublicIds = [];

            foreach ($request->file('additional_photos') as $photo) {
                $result = $this->cloudinaryService->uploadImage(
                    $photo,
                    'posts/additional'
                );

                if ($result['success']) {
                    $additionalPhotosUrls[] = $result['url'];
                    $additionalPhotosPublicIds[] = $result['public_id'];
                } else {
                    throw new \Exception('Error al subir imagen adicional: ' . $result['error']);
                }
            }

            $data['additional_photos'] = $additionalPhotosUrls;
            $data['additional_photos_public_ids'] = $additionalPhotosPublicIds;
        }

        return $data;
    }

    /**
     * Elimina los archivos asociados a un post de Cloudinary.
     */
    private function deletePostFiles(Post $post): void
    {
        if ($post->photo_public_id) {
            $this->cloudinaryService->deleteImage($post->photo_public_id);
        }

        if ($post->additional_photos_public_ids && is_array($post->additional_photos_public_ids)) {
            foreach ($post->additional_photos_public_ids as $publicId) {
                $this->cloudinaryService->deleteImage($publicId);
            }
        }
    }

    /**
     * Procesa las etiquetas de usuarios y cervezas.
     */
    private function processTags(array $tags): array
    {
        if (empty($tags)) {
            return [];
        }

        $processedTags = [];

        foreach ($tags as $tag) {
            if (!isset($tag['type'], $tag['id'])) {
                continue;
            }

            // Validar que exista el recurso según el tipo
            if ($tag['type'] === 'user') {
                if (User::find($tag['id'])) {
                    $processedTags[] = [
                        'type' => 'user',
                        'id' => (int) $tag['id']
                    ];
                }
            } elseif ($tag['type'] === 'beer') {
                if (Beer::find($tag['id'])) {
                    $processedTags[] = [
                        'type' => 'beer',
                        'id' => (int) $tag['id']
                    ];
                }
            } elseif ($tag['type'] === 'location') {
                if (Location::find($tag['id'])) {
                    $processedTags[] = [
                        'type' => 'location',
                        'id' => (int) $tag['id']
                    ];
                }
            }
        }

        return $processedTags;
    }
}
