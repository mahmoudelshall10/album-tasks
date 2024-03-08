<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('albums', App\Http\Controllers\AlbumsController::class);
Route::post('copy_album/{id}',[App\Http\Controllers\AlbumsController::class,'CopyAlbums'])->name('albums.copy_album');

Route::post('foot_albums/upload_single_image',[App\Http\Controllers\FootAlbumsController::class,'uploadSingleImage'])->name('foot_albums.uploadSingleImage');
Route::post('foot_albums/upload_multi_image',[App\Http\Controllers\FootAlbumsController::class,'UploadMultiFile'])->name('foot_albums.UploadMultiFile');

Route::get('foot_albums/show_file/{id}',[App\Http\Controllers\FootAlbumsController::class,'ShowFile'])->name('foot_albums.ShowFile');

Route::post('upload_image',[App\Http\Controllers\FootAlbumsController::class,'uploadImage'])->name('uploadImage');
Route::post('remove_single_image',[App\Http\Controllers\FootAlbumsController::class,'RemoveSingleFile'])->name('RemoveSingleFile');
