<?php

namespace App\Repositories\Movie;

interface MovieRepository
{
    public function get($limit);

    public function store($data);

    public function update($movie, $data);

    public function deleteById(int $id);

    public function getBySlug($slugUrl);

    public function findById(int $id);
}
