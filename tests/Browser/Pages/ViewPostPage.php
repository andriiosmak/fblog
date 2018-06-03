<?php

namespace Tests\Browser\Pages;

use App\Models\Post;
use Laravel\Dusk\Browser;

class ViewPostPage extends Page
{
    /**
     * Post
     *
     * @var \App\Models\Post
     */
    private $post;

    /**
     * Constructor.
     *
     * @param  \App\Models\Post  $post
     *
     * @return void
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url() : string
    {
        return route('post.show', ['id' => $this->post->id], false);
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  \Laravel\Dusk\Browser  $browser
     * @return void
     */
    public function assert(Browser $browser) : void
    {
        $browser->assertPathIs($this->url())
            ->assertTitle(trans('labels.laravel'))
            ->assertSee($this->post->title)
            ->assertSee($this->post->body)
            ->assertSeeLink(trans('labels.back'));
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements() : array
    {
        return [];
    }
}
