<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

/**
 * Class Language
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
