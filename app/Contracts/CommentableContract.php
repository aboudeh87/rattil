<?php

namespace App\Contracts;


/**
 * Interface CommentableContract
 *
 * @package App\Contracts
 */
interface CommentableContract
{

    /**
     * get the users who comments the model
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments();
}