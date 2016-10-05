<?php

namespace App\Contracts;


/**
 * Interface FavoritableContract
 *
 * @package App\Contracts
 */
interface FavoritableContract
{

    /**
     * get the users who add the model to favorite
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function favorators();
}