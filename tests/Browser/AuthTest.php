<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\LoginPage;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AuthTest extends DuskTestCase
{
    /**
     * Login Page Test.
     *
     * @return void
     */
    public function testLoginPage() : void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LoginPage);
        });
    }

    /**
     * Test Login Attempt.
     *
     * @return void
     */
    public function testLoginAttempt() : void
    {
        //not existing user
        $this->browse(function (Browser $browser) {
            $browser->visit(route('login'))
                ->type('email', 'notexisting@user.com')
                ->type('password', 'password')
                ->press(trans('labels.login'))
                ->assertUrlIs(route('login'))
                ->assertSee(trans('auth.failed'));
        });

        //valid user
        $this->browse(function (Browser $browser) {
            $browser->visit(route('login'))
                ->type('email', 'usera@fblog.com')
                ->type('password', 'secret')
                ->press(trans('labels.login'))
                ->assertPathIs('/');
        });
    }

    /**
     * Test Logout Attempt.
     *
     * @return void
     */
    public function testLogoutAttempt() : void
    {
        $this->browse(function ($browser) {
            $user = self::getUser();
            $browser->loginAs($user)
                ->visit(route('home'))
                ->clickLink($user->name)
                ->waitForText(trans('labels.logout'))
                ->clickLink(trans('labels.logout'))
                ->assertUrlIs(route('login'));
        });
    }
}
