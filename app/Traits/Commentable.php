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
     * get the users who comments the model
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}