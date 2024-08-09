<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Modules\Xot\Datas\RelationData;
use Spatie\LaravelData\DataCollection;
use Spatie\QueueableAction\QueueableAction;

class FilterRelationsAction
{
    use QueueableAction;

    /**
     * @return DataCollection<(int|string), RelationData>
     */
    public function execute(Model $model, array $data): DataCollection
    {
        $methods = get_class_methods($model);
        $res = collect($data)
            ->filter(
                static function ($value, $item) use ($methods): bool {
                    $method = Str::camel($item);

                    return \in_array($method, $methods, false);
                }
            )
            ->filter(
                static function ($value, $item) use ($model): bool {
                    $method = Str::camel($item);
                    $rows = $model->$method();

                    return $rows instanceof Relation;
                }
            )->map(
                static function ($value, $item) use ($model): array {
                    $method = Str::camel($item);
                    $rows = $model->$method();
                    // $related = null;
                    // if (method_exists($rows, 'getRelated')) {
                    // Cannot call method getRelated() on class-string|object
                    $related = $rows->getRelated();

                    // }
                    // if(!is_array($value)){
                    //    dddx(['item'=>$item,'value'=>$value]);
                    // }
                    return [
                        'relationship_type' => class_basename($rows),
                        'related' => $related,
                        'name' => $item,
                        'rows' => $rows,
                        'data' => Arr::wrap($value),
                    ];
                }
            )->all();

        /**
         * @var DataCollection<int|string, RelationData>
         */
        $res = RelationData::collect($res, DataCollection::class);

        return $res;
    }
}
