<?php

namespace App\Repositories;

use App\Models\Post;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class PostRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model() : string
    {
        return Post::class;
    }

    /**
     * Get all posts
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllPosts() : Collection
    {
        return $this->scopeQuery(function ($query) {
            return $query->orderBy('created_at', 'DESC');
        })->all();
    }
}