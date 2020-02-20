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

/*
 * Get routes for views without data
 */

// Navigates to the home page with default URL
Route::get('/', function () {
    return view('home');
});

// Navigates to the home page
Route::get('/home', function () {
    return view('home');
});

// Navigates to the register form
Route::get('/register', function () {
    return view('register');
});

// Navigates to the login form
Route::get('/login', function () {
    return view('login');
});

// Navigates to the new post form from the AllJobPostings page
Route::get('/newPost', function () {
    return view('newPost');
});

// Navigates to the new user job form from the profile page
Route::get('/createUserJob', function () {
    return view('newUserJob');
});

/*
 * Account Controller Routes
 */

// Calls the account controller register method from the register view form
Route::post('/processRegister', 'AccountController@onRegister');

// Calls the account controller login method from the login view form
Route::post('/processLogin', 'AccountController@onLogin');

// Calls the account controller logout method from the navbar
Route::get('/processLogout', 'AccountController@onLogout');

// Calls the account controller get profile method from the navbar
Route::get('/getProfile', 'AccountController@onGetProfile');

// Calls the account controller get edit profile method from the profile page
Route::get('/getEditProfile', 'AccountController@onGetEditProfile');

// Calls the account controller edit profile method from the edit profile view form
Route::post('/processEditProfile', 'AccountController@onEditProfile');

/*
 * Admin Controller Routes
 */

// Calls the admin controller get all users method from the navbar
Route::get('/getAllUsers', 'AdminController@onGetAllUsers');

// Calls the admin controller get other profile method from the allUsers page
Route::post('/getOtherProfile', 'AdminController@onGetOtherProfile');

// Calls the admin controller try delete user method from the allUsers page
Route::post('/getTryDeleteUser', 'AdminController@onTryDeleteUser');

// Calls the admin controller delete user method from the tryDeleteUser view form
Route::post('/processDeleteUser', 'AdminController@onDeleteUser');

// Calls the admin controller try toggle suspension method from the allUsers page
Route::post('/getTryToggleSuspension', 'AdminController@onTryToggleSuspension');

// Calls the admin controller toggle suspension method from the allUsers view form
Route::post('/processToggleSuspension', 'AdminController@onToggleSuspension');

/*
 * Post Controller Routes
 */

// Calls the post controller get all posts method from the navbar
Route::get('/getJobPostings', 'PostController@onGetAllPosts');

// Calls the post controller create post method from the newPost view form
Route::post('/processCreatePost', 'PostController@onCreatePost');

// Calls the post controller try delete post method from the allJobPostings page
Route::post('/getTryDeletePost', 'PostController@onTryDeletePost');

// Calls the post controller delete post method from the tryDeletePost view form
Route::post('/processDeletePost', 'PostController@onDeletePost');

// Calls the post controller get edit post method from the allJobPostings page
Route::post('/getEditPost', 'PostController@onGetEditPost');

// Calls the post controller edit post method from the editPost view form
Route::post('/processEditPost', 'PostController@onEditPost');

/*
 * UserJob Controller Routes
 */

// Calls the user job controller create user job method from the newUserJob view form
Route::post('/processCreateUserJob', 'UserJobController@onCreateUserJob');




// Route for calling the skill view from the navbar
Route::get('/createUserSkill', function () {
    return view('newUserSkill');
});

// Route for calling the create skills method from the controller
Route::post('/processCreateUserSkill', 'UserSkillController@onCreateUserSkill');

// Route for calling the Education controller method from the navbar
Route::get('/createUserEducation', function () {
    return view('newUserEducation');
});

// Route for calling the create education method from the controller
Route::post('/processCreateUserEducation', 'UserEducationController@onCreateUserEducation');
       
