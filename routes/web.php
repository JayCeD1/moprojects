<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionsController;
use App\Http\Controllers\AnswersController;

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

Route::resource('questions', QuestionsController::class)->except('show');
//Route::post('questions/{questions}/answer',[AnswersController::class,'store'])->name('answers.store');
Route::resource('questions.answers',AnswersController::class)->only('store','edit','update','destroy');
Route::get('/questions/{question:slug}',[QuestionsController::class,'show'])->name('questions.show');
Route::post('answers/{answer}/accept',\App\Http\Controllers\AcceptAnswerController::class)->name('answers.accept');
Route::post('questions/{question}/favorites',[\App\Http\Controllers\FavoritesController::class,'store'])->name('questions.favorite');
Route::delete('questions/{question}/favorites',[\App\Http\Controllers\FavoritesController::class,'destroy'])->name('questions.unfavorite');
Route::post('/questions/{question}/vote',\App\Http\Controllers\VoteQuestionController::class);
Route::post('/answers/{answer}/vote',\App\Http\Controllers\VoteAnswerController::class);

