<?php

namespace App\Contracts;


/**
 * Interface LikableContract
 *
 * @package App\Contracts
 */
interface LikableContract
{

    /**
     * get the users who like the model
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function likes();
}