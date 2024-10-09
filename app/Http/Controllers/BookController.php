<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Book::query();
        $title = $request->title;
        $author = $request->author;

        if ($request->has('title')) {   
            $query->where('title', $title);
        } else if ($request->has('author')) {
            $query->where('author', $author);
        }

        $tasks = $query->paginate(10);
        return response()->json($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'total_copies' => 'required|numeric',
            'available_copies' => 'required|numeric'
        ]);

        $book = Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'total_copies' => $request->total_copies,
            'available_copies' => $request->total_copies
        ]);

        return response()->json($book, 201);
    }


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
            'title' => 'required|string',
            'author' => 'required|string',
            'total_copies' => 'require|numeric',
            'available_copies' => 'require|numeric'
        ]);

        $book->update([
            'title' => $request->title,
            'author' => $request->author,
            'total_copies' => $request->total_copies,
            'available_copies' => $request->available_copies
        ]);

        return response()->json($book);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return response()->json(null,204);
    }
}
