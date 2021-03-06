<?php

use Illuminate\Http\Request;

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::middleware('auth:api')->prefix('v1')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Users
    Route::apiResource('users', 'UsersController');
    Route::get('users/{user}/relationships/comments', 'UsersCommentsRelationshipsController@index')->name('users.relationships.comments');
    Route::patch('users/{user}/relationships/comments', 'UsersCommentsRelationshipsController@update')->name('users.relationships.comments');
    Route::get('users/{user}/comments', 'UsersCommentsRelatedController@index')->name('users.comments');

    Route::get('/users/current', function (Request $request) {
        return $request->user();
    });

    // Authors
    Route::apiResource('authors', 'AuthorsController');

    Route::get('authors/{author}/relationships/books', 'AuthorsBooksRelationshipsController@index')
        ->name('authors.relationships.books');

    Route::get('authors/{author}/books', 'AuthorsBooksRelatedController@index')
        ->name('authors.books');

    Route::patch('authors/{author}/relationships/books', 'AuthorsBooksRelationshipsController@update')
        ->name('authors.relationships.books');

    // Books
    Route::apiResource('books', 'BooksController');

    Route::get('books/{book}/relationships/authors', 'BooksAuthorsRelationshipsController@index')
        ->name('books.relationships.authors');

    Route::get('books/{book}/authors', 'BooksAuthorsRelatedController@index')
        ->name('books.authors');

    Route::patch('books/{book}/relationships/authors', 'BooksAuthorsRelationshipsController@update')
        ->name('books.relationships.authors');

});
