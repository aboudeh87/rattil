<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Country
 *
 * @property integer $id
 * @property string $key
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CountryName[] $names
 * @method static \Illuminate\Database\Query\Builder|\App\Country whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Country whereKey($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Country whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Country whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
