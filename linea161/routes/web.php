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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');



//GET de los VIEWS
Route::get('/branchesView', function () {
    return view('branch');
})->middleware('auth');
Route::get('/stopsView', function () {
    return view('stop');
})->middleware('auth');

//GET
Route::get('/stops','StopController@getAll');
Route::get('/branches','BranchController@getAll');

//DELETE
Route::delete('/branches/{id}', 'BranchController@delete');
Route::delete('/stops/{id}','StopController@delete');


//ADD
Route::post('/branches','BranchController@add');
Route::post('/stops','StopController@add');

//GET BY ID
Route::get('/branches/{id}','BranchController@getOne');
Route::get('/stops/{id}','StopController@getOne');


//GET STOPS BY ID
Route::get('/branchStops/{id}','BranchController@getStops');

//EDIT
Route::put('/branches/{id}', 'BranchController@update');
Route::put('/stops/{id}', 'StopController@update');