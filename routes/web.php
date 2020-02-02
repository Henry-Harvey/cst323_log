<?php

/*
 * |--------------------------------------------------------------------------
 * | Web Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register web routes for your application. These
 * | routes are loaded by the RouteServiceProvider within a group which
 * | contains the "web" middleware group. Now create something great!
 * |
 */

// Route for navigating to the login form with an empty url
Route::get('/', function () {
    return view('login');
});

// Route for naviagting to the login form with a specific url
Route::get('/login', function () {
    return view('login');
});

// Route for calling the login method from the controller
Route::post('/processLogin', 'AccountController@onLogin');

// Route for naviagting to the register form with a specific url
Route::get('/register', function () {
    return view('register');
});

// Route for calling the register method from the controller
Route::post('/processRegister', 'AccountController@onRegister');

// Route for navigating to the result page
Route::get('/home', function () {
    return view('home');
});

// Route for calling the logout method from the controller
Route::get('/processLogout', 'AccountController@onLogout');


// Route for navigating to the user profile page
Route::get('/profile', function () {
    return view('profile');
});

// Route for navigating to the admin page
Route::get('/admin', function () {
    return view('admin');
});