<?php

namespace Tests\Feature\Http;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MovieTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_list_movie(): void
    {
        $response = $this->get('/api/v1/movies');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [],
            'links' => [],
            'meta' => [],
        ]);
        // Check if there are 3 movies same as in the database (see database/migrations/2024_02_28_105251_add_sample_data_movie.php)
        $response->assertJsonCount(3, 'data');
    }

    public function test_get_movie_detail(): void
    {
        // Check if movie exists in the database (see database/migrations/2024_02_28_105251_add_sample_data_movie.php)
        $response = $this->get('/api/v1/movies/raya-and-the-last-dragon-2021');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [],
        ]);
    }

    public function test_get_movie_detail_not_found(): void
    {
        $response = $this->get('/api/v1/movies/not-found');
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Movie not found',
            'success' => false,
        ]);
    }

    public function test_create_movie(): void
    {
        $movieData = [
            'title' => 'Test Movie',
            'category' => 'Test Category',
            'year' => '2023',
            'rating' => 7.5,
            'duration' => 120,
            'description' => 'This is a test movie description.',
        ];
        $response = $this->post('/api/v1/movies', $movieData);
        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'id' => 4,
            ],
        ]);
    }

    public function test_create_movie_validation_error(): void
    {
        $movieData = [];
        $response = $this->post('/api/v1/movies', $movieData);
        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'title' => ['The title field is required.'],
                'category' => ['The category field is required.'],
                'year' => ['The year field is required.'],
                'rating' => ['The rating field is required.'],
                'duration' => ['The duration field is required.'],
                'description' => ['The description field is required.'],
            ],
        ]);

        $movieData = [
            'title' => 'Slug exist',
            'slug' => 'raya-and-the-last-dragon-2021',
            'category' => 'Test Category',
            'year' => '2023',
            'rating' => 7.5,
            'duration' => 120,
            'description' => 'This is a test movie description.',
        ];
        $response = $this->post('/api/v1/movies', $movieData);
        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'slug' => ['The slug has already been taken.'],
            ],
        ]);

        $movieData = [
            'title' => 'Te',
            'category' => 'Test Category',
            'year' => 'dsada',
            'rating' => 7.5,
            'duration' => 'x120',
            'description' => 'This',
        ];
        $response = $this->post('/api/v1/movies', $movieData);
        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'title' => ['The title field must be at least 5 characters.'],
                'year' => ['The year field must match the format Y.'],
                'duration' => ['The duration field must be a number.'],
                'description' => ['The description field must be at least 10 characters.'],
            ],
        ]);
    }

    public function test_delete_movie(): void
    {
        $response = $this->delete('/api/v1/movies/3');
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Movie deleted successfully',
            'success' => true,
        ]);
    }

    public function test_delete_movie_not_found(): void
    {
        $response = $this->delete('/api/v1/movies/100');
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Movie not found',
            'success' => false,
        ]);
    }

    public function test_update_movie(): void
    {
        $movieData = [
            'title' => 'Test Movie',
            'category' => 'Test Category',
            'year' => '2023',
            'rating' => 7.5,
            'duration' => 120,
            'description' => 'This is a test movie description.',
        ];
        $response = $this->put('/api/v1/movies/3', $movieData);
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Movie updated successfully',
            'success' => true,
        ]);
    }

    public function test_update_movie_not_found(): void
    {
        $movieData = [
            'title' => 'Test Movie',
            'category' => 'Test Category',
            'year' => '2023',
            'rating' => 7.5,
            'duration' => 120,
            'description' => 'This is a test movie description.',
        ];
        $response = $this->put('/api/v1/movies/100', $movieData);
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Movie not found',
            'success' => false,
        ]);
    }

    public function test_update_movie_validation_error(): void
    {

        $movieData = [
            'title' => 'Slug exist',
            'slug' => 'the-batman-2022',
            'category' => 'Test Category',
            'year' => '2023',
            'rating' => 7.5,
            'duration' => 120,
            'description' => 'This is a test movie description.',
        ];
        $response = $this->put('/api/v1/movies/3', $movieData);
        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'slug' => ['The slug has already been taken.'],
            ],
        ]);

        $movieData = [
            'title' => 'Te',
            'category' => 'Test Category',
            'year' => 'dsada',
            'rating' => 7.5,
            'duration' => 'x120',
            'description' => 'This',
        ];
        $response = $this->put('/api/v1/movies/3', $movieData);
        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'title' => ['The title field must be at least 5 characters.'],
                'year' => ['The year field must match the format Y.'],
                'duration' => ['The duration field must be a number.'],
                'description' => ['The description field must be at least 10 characters.'],
            ],
        ]);
    }
}
