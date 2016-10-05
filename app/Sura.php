<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Sura
 *
 * @property integer                                                          $id
 * @property string                                                           $revealed
 * @property boolean                                                          $chronological_order
 * @property \Carbon\Carbon                                                   $created_at
 * @property \Carbon\Carbon                                                   $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SuraContent[] $content
 * @method static \Illuminate\Database\Query\Builder|\App\Sura whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Sura whereRevealed($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Sura whereChronologicalOrder($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Sura whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Sura whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Verse[] $verses
 */
class Sura extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'suwar';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['revealed', 'chronological_order'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function content()
    {
        return $this->hasMany(SuraContent::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function verses()
    {
        return $this->hasMany(Verse::class);
    }
}
