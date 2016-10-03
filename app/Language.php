<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Language
 *
 * @mixin \Eloquent
 * @property integer $id
 * @property string $key
 * @property string $name
 * @property string $direction
 * @property boolean $published
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Language whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Language whereKey($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Language whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Language whereDirection($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Language wherePublished($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Language whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Language whereUpdatedAt($value)
 */
class Language extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'languages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['key', 'name', 'direction', 'published'];

    /**
     * Find language model by language key
     *
     * @param $key
     *
     * @return Language|null
     */
    public function findByKey($key)
    {
        return static::whereKey($key)->first();
    }
}
