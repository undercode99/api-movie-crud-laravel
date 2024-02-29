<?php

namespace App\Http\Controllers\V1\Movie;

use App\Http\Controllers\V1\Controller;
use App\Http\Resources\Movie\MovieDetailResource;
use App\Http\Resources\Movie\MovieResource;
use App\Services\Movie\MovieService;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="Movie",
 *     title="Movie",
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="slug", type="string", example="raya-and-the-last-dragon-2021"),
 *     @OA\Property(property="title", type="string", example="Raya and the Last Dragon"),
 *     @OA\Property(property="rating", type="number", format="float", example=8.5),
 *     @OA\Property(property="year", type="integer", example=2021),
 * )
 *
 * @OA\Schema(
 *     schema="MovieUpdateOrCreate",
 *     title="MovieUpdateOrCreate",
 *
 *     @OA\Property(property="title", type="string", example="Raya and the Last Dragon"),
 *     @OA\Property(property="slug", type="string", example="raya-and-the-last-dragon-2021", nullable=true),
 *     @OA\Property(property="category", type="string", example="Animation"),
 *     @OA\Property(property="year", type="integer", example=2021),
 *     @OA\Property(property="rating", type="number", format="float", example=8.5),
 *     @OA\Property(property="duration", type="integer", example=100),
 *     @OA\Property(property="description", type="string", example="Raya and the Last Dragon is a 2021 American fantasy adventure film directed by Brad Bird and written by Brad Bird and Jeremy Dawson."),
 * )
 *
 * @OA\Schema(
 *     schema="MovieDetail",
 *     title="MovieDetail",
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="slug", type="string", example="raya-and-the-last-dragon-2021"),
 *     @OA\Property(property="title", type="string", example="Raya and the Last Dragon"),
 *     @OA\Property(property="category", type="string", example="Animation"),
 *     @OA\Property(property="year", type="integer", example=2021),
 *     @OA\Property(property="rating", type="number", format="float", example=8.5),
 *     @OA\Property(property="duration", type="integer", example=100),
 *     @OA\Property(property="duration_humanized", type="string", example="1h 40m"),
 *     @OA\Property(property="description", type="string", example="Raya and the Last Dragon is a 2021 American fantasy adventure film directed by Brad Bird and written by Brad Bird and Jeremy Dawson."),
 *     @OA\Property(property="created_at", type="string", example="2021-01-01T00:00:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", example="2021-01-01T00:00:00.000000Z"),
 * )
 */
class MovieController extends Controller
{
    protected $movieService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/movies",
     *     tags={"movies"},
     *     summary="Get list of movies",
     *     description="Retrieves a list of movies with pagination and sorting",
     *     operationId="getMovies",
     *
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Number of items to return per page",
     *         required=false,
     *
     *         @OA\Schema(
     *             type="integer",
     *             default=10
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number to retrieve",
     *         required=false,
     *
     *         @OA\Schema(
     *             type="integer",
     *             default=1
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *
     *                 @OA\Items(ref="#/components/schemas/Movie")
     *             ),
     *
     *             @OA\Property(
     *                 property="links",
     *                 type="object",
     *                 @OA\Property(property="first", type="string"),
     *                 @OA\Property(property="last", type="string"),
     *                 @OA\Property(property="prev", type="string"),
     *                 @OA\Property(property="next", type="string"),
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(property="from", type="integer"),
     *                 @OA\Property(property="path", type="string"),
     *                 @OA\Property(property="per_page", type="integer"),
     *                 @OA\Property(property="to", type="integer"),
     *             ),
     *         ),
     *     ),
     * )
     */
    public function index(Request $request)
    {
        return MovieResource::collection($this->movieService->list(
            $request->input('limit') ?: 6,
            $request->input('order') ?: 'desc'
        ));
    }

    /**
     * @OA\Get(
     *     path="/api/v1/movies/{slug}",
     *     tags={"movies"},
     *     summary="Get movie by slug",
     *     description="Retrieves a movie by its slug",
     *     operationId="getMovieBySlug",
     *
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         description="Slug of the movie",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="string",
     *             default="raya-and-the-last-dragon-2021"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *
     *         @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/MovieDetail"),
     *         ),
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Movie not found",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Movie not found"),
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=404)
     *         ),
     *     ),
     * )
     */
    public function show($slug)
    {
        return new MovieDetailResource($this->movieService->detail($slug));
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/movies/{id}",
     *     tags={"movies"},
     *     summary="Delete movie",
     *     description="Deletes a movie",
     *     operationId="deleteMovie",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the movie",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer",
     *             default=1
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Movie deleted successfully"),
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200)
     *         ),
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Movie not found",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Movie not found"),
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=404)
     *         ),
     *     ),
     * )
     */
    public function destroy($id)
    {
        return response()->json($this->movieService->delete($id));
    }

    /**
     * @OA\Post(
     *     path="/api/v1/movies",
     *     tags={"movies"},
     *     summary="Create movie",
     *     description="Creates a new movie",
     *     operationId="createMovie",
     *
     *     @OA\RequestBody(
     *         description="Movie data",
     *         required=true,
     *
     *         @OA\JsonContent(type="object", ref="#/components/schemas/MovieUpdateOrCreate"),
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Successful response",
     *
     *         @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/MovieDetail"),
     *         ),
     *     ),
     *
     *    @OA\Response(
     *         response=400,
     *         description="Validation failed",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=400),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="year", type="array",
     *
     *                     @OA\Items(type="string", example="The year field must match the format Y.")
     *                 )
     *             )
     *         )
     *     )
     *)
     */
    public function store(Request $request)
    {
        return new MovieDetailResource($this->movieService->create($request->all()));
    }

    /**
     * @OA\Put(
     *     path="/api/v1/movies/{id}",
     *     tags={"movies"},
     *     summary="Update movie",
     *     description="Updates a movie",
     *     operationId="updateMovie",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the movie",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer",
     *             default=1
     *         )
     *     ),
     *
     *     @OA\RequestBody(
     *         description="Movie data",
     *         required=true,
     *
     *         @OA\JsonContent(type="object", ref="#/components/schemas/MovieUpdateOrCreate"),
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Movie updated successfully"),
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *         ),
     *     ),
     *
     *    @OA\Response(
     *         response=400,
     *         description="Validation failed",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=400),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="year", type="array",
     *
     *                     @OA\Items(type="string", example="The year field must match the format Y.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function update($id, Request $request)
    {
        return response()->json($this->movieService->update($id, $request->all()));
    }
}
