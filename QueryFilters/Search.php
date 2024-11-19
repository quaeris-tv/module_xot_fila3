<?php

/**
 * https://laravelproject.com/laravel-filtering-query-using-pipelines/.
 * https://jeffochoa.me/understanding-laravel-pipelines.
 * https://dev.to/abrardev99/pipeline-pattern-in-laravel-278p.
 */

declare(strict_types=1);

namespace Modules\Xot\QueryFilters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Webmozart\Assert\Assert;

/**
 * Undocumented class.
 */
class Search
{
    /**
     * Undocumented function.
     */
    public function handle(Builder $query, \Closure $next, array $args = []): \Closure
    {
        $search_fields = [];
        $model = $query->getModel();
        Assert::string($q = request('q', ''), '['.__LINE__.']['.class_basename($this).']');
        $search_fields = $model->getFillable();
        // $table = $model->getTable();
        if (mb_strlen($q) > 1) {
            $query = $query->where(
                static function ($subquery) use ($search_fields, $q): void {
                    foreach ($search_fields as $search_field) {
                        if (Str::contains($search_field, '.')) {
                            [$rel, $rel_field] = explode('.', (string) $search_field);
                            // dddx([$rel, $rel_field]);
                            $subquery = $subquery->orWhereHas(
                                $rel,
                                static function (Builder $query) use ($rel_field, $q): void {
                                    // dddx($subquery1->getConnection()->getDatabaseName());
                                    $query->where($rel_field, 'like', '%'.$q.'%');
                                    // dddx($subquery1);
                                }
                            );
                        // dddx($subquery);
                        } else {
                            $subquery = $subquery->orWhere($search_field, 'like', '%'.$q.'%');
                        }
                    }
                }
            );
        }

        // dddx(['q' => $q, 'sql' => $query->toSql()]);

        return $next($query);
    }
}
