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

/* ルーティング */
Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');

/* 予約情報の作成更新取得 */
Route::post('/api/reserve/insert', 'ReserveController@insert');
Route::post('/api/reserve/update', 'ReserveController@update');
Route::post('/api/reserve/delete', 'ReserveController@delete');
Route::get('/api/reserve/list', 'ReserveController@selectAll');

/* 設備情報の作成更新取得 */
Route::post('/api/facility/insert', 'FacilityController@insert');
Route::post('/api/facility/update', 'FacilityController@update');
Route::get('/api/facility/list', 'FacilityController@selectAll');

/* 所属課の作成更新取得 */
Route::post('/api/department/insert', 'DepartmentController@insert');
Route::post('/api/department/update', 'DepartmentController@update');
Route::get('/api/department/list', 'DepartmentController@selectAll');

// ユーザ
Route::get('/api/user/list', 'UserController@selectAll');

/* 認証 */
Auth::routes();

/* ログインユーザの取得 */
Route::get('/api/user', function() {
  return Auth::user();
});