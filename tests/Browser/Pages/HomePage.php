<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class HomePage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url() : string
    {
        return route('home', [], false);
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
            ->assertSee(trans('labels.new'));
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
