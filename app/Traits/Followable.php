<?php

namespace App\Traits;


use App\Follow;

/**
 * Class Followable
 *
 * @package App\Traits
 */
trait Followable
{

    /**
     * get the users who like the model
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function followers()
    {
        return $this->morphMany(Follow::class, 'followable');
    }
}