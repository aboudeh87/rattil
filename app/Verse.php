<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Verse
 *
 * @property integer $id
 * @property integer $sura_id
 * @property boolean $number
 * @property boolean $chapter
 * @property integer $page
 * @property string $characters
 * @property string $text
 * @property string $clean_text
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Sura $sura
 * @method static \Illuminate\Database\Query\Builder|\App\Verse whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Verse whereSuraId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Verse whereNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Verse whereChapter($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Verse wherePage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Verse whereCharacters($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Verse whereText($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Verse whereCleanText($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Verse whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Verse whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sura()
    {
        return $this->belongsTo(Sura::class);
    }
}
