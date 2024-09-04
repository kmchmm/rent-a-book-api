<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

// class Book extends Model
// {
//     use HasFactory;

//     protected $table = 'books';

//     protected $fillable = [
//         'author',
//         'published',
//         'publisher',
//         'format'
//     ];
// }

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $table = 'books';

    // Define which attributes are mass assignable
    protected $fillable = [
        'author',
        'published',
        'publisher',
        'format',
        'title',              
        'image',              
        'random_number_13',   
        'random_number_10',  
    ];
}