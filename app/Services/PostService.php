<?php

namespace App\Services;

use App\Exceptions\CustomException;
use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use illuminate\Support\Str;

class PostService 
{
 
    public function createPost (array $postData) : Model | array {
        $postData['slug']  = Str::slug($postData['title'].Str::random(5));
        $postData['user_id'] = auth()->id();
        $postModel = Post::create($postData);
        return $postModel;
    }

    public function viewPost(string $slug) : Model {
        $post = Post::query()->where('slug', $slug)->first();
        return $post;
    }

    public function updatePost(array $postData) : void {
        $post = Post::query()->where('id', $postData['id'])->first();
        throw_if(blank($post), CustomException::class, 'Post not found');
        abort_if(auth()->id() != $post->user_id, 403, 'Unauthorized Access');
        if(!$post->update($postData)){
            throw new CustomException('Post update failed');
        }
    }

    public function deletePost(string $slug) : void {
        $post = Post::query()->where('slug', $slug)->first();
        abort_if(auth()->id() !== $post->user_id, 403, 'Unauthorized Access');
        throw_if(!$post->delete(), CustomException::class, 'Post deletion failed');
       
    }
}

