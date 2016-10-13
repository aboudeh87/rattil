<?php

namespace App\Transformers;


use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Transformer
 *
 * @package App\Transformers
 */
abstract class Transformer
{

    /**
     * Transform a collection of models or a single model
     *
     * @param array|Collection|Model $models
     *
     * @return Collection|array
     */
    public function transform($models)
    {
        if ($models instanceof Collection)
        {
            return $models->map(function (Model $model)
            {
                return $this->cacheItem($model);
            });
        }

        if (is_array($models))
        {
            return new Collection(array_map(function (Model $model)
            {
                return $this->cacheItem($model);
            }, $models));
        }

        return $this->cacheItem($models);
    }

    /**
     * return transformed item and cache it
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return mixed
     */
    protected function cacheItem(Model $model)
    {
        return \Cache::rememberForever($this->cacheKey($model), function () use ($model)
        {
            return $this->transformItem($model);
        });
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
        return get_class($model) . '_' . $model->getKey() . '_' . $model->updated_at;
    }

    /**
     * Transform single item
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return array
     */
    abstract protected function transformItem(Model $model);
}