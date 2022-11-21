<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkController;
use App\Models\Work;

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
//ホーム
Route::view('/', 'index')->name('index');

/**
 * 勤怠登録
 */
//勤怠一覧
Route::get('/works', [WorkController::class, 'index'])->name('works.index');
Route::prefix('work')->name('works')->group(function(){
    //勤怠提出
    Route::get('register', [WorkController::class, 'register'])->name('.register');
    Route::post('store', [WorkController::class, 'store'])->name('.store');
    //勤怠詳細
    Route::get('{work}', [WorkController::class, 'show'])->name('.show');
    //勤怠編集
    Route::get('{work}/edit', [WorkController::class, 'edit'])->name('.edit');
    //勤怠更新
    Route::put('{work}/edit', [WorkController::class, 'update'])->name('.update');
});