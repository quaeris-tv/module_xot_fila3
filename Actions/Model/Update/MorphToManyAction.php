<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Model\Update;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Modules\Xot\Datas\RelationData as RelationDTO;
use Spatie\QueueableAction\QueueableAction;

class MorphToManyAction
{
    use QueueableAction;

    public Collection $res;

    /**
     * Undocumented function.
     */
    public function execute(Model $row, RelationDTO $relationDTO): void
    {
        $data = $relationDTO->data;
        $name = $relationDTO->name;
        $model = $row;

        if (\in_array('to', array_keys($data), false) || \in_array('from', array_keys($data), false)) {
            if (! isset($data['to'])) {
                $data['to'] = [];
            }

            $data = $data['to'];
        }

        if (! \is_array($data)) {
            throw new \Exception('['.__LINE__.']['.class_basename($this).']');
        }

        if (! Arr::isAssoc($data)) {
            // dddx(['model' => $model, 'name' => $name, 'data' => $data]);
            $model->{$name}()->sync($data);
        }

        foreach ($data as $k => $v) {
            if (\is_array($v)) {
                if (! isset($v['pivot'])) {
                    $v['pivot'] = [];
                }

                /*
                echo '<hr/><pre>'.print_r($v['pivot'],1).'</pre><hr/>';
                $res = $model->$name()
                        ->where('related_id',$k)
                        ->where('user_id',$v['pivot']['user_id'])
                        ->update($v['pivot']);
                */

                $res = $model->$name()
                    ->syncWithoutDetaching([$k => $v['pivot']]);
            }

            // $res = $model->$name()
            //   ->syncWithoutDetaching([$v]);

            // ->where('user_id',1)
            // ->syncWithoutDetaching([$k => $v['pivot']])

            // ->updateOrCreate(['related_id'=>$k,'user_id'=>1],$v['pivot']);
            // $model->$name()->touch();
        }
    }
}
