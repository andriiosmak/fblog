<?php

namespace Tests\Browser\Pages;

use App\Models\Post;
use Laravel\Dusk\Browser;

class EditPostPage extends Page
{
    protected $post;

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
        return route('post.edit', ['id' => $this->post->id], false);
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser) : void
    {
        $browser->assertPathIs($this->url())
                ->assertTitle(trans('labels.laravel'))
                ->assertSee(trans('labels.title'))
                ->assertSee(trans('labels.description'))
                ->assertSee(trans('labels.body'))
                ->assertSeeLink(trans('labels.back'))
                ->assertInputValue('title', $this->post->title)
                ->assertInputValue('description', $this->post->description)
                ->assertInputValue('body', $this->post->body);
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
