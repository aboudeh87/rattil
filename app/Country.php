<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Country extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'countries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['key'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function names()
    {
        return $this->hasMany(CountryName::class);
    }
}
