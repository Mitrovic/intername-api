<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookControllerTest extends TestCase
{

    public function testIndex()
    {

        $response = $this->get('/api/books');

        $response->assertStatus(200);

    }

    public function testShow()
    {
        // Make a GET request to the /books/{id} endpoint
        $response = $this->get("/api/books/5");

        $response->assertStatus(200);

    }

}
