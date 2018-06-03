<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PostsTest extends DuskTestCase
{
    /**
     * Unauthorized User Access Attempt.
     *
     * @return void
     */
    public function testUnauthorizedUser() : void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(route('home'))
                ->assertUrlIs(route('login'));
        });
    }

    /**
     * Listing Page.
     *
     * @return void
     */
    public function testListingPage() : void
    {
        $this->browse(function (Browser $browser) {
            $user = self::getUser();
            $browser->loginAs($user)
                ->visit(route('home'))
                ->assertSee(trans('labels.new'));
        });
    }

    /**
     * New Post Page Test.
     *
     * @return void
     */
    public function testCreatePostPage() : void
    {
        $this->browse(function (Browser $browser) {
            $user = self::getUser();
            $browser->loginAs($user)
                ->visit(route('post.create'))
                ->assertTitle(trans('labels.laravel'))
                ->assertSee(trans('labels.title'))
                ->assertSee(trans('labels.description'))
                ->assertSee(trans('labels.body'))
                ->assertSeeLink(trans('labels.back'));
        });
    }

    /**
     * New Post Page Form Validation.
     *
     * @return void
     */
    public function testCreatePostFormValidation() : void
    {
        //required fields
        $this->browse(function (Browser $browser) {
            $user               = self::getUser();
            $validationTemplate = trans('validation.required');
            $browser->loginAs($user)
                ->visit(route('post.create'))
                ->press(trans('labels.submit'))
                ->assertUrlIs(route('post.create'))
                ->assertSee(str_replace(':attribute', 'title', $validationTemplate))
                ->assertSee(str_replace(':attribute', 'description', $validationTemplate))
                ->assertSee(str_replace(':attribute', 'body', $validationTemplate));
        });
    }

    /**
     * New Post Page Form Submit.
     *
     * @return void
     */
    public function testCreatePostFormSubmit() : void
    {
        $this->browse(function (Browser $browser) {
            $user        = self::getUser();
            $title       = 'My First Post';
            $description = 'I would like to tell you about my favourite pet.';
            $body        = 'I would like to tell you about my favourite pet. His name is wolfie and he is a dog.';
            $browser->loginAs($user)
                ->visit(route('post.create'))
                ->type('title', $title)
                ->type('description', $description)
                ->type('body', $body)
                ->press(trans('labels.submit'))
                ->assertUrlIs(route('post.index'))
                ->assertSee($title)
                ->assertSee($description)
                ->assertSee($user->name)
                ->assertSee(trans('messages.post.create.success'));
        });
    }

    /**
     * Edit Post Page Test.
     *
     * @return void
     */
    public function testEditPostPage() : void
    {
        //check whether page is accessible from listings
        $this->browse(function (Browser $browser) {
            $user = self::getUser();
            $browser->loginAs($user)
                ->visit(route('post.index'))
                ->clickLink(trans('labels.edit'))
                ->assertTitle(trans('labels.laravel'))
                ->assertSee(trans('labels.title'))
                ->assertSee(trans('labels.description'))
                ->assertSee(trans('labels.body'))
                ->assertSeeLink(trans('labels.back'))
                ->assertInputValueIsNot('title', '')
                ->assertInputValueIsNot('description', '')
                ->assertInputValueIsNot('body', '');
        });

        $this->browse(function (Browser $browser) {
            $user = self::getUser();
            $post = self::getPost();
            $browser->loginAs($user)
                ->visit(route('post.edit', ['id' => $post->id]))
                ->assertTitle(trans('labels.laravel'))
                ->assertSee(trans('labels.title'))
                ->assertSee(trans('labels.description'))
                ->assertSee(trans('labels.body'))
                ->assertSeeLink(trans('labels.back'))
                ->assertInputValue('title', $post->title)
                ->assertInputValue('description', $post->description)
                ->assertInputValue('body', $post->body);
        });
    }

    /**
     * Edit Post Page Form Validation.
     *
     * @return void
     */
    public function testEditPostFormValidation() : void
    {
        //required fields
        $this->browse(function (Browser $browser) {
            $user               = self::getUser();
            $validationTemplate = trans('validation.required');
            $post               = self::getPost();
            $browser->loginAs($user)
                ->visit(route('post.edit', ['id' => $post->id]))
                ->type('title', '')
                ->type('description', '')
                ->type('body', '')
                ->press(trans('labels.submit'))
                ->assertUrlIs(route('post.edit', ['id' => $post->id]))
                ->assertSee(str_replace(':attribute', 'title', $validationTemplate))
                ->assertSee(str_replace(':attribute', 'description', $validationTemplate))
                ->assertSee(str_replace(':attribute', 'body', $validationTemplate));
        });
    }

    /**
     * Edit Post Page Form Submit.
     *
     * @return void
     */
    public function testEditPostFormSubmit() : void
    {
        $this->browse(function (Browser $browser) {
            $user        = self::getUser();
            $post        = self::getPost();
            $title       = self::getPostTitle();
            $description = 'I would like to tell you about my cat.';
            $body        = 'I would like to tell you about my favourite pet. His name is Mike and he is a cat.';
            $browser->loginAs($user)
                ->visit(route('post.edit', ['id' => $post->id]))
                ->type('title', $title)
                ->type('description', $description)
                ->type('body', $body)
                ->press(trans('labels.submit'))
                ->assertUrlIs(route('post.index'))
                ->assertSee($title)
                ->assertSee($description)
                ->assertSee($user->name)
                ->assertSee(trans('messages.post.update.success'));
        });
    }

    /**
     * Delete Post Test.
     *
     * @return void
     */
    public function testDeletePost() : void
    {
        $this->browse(function (Browser $browser) {
            $title = self::getPostTitle();
            $user  = self::getUser();
            $browser->loginAs($user)
                ->visit(route('post.index'))
                ->assertSee($title)
                ->press(trans('labels.delete'))
                ->assertUrlIs(route('post.index'))
                ->assertSee(trans('messages.post.delete.success'))
                ->assertDontSee($title);
        });
    }
}
