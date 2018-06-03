<?php

namespace Tests;

use App\Models\User;
use App\Models\Post;
use Laravel\Dusk\TestCase as BaseTestCase;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public static function prepare()
    {
        static::startChromeDriver();
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        $options = (new ChromeOptions)->addArguments([
            '--disable-gpu',
            '--headless'
        ]);

        return RemoteWebDriver::create(
            'http://localhost:9515', DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )
        );
    }

    /**
     * Get user.
     *
     * @return \App\Models\User
     */
    public static function getUser() : User
    {
        return User::first();
    }

    /**
     * Get post.
     *
     * @return \App\Models\Post
     */
    public static function getPost() : Post
    {
        return Post::first();
    }

    /**
     * Get post title.
     *
     * @return string
     */
    public static function getPostTitle() : string
    {
        return 'My First Post Updated';
    }
}
