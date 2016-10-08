<?php

namespace App\Contracts;


/**
 * Interface FollowableContract
 *
 * @package App\Contracts
 */
interface FollowableContract
{

    /**
     * get the users who follow the model
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function followers();
}