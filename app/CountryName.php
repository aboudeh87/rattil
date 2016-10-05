<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class CountryName extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'country_name';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
