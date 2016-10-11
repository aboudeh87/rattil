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
     * Return verses of Sura if set true
     *
     * @var bool
     */
    protected $verses = false;

    /**
     * @param $verses
     *
     * @return $this
     */
    public function setVerses($verses)
    {
        $this->verses = (bool) $verses;

        return $this;
    }

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

        $data = [
            'id'                 => (int) $model->id,
            'name'               => $content ? $content->name : null,
            'revealed'           => trans("labels.{$model->revealed}"),
            'chronologicalOrder' => (int) $model->chronological_order,
        ];

        if ($this->verses === true)
        {
            $transformer = new VerseTransformer();
            $data['verses'] = $transformer->transform($model->verses()->orderBy('number', 'asc')->get());
        }

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
        return get_class($model) . '_' . $model->getKey() . '_' . $this->verses . '_' . $model->updated_at;
    }
}