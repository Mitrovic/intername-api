<?php

namespace Database\Seeders;

use App\Models\CustomUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authorData = [
            'name' => 'Author',
            'email' => 'author@example.com',
            'password' => bcrypt('password'),
            'author_pseudonym' => 'AuthorPseudonym',
        ];

        // Create the author
        $author = CustomUser::create($authorData);

        // Now, you can use the author's ID in your BooksTableSeeder
        $this->call(BooksTableSeeder::class, ['author_id' => $author->id]);
    }
}
