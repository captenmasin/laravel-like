# Laravel Like

👍 User-like features for Laravel Application.

[![CI](https://github.com/captenmasin/laravel-like/actions/workflows/ci.yml/badge.svg)](https://github.com/captenmasin/laravel-like/actions/workflows/ci.yml)

[![Sponsor me](https://github.com/captenmasin/captenmasin/blob/master/sponsor-me-button-s.svg?raw=true)](https://github.com/sponsors/captenmasin)

## Installing

```shell
composer require captenmasin/laravel-like -vvv
```

### Configuration and Migrations

```shell
php artisan vendor:publish --provider="Captenmasin\LaravelLike\LikeServiceProvider"
```

## Usage

### Traits

#### `Captenmasin\LaravelLike\Traits\Liker`

```php

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Captenmasin\LaravelLike\Traits\Liker;

class User extends Authenticatable
{
    use Liker;

    <...>
}
```

#### `Captenmasin\LaravelLike\Traits\Likeable`

```php
use Illuminate\Database\Eloquent\Model;
use Captenmasin\LaravelLike\Traits\Likeable;

class Post extends Model
{
    use Likeable;

    <...>
}
```

### API

```php
$user = User::find(1);
$post = Post::find(2);

$user->like($post);
$user->unlike($post);
$user->toggleLike($post);

$user->hasLiked($post);
$post->isLikedBy($user);
```

Get user likes with pagination:

```php
$likes = $user->likes()->with('likeable')->paginate(20);

foreach ($likes as $like) {
    $like->likeable; // App\Post instance
}
```

Get object likers:

```php
foreach($post->likers as $user) {
    // echo $user->name;
}
```

with pagination:

```php
$likers = $post->likers()->paginate(20);

foreach($likers as $user) {
    // echo $user->name;
}
```

### Aggregations

```php
// all
$user->likes()->count();

// short way
$user->totalLikes;

// with type
$user->likes()->withType(Post::class)->count();

// likers count
$post->likers()->count();

// short way
$post->totalLikers
```

List with `*_count` attribute:

```php
// likes_count
$users = User::withCount('likes')->get();

foreach($users as $user) {
    // $user->likes_count;
}

// likers_count
$posts = User::withCount('likers')->get();

foreach($posts as $post) {
    // $post->likes_count;
}
```

### N+1 issue

To avoid the N+1 issue, you can use eager loading to reduce this operation to just 2 queries. When querying, you may specify which relationships should be eager loaded using the `with` method:

```php
// Liker
$users = App\User::with('likes')->get();

foreach($users as $user) {
    $user->hasLiked($post);
}

// Likeable
$posts = App\Post::with('likes')->get();
// or
$posts = App\Post::with('likers')->get();

foreach($posts as $post) {
    $post->isLikedBy($user);
}
```

Of course we have a better solution, which can be found in the following section：

### Attach user like status to likeable collection

You can use `Liker::attachLikeStatus($likeables)` to attach the user like status, it will attach `has_liked` attribute to each model of `$likeables`:

#### For model
```php
$post = Post::find(1);

$post = $user->attachLikeStatus($post);

// result
[
    "id" => 1
    "title" => "Add socialite login support."
    "created_at" => "2021-05-20T03:26:16.000000Z"
    "updated_at" => "2021-05-20T03:26:16.000000Z"
    "has_liked" => true
 ],
```

#### For `Collection | Paginator | LengthAwarePaginator | array`:

```php
$posts = Post::oldest('id')->get();

$posts = $user->attachLikeStatus($posts);

$posts = $posts->toArray();

// result
[
  [
    "id" => 1
    "title" => "Post title1"
    "created_at" => "2021-05-20T03:26:16.000000Z"
    "updated_at" => "2021-05-20T03:26:16.000000Z"
    "has_liked" => true
  ],
  [
    "id" => 2
    "title" => "Post title2"
    "created_at" => "2021-05-20T03:26:16.000000Z"
    "updated_at" => "2021-05-20T03:26:16.000000Z"
    "has_liked" => fasle
  ],
  [
    "id" => 3
    "title" => "Post title3"
    "created_at" => "2021-05-20T03:26:16.000000Z"
    "updated_at" => "2021-05-20T03:26:16.000000Z"
    "has_liked" => true
  ],
]
```

#### For pagination

```php
$posts = Post::paginate(20);

$user->attachLikeStatus($posts);
```

### Events

| **Event**                             | **Description**                             |
| ------------------------------------- | ------------------------------------------- |
| `Captenmasin\LaravelLike\Events\Liked`   | Triggered when the relationship is created. |
| `Captenmasin\LaravelLike\Events\Unliked` | Triggered when the relationship is deleted. |

## Related packages

-   Follow: [captenmasin/laravel-follow](https://github.com/captenmasin/laravel-follow)
-   Like: [captenmasin/laravel-like](https://github.com/captenmasin/laravel-like)
-   Favorite: [captenmasin/laravel-favorite](https://github.com/captenmasin/laravel-favorite)
-   Subscribe: [captenmasin/laravel-subscribe](https://github.com/captenmasin/laravel-subscribe)
-   Vote: [captenmasin/laravel-vote](https://github.com/captenmasin/laravel-vote)
-   Bookmark: captenmasin/laravel-bookmark (working in progress)

## :heart: Sponsor me

[![Sponsor me](https://github.com/captenmasin/captenmasin/blob/master/sponsor-me.svg?raw=true)](https://github.com/sponsors/captenmasin)

如果你喜欢我的项目并想支持它，[点击这里 :heart:](https://github.com/sponsors/captenmasin)

## Project supported by JetBrains

Many thanks to Jetbrains for kindly providing a license for me to work on this and other open-source projects.

[![](https://resources.jetbrains.com/storage/products/company/brand/logos/jb_beam.svg)](https://www.jetbrains.com/?from=https://github.com/captenmasin)

## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/captenmasin/laravel-likes/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/captenmasin/laravel-likes/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## PHP 扩展包开发

> 想知道如何从零开始构建 PHP 扩展包？
>
> 请关注我的实战课程，我会在此课程中分享一些扩展开发经验 —— [《PHP 扩展包实战教程 - 从入门到发布》](https://learnku.com/courses/creating-package)

## License

MIT
