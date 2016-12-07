<?php

namespace App\Transformers\V1;


use App\Comment;
use App\Transformers\Transformer;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CommentTransformer
 *
 * @package App\Transformers\V1
 */
class CommentTransformer extends Transformer
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
        /** @var Comment $model */

        return [
            'id'       => (int) $model->id,
            'text'     => $model->text,
            'url'      => $model->url,
            'verified' => (bool) $model->verified,
            'user'     => (new UserTransformer)->transform($model->user),
            'date'     => $model->created_at->timestamp,
        ];
    }
}