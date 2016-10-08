<?php

namespace App\Traits;


use App\Comment;

/**
 * Class Commentable
 *
 * @package App\Traits
 */
trait Commentable
{

    /**
     * get the comments on the model
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}