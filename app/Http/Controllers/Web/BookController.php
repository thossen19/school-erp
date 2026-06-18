<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Library\Book;
use App\Models\Library\BookCategory;
use Illuminate\Http\Request;

class BookController extends Controller
{

    public function index(Request $request)
    {
        $books = Book::with('category')->when($request->search, fn($q) => $q->where(function($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")->orWhere('author', 'like', "%{$request->search}%")->orWhere('isbn', 'like', "%{$request->search}%");
            }))->when($request->category_id, fn($q) => $q->where('book_category_id', $request->category_id))->orderBy('title')->paginate(20);

        $categories = BookCategory::active()->orderBy('name')->get();
        return view('books.index', compact('books', 'categories'));
    }

    public function create()
    {
        $categories = BookCategory::active()->orderBy('name')->get();
        return view('books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20|unique:books',
            'publisher' => 'nullable|string|max:255',
            'book_category_id' => 'nullable|integer|exists:book_categories,id',
            'edition' => 'nullable|string|max:100',
            'publication_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'language' => 'nullable|string|max:50',
            'pages' => 'nullable|integer|min:1',
            'rack_number' => 'nullable|string|max:50',
            'shelf_number' => 'nullable|string|max:50',
            'barcode' => 'nullable|string|max:125|unique:books',
            'price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'available_quantity' => 'required|integer|min:0|lte:quantity',
            'description' => 'nullable|string|max:1000',
            'status' => 'boolean',
        ]);

        $validated['status'] = $request->boolean('status');
        $validated['school_id'] = auth()->user()->school_id ?? session('school_id', 1);

        Book::create($validated);
        return redirect()->route('books.index')->with('success', 'Book added successfully');
    }

    public function show(int $id)
    {
        $book = Book::with('category', 'issues.student', 'issues.librarian')->withCount('issues')->findOrFail($id);
        return view('books.show', compact('book'));
    }

    public function edit(int $id)
    {
        $book = Book::findOrFail($id);
        $categories = BookCategory::active()->orderBy('name')->get();
        return view('books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, int $id)
    {
        $book = Book::findOrFail($id);
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'author' => 'sometimes|string|max:255',
            'isbn' => 'nullable|string|max:20|unique:books,isbn,' . $id,
            'publisher' => 'nullable|string|max:255',
            'book_category_id' => 'nullable|integer|exists:book_categories,id',
            'edition' => 'nullable|string|max:100',
            'publication_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'language' => 'nullable|string|max:50',
            'pages' => 'nullable|integer|min:1',
            'rack_number' => 'nullable|string|max:50',
            'shelf_number' => 'nullable|string|max:50',
            'barcode' => 'nullable|string|max:125|unique:books,barcode,' . $id,
            'price' => 'nullable|numeric|min:0',
            'quantity' => 'sometimes|integer|min:1',
            'available_quantity' => 'sometimes|integer|min:0|lte:quantity',
            'description' => 'nullable|string|max:1000',
            'status' => 'boolean',
        ]);

        $validated['status'] = $request->boolean('status');

        $book->update($validated);
        return redirect()->route('books.index')->with('success', 'Book updated successfully');
    }

    public function destroy(int $id)
    {
        Book::findOrFail($id)->delete();
        return redirect()->route('books.index')->with('success', 'Book deleted successfully');
    }
}
