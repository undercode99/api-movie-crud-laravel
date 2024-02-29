<?php

namespace Tests\Unit\Repositories\Movie;

use App\Models\Movie;
use App\Repositories\Movie\EloquentMovieRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentMovieRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = new EloquentMovieRepository(new Movie());

        // truncating the movies table
        Movie::truncate();
    }

    /** @test */
    public function it_can_get_movies()
    {
        // Creating 3 movies
        Movie::factory()->count(3)->create();

        // Testing the get method
        $movies = $this->repository->get(10);

        // Asserting that the number of retrieved movies equals 3
        $this->assertCount(3, $movies);
    }

    /** @test */
    public function it_can_store_a_movie()
    {
        // Creating a new movie data array
        $movieData = [
            'title' => 'Test Movie',
            'category' => 'Animation',
            'slug' => 'test-movie-slug',
            'description' => 'This is a test movie',
            'year' => 2021,
            'rating' => 5.2,
            'duration' => 120,
        ];

        // Storing the movie
        $storedMovie = $this->repository->store($movieData);

        // Asserting that the movie was stored successfully
        $this->assertInstanceOf(Movie::class, $storedMovie);
        $this->assertEquals($movieData['title'], $storedMovie->title);
    }

    /** @test */
    public function it_can_update_a_movie()
    {
        $movie = Movie::factory()->create();

        // Updating the movie
        $updatedData = [
            'title' => 'Updated Title',
            'description' => 'Updated description',
        ];
        $this->repository->update($updatedData, $movie->id);

        // Retrieving the updated movie
        $updatedMovie = Movie::find($movie->id);

        // Asserting that the movie was updated successfully
        $this->assertEquals($updatedData['title'], $updatedMovie->title);
        $this->assertEquals($updatedData['description'], $updatedMovie->description);
    }

    /** @test */
    public function it_can_delete_a_movie()
    {
        // Retrieving the movie
        $movie = Movie::factory()->create();

        // Deleting the movie
        $this->repository->deleteById($movie->id);

        // Asserting that the movie was deleted successfully
        $this->assertNull(Movie::find($movie->id));
    }

    /** @test */
    public function it_can_get_a_movie_by_slug()
    {
        // Creating a movie with a specific slug
        $movie = Movie::factory()->create([
            'slug' => 'golden-goose-2022',
        ]);

        // Retrieving the movie by its slug
        $retrievedMovie = $this->repository->getBySlug($movie->slug);

        // Asserting that the retrieved movie matches the created one
        $this->assertEquals($movie->id, $retrievedMovie->id);
    }

    /** @test */
    public function it_can_find_a_movie_by_id()
    {
        // Creating a movie
        $movie = Movie::factory()->create();

        // Retrieving the movie by its ID
        $retrievedMovie = $this->repository->findById($movie->id);

        // Asserting that the retrieved movie matches the created one
        $this->assertEquals($movie->id, $retrievedMovie->id);
    }
}
