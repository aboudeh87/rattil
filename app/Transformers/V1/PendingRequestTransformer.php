<?php

namespace App\Transformers\V1;


use App\Follow;
use App\Transformers\Transformer;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PendingRequestTransformer
 *
 * @package App\Transformers\V1
 */
class PendingRequestTransformer extends Transformer
{

    /**
     * Transform single item
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return array
     */
    protected function transformItem(Model $model)
    {
        /** @var Follow $model */
        return [
            'id'        => (int) $model->id,
            'user_id'   => (int) $model->user->id,
            'name'      => $model->user->name,
            'username'  => $model->user->username,
            'avatar'    => $model->user->avatar,
            'certified' => $model->user->certified,
            'date'      => $model->created_at->timestamp,
        ];
    }
}