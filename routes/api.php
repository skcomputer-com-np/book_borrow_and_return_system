<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowTransactionController;
use App\Http\Controllers\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::apiResource('authors', AuthorController::class);
    Route::apiResource('books', BookController::class);
    Route::apiResource('students', StudentController::class);
    Route::apiResource('borrow_transactions', BorrowTransactionController::class)->only(['index', 'store', 'show']);

    //overdue and return route
    Route::post(
        'borrow_transactions/{borrow_transaction}/return',
        [BorrowTransactionController::class, 'returnBook']
    );
    Route::get('borrow_transactions/overdue/list', [BorrowTransactionController::class, 'overdue']);
});
