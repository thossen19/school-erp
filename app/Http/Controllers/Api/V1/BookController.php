<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Library\Book;
use App\Services\Library\LibraryService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookController extends Controller
{
    use ApiResponseTrait;

    protected LibraryService $libraryService;

    public function __construct(LibraryService $libraryService)
    {
        $this->libraryService = $libraryService;
    }

    public function index(Request $request): JsonResponse
    {
        $books = Book::with('category')->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))->when($request->status, fn($q) => $q->where('status', $request->status))->when($request->language, fn($q) => $q->where('language', $request->language))->when($request->search, fn($q) => $q->where(function($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")->orWhere('isbn', 'like', "%{$request->search}%")->orWhere('author', 'like', "%{$request->search}%")->orWhere('publisher', 'like', "%{$request->search}%");
            }))->orderBy('title')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($books, 'Books retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'required|string|max:50|unique:books,isbn',
            'author' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'category_id' => 'required|integer|exists:book_categories,id',
            'language' => 'nullable|string|max:50',
            'edition' => 'nullable|string|max:50',
            'published_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'pages' => 'nullable|integer|min:0',
            'price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'available_quantity' => 'required|integer|min:0|lte:quantity',
            'rack_no' => 'nullable|string|max:50',
            'shelf_no' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:2000',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'nullable|string|in:available,damaged,lost,withdrawn',
        ]);

        $book = Book::create($validated);

        if ($request->hasFile('cover_image')) {
            $book->update(['cover_image' => $request->file('cover_image')->store('books/covers', 'public')]);
        }

        return $this->createdResponse($book->load('category'), 'Book created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(
            Book::with('category', 'issues')->findOrFail($id),
            'Book retrieved'
        );
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $book = Book::findOrFail($id);
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'isbn' => 'sometimes|string|max:50|unique:books,isbn,' . $id,
            'author' => 'sometimes|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'category_id' => 'sometimes|integer|exists:book_categories,id',
            'language' => 'nullable|string|max:50',
            'edition' => 'nullable|string|max:50',
            'pages' => 'nullable|integer|min:0',
            'price' => 'nullable|numeric|min:0',
            'quantity' => 'sometimes|integer|min:0',
            'available_quantity' => 'sometimes|integer|min:0|lte:quantity',
            'rack_no' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:2000',
            'status' => 'nullable|string|in:available,damaged,lost,withdrawn',
        ]);
        $book->update($validated);
        return $this->updatedResponse($book->fresh()->load('category'), 'Book updated');
    }

    public function destroy(int $id): JsonResponse
    {
        Book::findOrFail($id)->delete();
        return $this->deletedResponse('Book deleted');
    }

    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q');
        if (!$query) return $this->errorResponse('Search query is required', 400);

        $books = Book::with('category')->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")->orWhere('isbn', 'like', "%{$query}%")->orWhere('author', 'like', "%{$query}%");
            })->limit(20)->get();

        return $this->successResponse($books, 'Search results');
    }

    public function getByCategory(int $categoryId): JsonResponse
    {
        $books = Book::where('category_id', $categoryId)->orderBy('title')->get();
        return $this->successResponse($books, 'Books by category');
    }
}
