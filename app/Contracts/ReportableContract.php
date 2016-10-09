<?php

namespace App\Contracts;


/**
 * Interface ReportableContract
 *
 * @package App\Contracts
 */
interface ReportableContract
{

    /**
     * get the reports on the model
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function reports();
}