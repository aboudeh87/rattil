<?php

namespace App\Transformers\V1;


use App\Verse;
use App\Recitation;
use App\Transformers\Transformer;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RecitationTransformer
 *
 * @package App\Transformers\V1
 */
class RecitationTransformer extends Transformer
{

    /**
     * Show all information
     *
     * @var bool
     */
    protected $show = false;

    /**
     * Set shoe property
     *
     * @param $show
     *
     * @return $this
     */
    public function setShow($show)
    {
        $this->show = (bool) $show;

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
        /** @var Recitation $model */
        $data = [
            'id'             => (int) $model->id,
            'slug'           => $model->slug,
            'user'           => (new UserTransformer)->transform($model->user),
            'narration'      => (new NarrationTransformer)->transform($model->narration),
            'fromVerse'      => $model->fromVerse->number,
            'toVerse'        => $model->toVerse->number,
            'mentions'       => (new UserTransformer)->transform($model->mentions),
            'date'           => $model->created_at->timestamp,
            'commentsCount'  => (int) ($model->comments_count === null ?
                $model->comments()->count() : $model->comments_count),
            'favoritesCount' => (int) ($model->favorators_count === null ?
                $model->favorators()->count() : $model->favorators_count),
            'likesCount'     => (int) ($model->likes_count === null ?
                $model->likes()->count() : $model->likes_count),
        ];

        if ($this->show === true)
        {
            $data['sura'] = (new SuraTransformer)->transform($model->sura);
            $data['verses'] = (new VerseTransformer)->transform(
                $model->sura
                    ->verses()
                    ->whereBetween('number', [
                        $model->fromVerse->number,
                        $model->toVerse->number,
                    ])
                    ->orderBy('number', 'asc')
                    ->get()
            );
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
        return get_class($model) . '_' . $model->getKey() . '_' . \App::getLocale() . '_' . $this->show . '_' . $model->updated_at;
    }
}