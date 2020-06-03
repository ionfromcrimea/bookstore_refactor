<?php

namespace App\Http\Controllers;

use App\Author;
use App\Http\Requests\CreateAuthorRequest;
use App\Http\Requests\JSONAPIRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Http\Resources\AuthorsResource;
use App\Http\Resources\JSONAPICollection;
use App\Http\Resources\JSONAPIResource;
use App\Services\JSONAPIService;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class AuthorsController extends Controller
{
    private $service;
    public function __construct(JSONAPIService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $authors = Author::all();
//        $authors = QueryBuilder::for(Author::class)->allowedSorts([
//            'name',
//            'created_at',
//            'updated_at',
//        ])->jsonPaginate();
        return $this->service->fetchResources(Author::class, 'authors');
//        return $authors;
//        $responce = AuthorsResource::collection($authors);
//        $responce = new JSONAPICollection($authors);
//        dd($responce);
//        return $responce;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
//    public function create()
//    {
//        //
//    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JSONAPIRequest $request)
    {
//        $author = Author::create([
//            'name' => $request->input('data.attributes.name'),
//        ]);
//        return (new JSONAPIResource($author))
//            ->response()
//            ->header('Location', route('authors.show', ['author' => $author->id]));;
        return $this->service->createResource(Author::class, $request->input('data.attributes'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function show($author)
    {
//        $responce = new JSONAPIResource($author);
//        return $responce;
//        return $this->service->fetchResource($author);
        return $this->service->fetchResource(Author::class, $author, 'authors');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Author  $author
     * @return \Illuminate\Http\Response
     */
//    public function edit(Author $author)
//    {
//        //
//    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function update(JSONAPIRequest $request, Author $author)
    {
//        $author->update($request->input('data.attributes'));
//        return (new JSONAPIResource($author))
//            ->response()
//            ->header('Location', route('authors.show', ['author' => $author->id]));;
        return $this->service->updateResource($author, $request->input('data.attributes'));
//        return new AuthorsResource($author);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function destroy(Author $author)
    {
//        $author->delete();
//        return response(null, 204);
        return $this->service->deleteResource($author);
    }
}
