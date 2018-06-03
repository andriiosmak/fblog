<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Repositories\PostRepository;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

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
     * @param  \App\Http\Requests\CreatePostRequest  $request
     * @param  \App\Repositories\PostRepository      $repository
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreatePostRequest $request, PostRepository $repository) : RedirectResponse
    {
        $data = $request->only([
            'title',
            'description',
            'body',
        ]);
        $data['created_at'] = time();

        auth()->user()->posts()->create($data);

        return redirect()->route('post.index')->with('success', trans('messages.post.create.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Repositories\PostRepository  $repository
     * @param  string                            $id
     *
     * @return \Illuminate\View\View
     */
    public function show(PostRepository $repository, string $id) : View
    {
        $post = $repository->find($id);

        return view('/posts/show', ['post' => $post]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Http\Requests\CreatePostRequest  $request
     * @param  string                                $id
     *
     * @return \Illuminate\View\View
     */
    public function edit(PostRepository $repository, string $id) : View
    {
        $post = $repository->find($id);

        $this->checkAccess($post);

        return view('/posts/edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CreatePostRequest  $request
     * @param  \App\Repositories\PostRepository      $repository
     * @param  string                                $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(CreatePostRequest $request, PostRepository $repository, string $id) : RedirectResponse
    {
        $post = $repository->find($id);

        $this->checkAccess($post);

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
     * @param  string                            $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(PostRepository $repository, string $id) : RedirectResponse
    {
        $post = $repository->find($id);

        $this->checkAccess($post);

        $result = $post->delete();

        if (!$result) {
            return redirect()->route('post.index')->withErrors([trans('messages.post.delete.failure')]);
        }

        return redirect()->route('post.index')->with('success', trans('messages.post.delete.success'));
    }

    /**
     * Check whether user har a right to change a post.
     *
     * @param  App\Models\Post  $post
     *
     * @return void
     */
    private function checkAccess(Post $post) : void
    {
        if (Gate::denies('change-post', $post)) {
            abort(404);
        }
    }
}
