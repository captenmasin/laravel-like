<?php

namespace Tests;

use Illuminate\Database\Eloquent\Model;
use Captenmasin\LaravelLike\Traits\Likeable;
use Captenmasin\LaravelLike\Traits\Liker;

class User extends Model
{
    use Likeable;
    use Liker;

    protected $fillable = ['name'];
}
