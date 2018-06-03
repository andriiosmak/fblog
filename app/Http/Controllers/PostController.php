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
use Symfony\Component\HttpFoundation\Response;

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
        //Retrieves a post. Redirects to 404 in case of wrong $postId.
        $post = $repository->find($postId);

        //Checks whether user is going to edit his own post
        if (!$auth->user()->can('update', $post)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return view('/posts/edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     * Form vadilation was implemented in PostRequest.
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
        //Retrieves a post. Redirects to 404 in case of wrong $postId.
        $post = $repository->find($postId);

        //Checks whether user is going to update his own post
        if (!$auth->user()->can('update', $post)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $result = $post->update($request->only([
            'title',
            'description',
            'body'
        ]));

        if (!$result) {
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
     * @param  \App\Repositories\PostRepository  $repository
     * @param  \Illuminate\Auth\AuthManager      $auth
     * @param  string                            $postId
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(PostRepository $repository, AuthManager $auth, string $postId) : RedirectResponse
    {
        //Retrieves a post. Redirects to 404 in case of wrong $postId
        $post = $repository->find($postId);

        //Checks whether user is going to delete his own post
        if (!$auth->user()->can('delete', $post)) {
            abort(Response::HTTP_NOT_FOUND);
        }

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
