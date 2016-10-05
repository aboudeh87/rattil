<?php

namespace App\Traits;


/**
 * Class Likable
 *
 * @package App\Traits
 */
trait Likable
{

    /**
     * get the users who like the model
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function likes()
    {
        return $this->morphMany(Li);
    }
}