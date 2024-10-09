<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loans = Loan::all();
        return response()->json($loans);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'numeric|required',
            'book_id' => 'numeric|required',
            'due_date' => 'date|required'
        ]);

        $bookId = $request->input('book_id');
        DB::transaction(function () use ($bookId, $request) {
            $book = Book::findOrFail($bookId); // Mencari buku dan otomatis throw 404 jika tidak ditemukan

            if ($book->available_copies > 0) {
                $book->available_copies -= 1;
                $book->save();

                $loan = Loan::create([
                    'user_id' => $request->user_id,
                    'book_id' => $request->book_id,
                    'loan_date' => now(),
                    'due_date' => $request->due_date,
                ]);

                return response()->json($loan, 201);
            }

            // Jika buku tidak tersedia
            return response()->json(['message' => 'Buku tidak tersedia'], 400);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return response()->json($book);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'status' => 'string|required'
        ]);

        $book->update([
            'status' => $request->status
        ]);

        return response()->json($book);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return response()->json(null, 204);
    }
}
