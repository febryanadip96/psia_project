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
    return redirect('login');
});

Auth::routes();

Route::get('pembelian', 'User\PembelianController@index');
Route::get('pembelian/create', 'User\PembelianController@create');
Route::post('pembelian', 'User\PembelianController@store');
Route::get('pembelian/pelunasan/{id}', 'User\NotaPelunasanBeliController@pelunasan');
Route::post('pembelian/pelunasan', 'User\NotaPelunasanBeliController@simpan');
Route::get('pembelian/pelunasan/{id}/lihat', 'User\NotaPelunasanBeliController@lihat');
Route::get('penjualan', 'User\PenjualanController@index');
Route::get('penjualan/create', 'User\PenjualanController@create');
Route::post('penjualan', 'User\PenjualanController@store');
Route::get('penjualan/pelunasan/{id}', 'User\NotaPelunasanJualController@pelunasan');
Route::post('penjualan/pelunasan', 'User\NotaPelunasanJualController@simpan');
Route::get('penjualan/pelunasan/{id}/lihat', 'User\NotaPelunasanJualController@lihat');
Route::get('laporan', 'User\LaporanController@index');


