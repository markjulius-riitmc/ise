<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::resource('/cruds', 'CrudsController', [
  'except' => ['edit', 'show', 'store']
]);

// CATEGORIES
// Get list of categories
Route::get('categories', 'CategoryController@index');
// Create new category
Route::post('category', 'CategoryController@store');
// Update category
Route::put('category', 'CategoryController@store');
// Delete category
Route::delete('category/{id}', 'CategoryController@destroy');

// EQUIPMENT
// Get list of quipment
Route::get('equipments', 'EquipmentController@index');
// Create new equipment
Route::post('equipment', 'EquipmentController@store');
// Update equipment
Route::put('equipment', 'EquipmentController@store');
// Delete equipment
Route::delete('equipment/{id}', 'EquipmentController@destroy');

// SITES
Route::get('/sites', 'SiteController@index');
Route::post('/site', 'SiteController@store');
Route::put('/site', 'SiteController@store');
Route::delete('/site/{id}', 'SiteController@destroy');

// DIVISIONS
Route::get('/divisions', 'DivisionController@index');
Route::post('/division', 'DivisionController@store');
Route::put('/division', 'DivisionController@store');
Route::delete('/division/{id}', 'DivisionController@destroy');

// DEPARTMENTS
Route::get('/departments', 'DepartmentController@index');
Route::post('/department', 'DepartmentController@store');
Route::put('/department', 'DepartmentController@store');
Route::delete('/department/{id}', 'DepartmentController@destroy');

// EMPLOYEES
Route::get('/employees', 'EmployeeController@index');
Route::post('/employee', 'EmployeeController@store');
Route::put('/employee', 'EmployeeController@store');
Route::delete('/employee/{id}', 'EmployeeController@destroy');

// SUPPLIERS
Route::get('/suppliers', 'SupplierController@index');
Route::post('/supplier', 'SupplierController@store');
Route::put('/supplier', 'SupplierController@store');
Route::delete('/supplier/{id}', 'SupplierController@destroy');

// MANAGE EQUIPMENT
// Route::get('/manage-equipment', 'ManageEquipmentController@index');
// Route::post('/manage-equipment', 'ManageEquipmentController@store');
// Route::put('/manage-equipment', 'ManageEquipmentController@store');
// Route::delete('/manage-equipment/{id}', 'ManageEquipmentController@destroy');
Route::apiResource('manage-equipment', 'ManageEquipmentController');
Route::apiResource('user', 'UserController');

Route::group([

  'middleware' => 'api',
  'prefix' => 'auth'

], function ($router) {

  Route::post('login', 'AuthController@login');
  Route::post('signup', 'AuthController@signup');
  Route::post('logout', 'AuthController@logout');
  Route::post('refresh', 'AuthController@refresh');
  Route::post('me', 'AuthController@me');

});



Route::get('/get-inspiration', 'HomeController@getInspiration');
