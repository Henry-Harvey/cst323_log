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

// Route for navigating to the home page
Route::get('/home', function () {
    return view('home');
});

// Route for naviagting to the register form
Route::get('/register', function () {
    return view('register');
});

// Route for calling the register controller method from the register form
Route::post('/processRegister', 'AccountController@onRegister');

// Route for navigating to the login form with an empty url
Route::get('/', function () {
    return view('login');
});

// Route for naviagting to the login form
Route::get('/login', function () {
    return view('login');
});

// Route for calling the login controller method from the login form
Route::post('/processLogin', 'AccountController@onLogin');

// Route for calling the logout controller method from the navbar
Route::get('/processLogout', 'AccountController@onLogout');

// Route for calling the profile controller method from the navbar
Route::get('/profile', 'AccountController@onGetProfile');

// Route for calling the profile controller method from the navbar
Route::get('/getEditProfile', 'AccountController@onGetEditProfile');

// Route for calling the edit profile method from the controller
Route::post('/processEditProfile', 'AccountController@onEditUser');

// Route for calling the admin controller method from the navbar
Route::get('/admin', 'AdminController@onGetAllUsers');

// Route for calling the suspend controller method from the admin page
Route::post('/processShowOtherUser', 'AdminController@onGetOtherProfile');

// Route for calling the tryDelete controller method from the admin page
Route::post('/processTryDeleteUser', 'AdminController@onTryDeleteUser');

// Route for calling the delete controller method from the admin page
Route::post('/processDeleteUser', 'AdminController@onDeleteUser');

// Route for calling the suspend controller method from the admin page
Route::post('/processTryToggleSuspension', 'AdminController@onTryToggleSuspension');

Route::post('/processToggleSuspension', 'AdminController@onToggleSuspension');

Route::get('/jobPostings', 'AdminController@onGetAllPosts');

Route::get('/createPost', function () {
    return view('newPost');
});

Route::post('/processCreatePost', 'AdminController@onCreatePost');

Route::post('/processTryDeletePost', 'AdminController@onTryDeletePost');

Route::post('/processDeletePost', 'AdminController@onDeletePost');

Route::post('/getEditPost', 'AdminController@onGetEditPost');

Route::post('/processEditPost', 'AdminController@onEditPost');



// Route for calling the history controller method from the navbar
Route::get('/createUserJob', function () {
    return view('newUserJob');
});

// Route for calling the edit job history method from the controller
Route::post('/processCreateUserJob', 'UserJobController@onCreateUserJob');

// Route for calling the skills controller method from the navbar
Route::get('/getEditSkills', 'SkillsController@onGetEditSkills');

// Route for calling the edit skills method from the controller
Route::post('/processEditSkills', 'HistoryController@onEditSkills');

// Route for calling the education controller method from the navbar
Route::get('/getEditEducation', 'EducationController@onGetEditEducation');

// Route for calling the edit education method from the controller
Route::post('/processEditEducation', 'EducationController@onEditEducation');
