<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

/**
 * App\CountryName
 *
 * @property integer $id
 * @property integer $country_id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Country $country
 * @method static \Illuminate\Database\Query\Builder|\App\CountryName whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CountryName whereCountryId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CountryName whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CountryName whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CountryName whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
