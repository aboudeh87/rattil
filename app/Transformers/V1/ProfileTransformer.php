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

        $properties = array_keys(config('profile.rules', []));

        foreach ($properties as $property)
        {

            if (in_array($property, config('profile.owner', [])) && $model->id !== auth('api')->id())
            {
                continue;
            }

            $propertyModel = $model->properties()->where('key', $property)->first();
            $data[$property] = $propertyModel ? $propertyModel->value : null;
        }

        $data['followers_count'] = $model->followers()->whereAccepted(true)->count();
        $data['following_count'] = $model->following()->whereAccepted(true)->count();
        $data['favorites_count'] = $model->favorites()->count();
        $data['recitations_count'] = $model->recitations()->where('disabled', false)->count();

        return $data;
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
        return 'profile_' . $model->getKey() . '_' . ($model->getKey() === auth('api')->id()) . '_' . $model->updated_at;
    }
}