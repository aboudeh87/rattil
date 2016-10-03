<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

/**
 * App\SuraContent
 *
 * @property integer $id
 * @property integer $sura_id
 * @property string $language_key
 * @property string $name
 * @property string $definition
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Sura $sura
 * @property-read \App\Language $language
 * @method static \Illuminate\Database\Query\Builder|\App\SuraContent whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\SuraContent whereSuraId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\SuraContent whereLanguageKey($value)
 * @method static \Illuminate\Database\Query\Builder|\App\SuraContent whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\SuraContent whereDefinition($value)
 * @method static \Illuminate\Database\Query\Builder|\App\SuraContent whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\SuraContent whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SuraContent extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'suwar_content';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['language_key', 'name', 'definition'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sura()
    {
        return $this->belongsTo(Sura::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_key', 'key');
    }
}
