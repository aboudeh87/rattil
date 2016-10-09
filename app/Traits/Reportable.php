<?php

namespace App\Traits;


use App\Comment;
use App\Report;

/**
 * Class Reportable
 *
 * @package App\Traits
 */
trait Reportable
{

    /**
     * get the reports on the model
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }
}