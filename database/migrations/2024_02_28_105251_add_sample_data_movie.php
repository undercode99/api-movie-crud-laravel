<?php

use App\Models\Movie;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $movies = [
            [
                'id' => 1,
                'title' => 'Raya and the Last Dragon',
                'slug' => 'raya-and-the-last-dragon-2021',
                'category' => 'Animation',
                'year' => 2021,
                'rating' => 8.5,
                'duration' => 100,
                'description' => 'Long ago, in the fantasy world of Kumandra, humans and dragons lived together in harmony. However, when an evil monster known as Druun threatened the land, the dragons sacrificed themselves to save humanity.',
            ],
            [
                'id' => 2,
                'title' => 'The Batman',
                'slug' => 'the-batman-2022',
                'category' => 'Action',
                'year' => 2022,
                'rating' => 8.8,
                'duration' => 120,
                'description' => 'In his second year of fighting crime, Batman uncovers corruption in Gotham City that connects to his own family while facing a serial killer known as the Riddler.',
            ],
            [
                'id' => 3,
                'title' => 'Planet Earth 2',
                'slug' => 'planet-earth-2-2021',
                'category' => 'Animation',
                'year' => 2021,
                'rating' => 7.2,
                'duration' => 90,
                'description' => 'A chance meeting between two underground utopias sets off a race against time to decide where their next meal is and who gets to drive it.',
            ],
        ];
        Movie::insert($movies);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Movie::destroy([1, 2]);
    }
};
