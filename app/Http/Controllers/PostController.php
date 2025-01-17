<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct(
        protected PostService $postService
    )
    {
        $this->middleware(['throttle:10,1']);
    }
    public function createPost(PostRequest $postRequest) : JsonResponse {
        $data = $this->postService->createPost($postRequest->validated());
        return $this->success(data:$data);
    }

    public function viewPost($slug) : JsonResponse {
        $data = $this->postService->viewPost($slug);
        return $this->success(data:$data);
    }

    public function updatePost(PostRequest $postRequest) : JsonResponse {
        $this->postService->updatePost($postRequest->validated());
        return $this->success(message:'Post updated successfully');
    }

    public function deletePost(string $slug) : JsonResponse {
        $this->postService->deletePost($slug);
        return $this->success(message: 'Post deleted successfully');
    }
}
