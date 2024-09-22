<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('login');
Route::post('/checkUser', [LoginController::class, 'checkUser'])->name('checkUser');
Route::group(['middleware' => ['auth.check']], function () {
    Route::get('/dashboard', [LoginController::class, 'dashboard'])->name('dashboard');
    Route::get('/addEmployee', [LoginController::class, 'addEmployee'])->name('addEmployee');
    Route::post('/saveEmployee', [LoginController::class, 'saveEmployee'])->name('saveEmployee');
    Route::post('/viewEmployee', [LoginController::class, 'viewEmployee'])->name('viewEmployee');
    Route::post('/clockIn', [LoginController::class, 'clockIn'])->name('clockIn');
    Route::post('/clockOut', [LoginController::class, 'clockOut'])->name('clockOut');
    Route::post('/lastClockIn', [LoginController::class, 'lastClockIn'])->name('lastClockIn');
    Route::post('/overAllAttendance', [LoginController::class, 'overAllAttendance'])->name('overAllAttendance');
    Route::post('/totalAttendance', [LoginController::class, 'totalAttendance'])->name('totalAttendance');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});