<?php

namespace Tests;

use Illuminate\Database\Eloquent\Model;
use Captenmasin\LaravelLike\Traits\Likeable;

class Post extends Model
{
    use Likeable;

    protected $fillable = ['title'];
}
