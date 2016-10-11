<?php

namespace App\Transformers\V1;


use App\Narration;
use App\NarrationName;
use App\Transformers\Transformer;
use Illuminate\Database\Eloquent\Model;

/**
 * Class NarrationTransformer
 *
 * @package App\Transformers\V1
 */
class NarrationTransformer extends Transformer
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
        /** @var Narration $model */
        $content = NarrationName::whereNarrationId($model->id)
            ->whereLanguageKey(\App::getLocale())
            ->first() ?: NarrationName::whereNarrationId($model->id)->first();

        return [
            'id'     => (int) $model->id,
            'name'   => $content ? $content->name : null,
            'weight' => $model->weight,
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
        return get_class($model) . '_' . $model->getKey() . '_' . \App::getLocale() . '_' . $model->updated_at;
    }
}