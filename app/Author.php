<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Author extends AbstractAPIModel
{
    protected $fillable = ['name'];

    public function books()
    {
        return $this->belongsToMany(Book::class);
    }

//    public function allowedAttributes(){
//        return collect($this->attributes)->filter(function($item, $key){
//            return !collect($this->hidden)->contains($key) && $key !== 'id';
//        })->merge([
//            'created_at' => $this->created_at,
//            'updated_at' => $this->updated_at,
//        ]);
//    }

    public function type()
    {
        return 'authors';
    }

}
