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

// Route for navigating to the calculator form with an empty url
Route::get('/', function () {
    return view('login');
});

// Route for naviagting to the calculator form with a specific url
Route::get('/login', function () {
    return view('login');
});

Route::post('/processLogin', 'AccountController@login');

Route::get('/register', function () {
    return view('register');
});

 // Route for calling the calculate method from the controller
Route::post('/processRegister', 'AccountController@register');

// Route for navigating to the result page
Route::get('/home', function () {
    return view('home');
});

Route::post('/processLogout', 'AccountController@logout');