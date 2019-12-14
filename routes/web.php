<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
Route::get('/', function () {
    return view('welcome');
});
*/

// Authentication Routes
Route::get('logout', 'Auth\LoginController@logout', function () {
    return abort(404);
});
Auth::routes();

// Main Routes
Route::get('/', 'AntelopeController@dashboard');
Route::get('/xcsinfo', 'BaseXCS@xcsInfo');
Route::get('/member_admin', 'AntelopeController@memberAdmin');