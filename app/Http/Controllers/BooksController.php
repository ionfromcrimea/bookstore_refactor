<?php

namespace App\Http\Controllers;

use App\Book;
use App\Http\Requests\CreateBookRequest;
use App\Http\Requests\UpdateBookRequest;
use Illuminate\Http\Request;
use App\Http\Resources\BooksResource;
use App\Http\Resources\BooksCollection;
use Spatie\QueryBuilder\QueryBuilder;

class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = QueryBuilder::for(Book::class)->allowedSorts([
            'title',
            'publication_year',
            'created_at',
            'updated_at',
        ])
            ->allowedIncludes('authors')
            ->jsonPaginate();
//        return view('dd', compact('books'));
         return new BooksCollection($books);
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateBookRequest $request)
    {
        $book = Book::create([
            'title' => $request->input('data.attributes.title'),
            'description' => $request->input('data.attributes.description'),
            'publication_year' => $request->input('data.attributes.publication_year'),
        ]);
        return (new BooksResource($book))
            ->response()
            ->header('Location', route('books.show', [
                'book' => $book,
            ]));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Book $book
     * @return \Illuminate\Http\Response
     */
    public function show($book)
    {
        $query = QueryBuilder::for(Book::where('id', $book))
            ->allowedIncludes('authors')
            ->firstOrFail();
//        return view('dd', compact('query'));
        return new BooksResource($query);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Book $book
     * @return \Illuminate\Http\Response
     */
//    public function edit(Book $book)
//    {
//        //
//    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Book $book
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $book->update($request->input('data.attributes'));
        return new BooksResource($book);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Book $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return response(null, 204);
    }
}
