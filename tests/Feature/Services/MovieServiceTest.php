<?php

namespace Tests\Feature\Services\Movie;

use App\Models\Movie;
use App\Services\Movie\MovieService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MovieServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $movieService;

    public function setUp(): void
    {
        parent::setUp();
        $this->movieService = app()->make(MovieService::class);
    }

    /** @test */
    public function it_can_create_a_movie()
    {
        $movieData = [
            'title' => 'Test Movie',
            'category' => 'Test Category',
            'year' => '2022',
            'rating' => 7.5,
            'duration' => 120,
            'description' => 'This is a test movie description.',
        ];

        $createdMovie = $this->movieService->create($movieData);

        $this->assertInstanceOf(Movie::class, $createdMovie);
        $this->assertDatabaseHas('movies', ['title' => 'Test Movie']);
    }

    /** @test */
    public function it_can_update_a_movie()
    {
        $movie = Movie::factory()->create();

        $updatedData = [
            'title' => 'Updated Test Movie',
            'year' => '2023',
            'rating' => 8.0,
        ];

        $this->movieService->update($movie->id, $updatedData);

        $this->assertDatabaseHas('movies', ['title' => 'Updated Test Movie']);
    }

    /** @test */
    public function it_can_list_movies()
    {
        Movie::factory()->count(5)->create();

        $movies = $this->movieService->list(3);

        $this->assertCount(3, $movies);
    }

    /** @test */
    public function it_can_get_movie_detail()
    {
        $movie = Movie::factory()->create();

        $retrievedMovie = $this->movieService->detail($movie->slug);

        $this->assertEquals($movie->id, $retrievedMovie->id);
    }

    /** @test */
    public function it_can_delete_a_movie()
    {
        $movie = Movie::factory()->create();

        $this->movieService->delete($movie->id);

        $this->assertNull(Movie::find($movie->id));
    }
}
