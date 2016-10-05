<?php

namespace App\Traits;


use App\Favorite;

/**
 * Class Favoritable
 *
 * @package App\Traits
 */
trait Favoritable
{

    /**
     * get the users who add the model to favorite
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function favorators()
    {
        return $this->morphMany(Favorite::class, 'likable');
    }
}