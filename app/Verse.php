<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Verse extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'verses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['page', 'chapter', 'characters', 'number', 'text', 'clean_text'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sura()
    {
        return $this->belongsTo(Sura::class);
    }
}
