<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Post;

class PostPolicy
{
    /**
     * Determine whether the user can view the post.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Post  $post
     * @return bool
     */
    public function view(User $user, Post $post) : bool
    {
        return true;
    }

    /**
     * Determine whether the user can create posts.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user) : bool
    {
        return true;
    }

    /**
     * Determine if the given post can be updated by the user.
     *
     * @param  use App\Models\User  $user
     * @param  use App\Models\Post  $post
     *
     * @return bool
     */
    public function update(User $user, Post $post) : bool
    {
        return $user->id === $post->user_id;
    }

    /**
     * Determine if the given post can be deleted by the user.
     *
     * @param  use App\Models\User  $user
     * @param  use App\Models\Post  $post
     *
     * @return bool
     */
    public function delete(User $user, Post $post) : bool
    {
        return $user->id === $post->user_id;
    }
}