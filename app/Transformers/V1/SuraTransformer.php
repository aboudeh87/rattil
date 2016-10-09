<?php

namespace App\Transformers\V1;


use App\Sura;
use App\Verse;
use App\SuraContent;
use App\Transformers\Transformer;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SuraTransformer
 *
 * @package App\Transformers\V1
 */
class SuraTransformer extends Transformer
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
        /**
         * @var Sura        $model
         * @var SuraContent $content
         */
        $content = SuraContent::whereSuraId($model->id)
            ->whereLanguageKey(\App::getLocale())
            ->first() ?: SuraContent::whereSuraId($model->id)->first();

        $verses = $model->verses()->orderBy('number', 'asc')->get()->map(function (Verse $verse)
        {
            return [
                'id'         => (int) $verse->id,
                'number'     => (int) $verse->number,
                'text'       => $verse->text,
                'cleanText'  => $verse->clean_text,
                'characters' => $verse->characters,
            ];
        });

        return [
            'id'                 => (int) $model->id,
            'name'               => $content ? $content->name : null,
            'revealed'           => trans("labels.{$model->revealed}"),
            'chronologicalOrder' => (int) $model->chronological_order,
            'verses'             => $verses,
        ];
    }
}