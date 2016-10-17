<?php

namespace App\Transformers\V1;


use App\User;
use App\Transformers\Transformer;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProfileTransformer
 *
 * @package App\Transformers\V1
 */
class ProfileTransformer extends Transformer
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
        /** @var User $model */
        $data = [
            'id'       => (int) $model->id,
            'name'     => $model->name,
            'username' => $model->username,
            'avatar'   => $model->avatar,
        ];

        $properties = array_keys(config('profile'));

        foreach ($properties as $property)
        {
            $propertyModel = $model->properties()->where('key', $property)->first();

            $data[$property] = $propertyModel ? $propertyModel->value : null;
        }

        return $data;
    }
}