<?php

namespace Tests\Unit\Services\Movie;

use App\Exceptions\ExceptionService;
use App\Models\Movie;
use App\Repositories\Movie\EloquentMovieRepository;
use App\Services\Movie\MovieService;
use Mockery;
use Tests\TestCase;

class MovieServiceTest extends TestCase
{
    protected $movieService;

    protected $movieRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->movieRepository = Mockery::mock(EloquentMovieRepository::class);
        $this->movieService = new MovieService($this->movieRepository);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    /** @test */
    public function it_can_list_movies()
    {
        $limit = 10;

        $listMovies = [
            [
                'id' => 1,
                'title' => 'Test Movie',
                'category' => 'Test Category',
                'year' => '2023',
                'rating' => 7.5,
                'duration' => 120,
                'description' => 'This is a test movie description.',
            ],
        ];

        $this->movieRepository
            ->shouldReceive('get')
            ->with($limit)
            ->once()
            ->andReturn($listMovies);

        $movies = $this->movieService->list($limit);

        $this->assertEquals($listMovies, $movies);
    }

    /** @test */
    public function it_can_get_movie_detail()
    {
        $slugUrl = 'test-movie';

        $this->movieRepository
            ->shouldReceive('getBySlug')
            ->with($slugUrl)
            ->once()
            ->andReturn(new Movie());

        $detail = $this->movieService->detail($slugUrl);

        $this->assertInstanceOf(Movie::class, $detail);
    }

    /** @test */
    public function it_can_delete_a_movie()
    {
        $movieId = 1;

        $this->movieRepository
            ->shouldReceive('findById')
            ->with($movieId)
            ->once()
            ->andReturn(new Movie());

        $this->movieRepository
            ->shouldReceive('deleteById')
            ->with($movieId)
            ->once()
            ->andReturn(true);

        $this->assertEquals('Movie deleted successfully', $this->movieService->delete($movieId)->message);
    }

    /** @test */
    public function it_throws_exception_when_movie_not_found_for_detail()
    {
        $this->expectException(ExceptionService::class);

        $slugUrl = 'non-existing-slug';

        $this->movieRepository
            ->shouldReceive('getBySlug')
            ->with($slugUrl)
            ->once()
            ->andReturn(null);

        $this->movieService->detail($slugUrl);
    }

    /** @test */
    public function it_can_create_a_movie()
    {
        $movieData = [
            'title' => 'Test Movie',
            'category' => 'Test Category',
            'year' => '2023',
            'rating' => 7.5,
            'duration' => 120,
            'description' => 'This is a test movie description.',
        ];

        $this->movieRepository
            ->shouldReceive('store')
            ->once()
            ->andReturn(new Movie());

        $createdMovie = $this->movieService->create($movieData);

        $this->assertInstanceOf(Movie::class, $createdMovie);
    }

    /** @test */
    public function it_throws_exception_when_movie_not_found_for_delete()
    {
        $this->expectException(ExceptionService::class);

        $movieId = 999;

        $this->movieRepository
            ->shouldReceive('findById')
            ->with($movieId)
            ->once()
            ->andReturn(null);

        $this->movieService->delete($movieId);
    }
}
