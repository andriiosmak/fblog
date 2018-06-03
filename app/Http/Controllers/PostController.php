<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\View\View;
use App\Repositories\PostRepository;
use App\Http\Requests\PostRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\AuthManager;

class PostController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Post::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Repositories\PostRepository  $repository
     *
     * @return \Illuminate\View\View
     */
    public function index(PostRepository $repository) : View
    {
        return view('/posts/index', [
            'posts' => $repository->getAllPosts()
        ]);
    }

    /**
     * Show the form for creating a new post.
     *
     * @return \Illuminate\View\View
     */
    public function create() : View
    {
        return view('/posts/create');
    }

    /**
     * Store a newly created post in storage.
     * Form vadilation was implemented in PostRequest.
     *
     * @param  \App\Http\Requests\PostRequest  $request
     * @param  \Illuminate\Auth\AuthManager    $auth
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PostRequest $request, AuthManager $auth) : RedirectResponse
    {
        $auth->user()
            ->posts()
            ->create($request->validated());

        return redirect()
            ->route('post.index')
            ->with('success', trans('messages.post.create.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     *
     * @return \Illuminate\View\View
     */
    public function show(Post $post) : View
    {
        return view('/posts/show', ['post' => $post]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     *
     * @return \Illuminate\View\View
     */
    public function edit(Post $post) : View
    {
        return view('/posts/edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     * Form vadilation was implemented in PostRequest.
     *
     * @param  \App\Http\Requests\PostRequest  $request
     * @param  \App\Models\Post                $post
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(
        PostRequest $request,
        Post $post
    ) : RedirectResponse {
        if (!$post->update($request->validated())) {
            return redirect()
                ->route('post.index')
                ->withErrors([trans('messages.post.update.failure')]);
        }

        return redirect()
            ->route('post.index')
            ->with('success', trans('messages.post.update.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Post $post) : RedirectResponse
    {
        if (!$post->delete()) {
            return redirect()
                ->route('post.index')
                ->withErrors([trans('messages.post.delete.failure')]);
        }

        return redirect()
            ->route('post.index')
            ->with('success', trans('messages.post.delete.success'));
    }
}
