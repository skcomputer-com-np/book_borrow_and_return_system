<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBorrowTransactionRequest;
use App\Http\Resources\BorrowTransactionResource;
use App\Models\Book;
use App\Models\BorrowTransaction;
use Illuminate\Http\Request;

class BorrowTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = BorrowTransaction::with(['book', 'student']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        $borrowings = $query->latest()->paginate(15);
        return BorrowTransactionResource::collection($borrowings);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBorrowTransactionRequest $request)
    {
        $book = Book::findOrFail($request->book_id);

        // Check if book is available
        if (!$book->isAvailable()) {
            return response()->json([
                'message' => 'Book is not available for borrowing'
            ]);
        }

        // Create borrowing recored
        $borrowing = BorrowTransaction::create($request->validated());

        // Update book availability
        $book->borrow();

        $borrowing->load(['book', 'student']);

        return new BorrowTransactionResource($borrowing);
    }

    /**
     * Display the specified resource.
     */
    public function show(BorrowTransaction $borrowTransaction)
    {
        $borrowTransaction->load(['book', 'student']);
        return new BorrowTransactionResource($borrowTransaction);
    }

    public function returnBook(BorrowTransaction $borrowTransaction)
    {
        // return response()->json([
        //     'message' => $borrowTransaction
        // ]);

        // Check book status
        if (($borrowTransaction->status !== "borrowed")) {
            return response()->json([
                'message' => 'Book has been already returned!'
            ]);
        }

        // Update borrowing record
        $borrowTransaction->update([
            'return_date' => now(),
            'status' => 'returned'
        ]);

        // Update book availability
        $borrowTransaction->book->returnBook();

        $borrowTransaction->load(['book', 'student']);

        return new BorrowTransactionResource($borrowTransaction);
    }

    public function overdue()
    {
        $overdueBorrowings = BorrowTransaction::with('book', 'student')
            ->where('status', 'borrowed')
            ->where('due_date', '<', now())
            ->get();

        if ($overdueBorrowings->count() > 0) {
            // Update status to overdue
            BorrowTransaction::with('book', 'student')
                ->where('status', 'borrowed')
                ->where('due_date', '<', now())
                ->update(['status' => 'overdue']);
        }

        $overdueBorrowings = BorrowTransaction::with('book', 'student')
            ->where('status', 'overdue')
            ->get();
        return BorrowTransactionResource::collection($overdueBorrowings);
    }
}
