<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
    // Get all books
    public function index(Request $request)
    {
        $query = $request->input('query');

        $books = Book::when($query, function ($queryBuilder) use ($query) {
                return $queryBuilder->where('title', 'like', "%$query%");
            })
            ->get();
        return response()->json([
            'data' => $books
        ]);
    }

    // Get a specific book by ID
    public function show($id)
    {
        $book = Book::findOrFail($id);
        return response()->json($book);
    }

    // Store a new book
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'author_id' => 'required|exists:custom_users,id',
            'cover_image' => 'required|string',
            'price' => 'required|numeric',
        ]);

        $book = Book::create($request->all());

        return response()->json($book, 201);
    }

    // Update an existing book
    public function update(Request $request, $id)
    {
        Log::info($request);
        $book = Book::findOrFail($id);

        // Check if the authenticated user is the author of the book
        if (Auth::user()->id !== $book->author_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        Log::info($request->input('title'));
        $book->title = $request->input('title');
        $book->description = $request->input('description');
        $book->cover_image = $request->input('cover_image');
        $book->price = $request->input('price');
        $appUrl = env('APP_URL');

        if ($request->hasFile('imageFile')) {
            $imageFile = $request->file('imageFile');
            $imageName = time() . '.' . $imageFile->getClientOriginalExtension();
            $imagePath = public_path('/uploads'); // Adjust the upload path as needed
            $imageFile->move($imagePath, $imageName);

            // Update the cover image field with the new image path
            $book->cover_image = $appUrl.'/uploads/' . $imageName;
        }
        $book->save();

        return response()->json($book, 200);
    }

    // Delete a book
    public function destroy($id)
    {
        $book = Book::findOrFail($id);

        // Check if the authenticated user is the author of the book
        if (Auth::user()->id !== $book->author_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $book->delete();

        return response()->json(null, 204);
    }
    public function updatePublishStatus($id)
    {
        // Find the book by ID
        $book = Book::findOrFail($id);

        // Update the publish status
        $book->update(['publish' => !$book->publish]);

        return response()->json($book, 200);
    }
}
