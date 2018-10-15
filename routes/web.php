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

Route::resource('/api/alumnos', 'SalesController');

Route::post('/api/CreateSales', 'SalesController@CreateSales');

Route::get('/api/GetQualification/{id_t_usuarios}', 'SalesController@GetQualification');

Route::put('/api/UpdateQualification/{usuario}/{materia}/{calificacion}', 'SalesController@UpdateQualification');

Route::delete('/api/DeleteQualification/{usuario}/{materia}', 'SalesController@DeleteQualification');