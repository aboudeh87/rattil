<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Narration
 *
 * @property integer $id
 * @property integer $weight
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\NarrationName[] $names
 * @method static \Illuminate\Database\Query\Builder|\App\Narration whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Narration whereWeight($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Narration whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Narration whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Narration extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'narrations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['weight'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function names()
    {
        return $this->hasMany(NarrationName::class);
    }
}
