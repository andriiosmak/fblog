<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Repositories\PostRepository;
use App\Http\Requests\PostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
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
     *
     * @param  \App\Http\Requests\PostRequest  $request
     * @param  \Illuminate\Auth\AuthManager    $auth
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PostRequest $request, AuthManager $auth) : RedirectResponse
    {
        $data = $request->only([
            'title',
            'description',
            'body',
        ]);
        $data['created_at'] = time();

        $auth->user()->posts()->create($data);

        return redirect()->route('post.index')->with('success', trans('messages.post.create.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Repositories\PostRepository  $repository
     * @param  string                            $postId
     *
     * @return \Illuminate\View\View
     */
    public function show(PostRepository $repository, string $postId) : View
    {
        $post = $repository->find($postId);

        return view('/posts/show', ['post' => $post]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Http\Requests\PostRequest  $request
     * @param  \Illuminate\Auth\AuthManager    $auth
     * @param  string                          $postId
     *
     * @return \Illuminate\View\View
     */
    public function edit(PostRepository $repository, AuthManager $auth, string $postId) : View
    {
        $post = $repository->find($postId);

        if (!$auth->user()->can('update', $post)) {
            abort(404);
        }

        return view('/posts/edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\PostRequest    $request
     * @param  \App\Repositories\PostRepository  $repository
     * @param  \Illuminate\Auth\AuthManager      $auth
     * @param  string                            $postId
     *
     * @return \Illuminate\Http\Response
     */
    public function update(
        PostRequest $request,
        PostRepository $repository,
        AuthManager $auth,
        string $postId
    ) : RedirectResponse {
        $post = $repository->find($postId);

        if (!$auth->user()->can('update', $post)) {
            abort(404);
        }

        $result = $post->update($request->only([
            'title',
            'description',
            'body'
        ]));

        if (!$result) {
            return redirect()->route('post.index')->withErrors([trans('messages.post.update.failure')]);
        }

        return redirect()->route('post.index')->with('success', trans('messages.post.update.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Repositories\PostRepository  $repository
     * @param  \Illuminate\Auth\AuthManager      $auth
     * @param  string                            $postId
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(PostRepository $repository, AuthManager $auth, string $postId) : RedirectResponse
    {
        $post = $repository->find($postId);

        if (!$auth->user()->can('delete', $post)) {
            abort(404);
        }

        if (!$post->delete()) {
            return redirect()->route('post.index')->withErrors([trans('messages.post.delete.failure')]);
        }

        return redirect()->route('post.index')->with('success', trans('messages.post.delete.success'));
    }
}
