<?php

namespace App\Transformers\V1;


use App\User;
use App\Transformers\Transformer;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FollowerTransformer
 *
 * @package App\Transformers\V1
 */
class FollowerTransformer extends Transformer
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
        $country = $model->properties()->where('key', 'country')->first();

        /** @var User $model */
        return [
            'id'                => (int) $model->id,
            'name'              => $model->name,
            'username'          => $model->username,
            'avatar'            => $model->avatar,
            'country'           => $country ? $country->value : null,
            'recitations_count' => isset($model->recitation_count) ?
                $model->recitation_count : $model->recitations()->whereDisabled(false)->count(),
        ];
    }

    /**
     * Get a key for cached model
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return string
     */
    protected function cacheKey(Model $model)
    {
        return 'follower_' . $model->getKey() . '_' . $model->updated_at;
    }
}