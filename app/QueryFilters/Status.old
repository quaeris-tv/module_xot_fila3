<?php

/**
 * https://laravelproject.com/laravel-filtering-query-using-pipelines/.
 * https://jeffochoa.me/understanding-laravel-pipelines.
 * https://dev.to/abrardev99/pipeline-pattern-in-laravel-278p.
 * https://www.codecheef.org/article/laravel-pipeline-interpretation-with-example.
 */

declare(strict_types=1);

namespace Modules\Xot\QueryFilters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class Status
{
    /**
     * Undocumented function.
     */
    public function handle(Builder $query, \Closure $next): \Closure
    {
        // if (request()->has('status')) {
        //    $query->where('status', request('status'));
        // }
        $status = Arr::get(request()->all(), 'status');

        // Assicuriamoci che il valore di status sia valido prima di applicare il filtro
        if (! is_null($status)) {
            $query->where('status', '=', $status);
        }

        // $next($query);
        // Here you perform the task and return the updated $content
        // to the next pipe
        return $next($query);
    }
}

/*
use Illuminate\Pipeline\Pipeline;



class PostController
{
    public function index(Request $request)
    {
        $query = Post::query();

        $posts = app(Pipeline::class)
                ->send($query)
                ->through([
                    \App\QueryFilters\Status::class,
                    \App\QueryFilters\OrderBy::class,
                ])
                ->thenReturn()
                ->get();

        return view('post.index', compact('posts'));
    }
}

 */
/*
app(Pipeline::class)
    ->send($content)
    ->through($pipes)
    ->via(‘customMethodName’) // <---- This one :)
    ->then(function ($content) {
        return Post::create(['content' => $content]);
    });
    */
