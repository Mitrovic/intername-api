<?php

namespace Database\Seeders;

use App\Models\CustomUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Book;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run($authorId = null): void
    {
        $response = Http::get('https://gutendex.com/books');
        $booksData = $response->json();

        // Seed books data
        foreach ($booksData['results'] as $bookData) {
            // Create a Book model instance
            $book = new Book();

            // Set book attributes
            $book->title = $bookData['title'];

            $book->description =  implode(', ', $bookData['subjects']);

            $book->author_id = $authorId ?? $this->getRandomAuthorId();

            $book->cover_image = $this->extractCoverImageUrl($bookData);

            // Set random price if missing
            $book->price = $bookData['price'] ?? rand(1000, 6000) / 100; // Random price between $10 and $60

            // Save the book
            $book->save();
        }
    }

    private function getRandomAuthorId()
    {
        $randomAuthor = CustomUser::inRandomOrder()->first();

        return $randomAuthor->id;
    }

    private function extractCoverImageUrl($bookData)
    {
        // Check if "image/jpeg" format is present
        if (isset($bookData['formats']['image/jpeg'])) {
            return $bookData['formats']['image/jpeg'];
        }

        return null;
    }
}
