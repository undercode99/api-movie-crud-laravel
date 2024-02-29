<?php

namespace App\Http\Resources\Movie;

use Illuminate\Http\Resources\Json\JsonResource;

class MovieDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'category' => $this->category,
            'year' => $this->year,
            'rating' => $this->rating,
            'duration' => $this->duration,
            'duration_humanized' => $this->durationHumanized(),
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    private function durationHumanized()
    {
        $h = floor($this->duration / 60);
        $m = $this->duration % 60;

        return $h.'h '.$m.'m';
    }
}
