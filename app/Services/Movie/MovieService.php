<?php

namespace App\Services\Movie;

use App\Exceptions\ExceptionService;
use App\Repositories\Movie\EloquentMovieRepository;
use App\Services\BaseService;
use Illuminate\Support\Str;

class MovieService extends BaseService
{
    protected $movieRepository;

    public function __construct(EloquentMovieRepository $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

    public function list($limit = 10)
    {

        return $this->movieRepository->get($limit);
    }

    public function detail(string $slugUrl)
    {
        $detail = $this->movieRepository->getBySlug($slugUrl);
        if (! $detail) {
            throw ExceptionService::notFound('Movie not found');
        }

        return $detail;
    }

    public function delete($id)
    {
        $movie = $this->movieRepository->findById($id);
        if (! $movie) {
            throw ExceptionService::notFound('Movie not found');
        }
        $deleted = $this->movieRepository->deleteById($id);
        if (! $deleted) {
            throw ExceptionService::internalServerError('Movie not deleted');
        }

        return $this->success('Movie deleted successfully');
    }

    public function create(array $data)
    {
        $createRules = [
            'title' => 'required|max:255|min:5',
            'category' => 'required',
            'year' => 'required|date_format:Y',
            'rating' => 'required|numeric',
            'duration' => 'required|numeric',
            'description' => 'required|min:10',
            'slug' => 'nullable|unique:movies,slug',
        ];
        $this->validateOrFail($data, $createRules);

        $data['slug'] = ! empty($data['slug']) ? $data['slug'] : $this->_createSlug($data['title']);

        return $this->movieRepository->store($data);
    }

    private function _createSlug($title)
    {
        return Str::slug($title).'-'.random_int(10000, 999999);
    }

    public function update($id, array $data)
    {
        $movie = $this->movieRepository->findById($id);
        if (! $movie) {
            throw ExceptionService::notFound('Movie not found');
        }

        $updateRules = [
            'title' => 'max:255|min:5',
            'year' => 'date_format:Y',
            'rating' => 'numeric',
            'duration' => 'numeric',
            'description' => 'min:10',
            'slug' => 'unique:movies,slug,'.$id,
        ];

        $this->validateOrFail($data, $updateRules);

        $updated = $this->movieRepository->update($data, $id);
        if (! $updated) {
            throw ExceptionService::internalServerError('Movie not updated');
        }

        return $this->success('Movie updated successfully');
    }
}
