<?php

namespace App\Transformers\V1;


use App\Verse;
use App\Transformers\Transformer;
use Illuminate\Database\Eloquent\Model;

/**
 * Class VerseTransformer
 *
 * @package App\Transformers\V1
 */
class VerseTransformer extends Transformer
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
        /** @var Verse $model */
        return [
            'id'         => (int) $model->id,
            'number'     => (int) $model->number,
            'text'       => $model->text,
            'cleanText'  => $model->clean_text,
            'characters' => $model->characters,
        ];
    }
}