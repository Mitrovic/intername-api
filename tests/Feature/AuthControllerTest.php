<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\CustomUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    public function testUserCanAuthenticate()
    {
        // Create a user for testing
        $user = CustomUser::factory()->create(['password' => Hash::make('password')]);

        // Make a POST request to authenticate
        $response = $this->postJson('/api/authenticate', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        // Assert the response has a 200 status code
        $response->assertStatus(200);

        // Assert the response has the required keys
        $response->assertJsonStructure(['access_token', 'token_type', 'expires_in']);
    }
    public function testAuthenticatedUserCanLogout()
    {
        // Create a user for testing
        $user = CustomUser::factory()->create();

        // Authenticate the user
        $token = auth()->guard('api')->login($user);

        // Make a POST request to logout
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])->postJson('/api/logout');

        // Assert the response has a 200 status code
        $response->assertStatus(200);

        // Assert the response contains the logout message
        $response->assertJson(['message' => 'Successfully logged out']);
    }

    public function testAuthenticatedUserCanRefreshToken()
    {
        // Create a user for testing
        $user = CustomUser::factory()->create();

        // Authenticate the user
        $token = auth()->guard('api')->login($user);

        // Make a POST request to refresh the token
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])->postJson('/api/refresh');

        // Assert the response has a 200 status code
        $response->assertStatus(200);

        // Assert the response has the required keys
        $response->assertJsonStructure(['access_token', 'token_type', 'expires_in']);
    }

    public function testAuthenticatedUserCanGetOwnDetails()
    {
        // Create a user for testing
        $user = CustomUser::factory()->create();

        // Authenticate the user
        $token = auth()->guard('api')->login($user);

        // Make a GET request to get own details
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])->getJson('/api/me');

        // Assert the response has a 200 status code
        $response->assertStatus(200);

        // Assert the response contains the user details
        $response->assertJson(['id' => $user->id, 'email' => $user->email]);
    }
    public function testAuthenticatedUserCanListBooks()
    {
        // Create a user for testing
        $user = CustomUser::factory()->create(['password' => Hash::make('password')]);

        // Authenticate the user
        $token = auth()->guard('api')->login($user);

        // Make a GET request to list books
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])->getJson('/api/user/books');

        // Assert the response has a 200 status code
        $response->assertStatus(200);

        // Your additional assertions for the response structure, content, etc.
    }

    public function testAuthenticatedUserCanUpdateBook()
    {
        // Create a user for testing
        $user = CustomUser::factory()->create(['password' => Hash::make('password')]);

        // Authenticate the user
        $token = auth()->guard('api')->login($user);

        // Create a book for testing
        $book = Book::factory()->create(['author_id' => $user->id]);

        // Make a PUT request to update the book
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->postJson('/api/user/books/' . $book->id, ['title' => 'Updated Title Test','description' => 'Description','cover_image' => 'cover_image_url','price' => 25]);

        // Assert the response has a 200 status code
        $response->assertStatus(200);

        // Your additional assertions for the updated book
    }

    public function testAuthenticatedUserCanDeleteBook()
    {
        // Create a user for testing
        $user = CustomUser::factory()->create(['password' => Hash::make('password')]);

        // Authenticate the user
        $token = auth()->guard('api')->login($user);

        // Create a book for testing
        $book = Book::factory()->create(['author_id' => $user->id]);

        // Make a DELETE request to delete the book
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->deleteJson('/api/user/books/' . $book->id);

        $response->assertStatus(204);
    }
}
