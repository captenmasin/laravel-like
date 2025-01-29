<?php

namespace Tests;

use Illuminate\Database\Eloquent\Model;
use Captenmasin\LaravelLike\Traits\Likeable;

class Book extends Model
{
    use Likeable;

    protected $fillable = ['title'];
}
