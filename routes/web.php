<?php

//auth
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

//posts
Route::resource('/post', 'PostController');
Route::get('/', 'PostController@index')->name('home');
