<?php

use App\Http\Controllers\PageController;
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


Route::get('/',function(){
    return view('auth');
})->name('login');
Route::get('/new-workmen', [PageController::class, 'newWorkmenPage'])->name('new-workmen');
Route::get('/attendence', [PageController::class, 'takeAttendencePage'])->name('attendence');
Route::get('/reports',[PageController::class,'reportsPage'])->name('reports');
Route::get('/sites',[PageController::class,'sitePage'])->name('locations');
Route::get('/dashboard',[PageController::class,'dashboard'])->name('dashboard');
