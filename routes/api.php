<?php

use App\Http\Controllers\Api\V2\BookController as V2BookController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowTransactionController;
use App\Http\Controllers\StudentController;
use App\Models\Author;
use App\Models\Book;
use App\Models\BorrowTransaction;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('v1')->group(function () {
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

        //Statistics
        Route::get('statistics', function () {
            return response()->json([
                'total_books' => Book::count(),
                'total_authors' => Author::count(),
                'total_students' => Student::count(),
                'books_borrow' => BorrowTransaction::where('status', 'borrowed')->count(),
                'overdue_browings' => BorrowTransaction::where('status', 'overdue')->count(),

            ]);
        });
    });

    Route::prefix('v2')->group(function () {
        Route::get('/latest/five/books', [V2BookController::class, 'firstFiveBooks']);
    });
});
