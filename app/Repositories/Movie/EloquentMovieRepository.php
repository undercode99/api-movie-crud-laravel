<?php

namespace App\Repositories\Movie;

use App\Models\Movie;

class EloquentMovieRepository implements MovieRepository
{
    protected $movie;

    public function __construct(Movie $movie)
    {
        $this->movie = $movie;
    }

    public function get($limit)
    {
        return $this->movie->simplePaginate($limit);
    }

    public function store($data)
    {
        return $this->movie->create($data);
    }

    public function update($data, $id)
    {
        return $this->movie->where('id', $id)->update($data);
    }

    public function deleteById(int $id)
    {
        return $this->movie->destroy($id);
    }

    public function getBySlug($slugUrl)
    {
        return $this->movie->where('slug', $slugUrl)->first();
    }

    public function findById(int $id)
    {
        return $this->movie->find($id);
    }
}
