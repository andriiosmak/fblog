<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class CreatePostPage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url() : string
    {
        return route('post.create', [], false);
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
