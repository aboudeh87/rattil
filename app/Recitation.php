<?php

namespace App;


use App\Traits\Likable;
use App\Contracts\LikableContract;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Recitation
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $sura_id
 * @property integer $narration_id
 * @property integer $from_verse
 * @property integer $to_verse
 * @property string $slug
 * @property string $description
 * @property string $url
 * @property string $length
 * @property boolean $verified
 * @property boolean $disabled
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property-read \App\User $user
 * @property-read \App\Sura $sura
 * @property-read \App\Narration $narration
 * @property-read \App\Verse $fromVerse
 * @property-read \App\Verse $toVerse
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Like[] $likes
 * @method static \Illuminate\Database\Query\Builder|\App\Recitation whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Recitation whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Recitation whereSuraId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Recitation whereNarrationId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Recitation whereFromVerse($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Recitation whereToVerse($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Recitation whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Recitation whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Recitation whereUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Recitation whereLength($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Recitation whereVerified($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Recitation whereDisabled($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Recitation whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Recitation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Recitation whereDeletedAt($value)
 * @mixin \Eloquent
 */
class Recitation extends Model implements LikableContract
{

    use Likable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'recitations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'sura_id',
        'narration_id',
        'from_verse',
        'to_verse',
        'slug',
        'description',
        'url',
        'length',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

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
    public function narration()
    {
        return $this->belongsTo(Narration::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fromVerse()
    {
        return $this->belongsTo(Verse::class, 'from_verse');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function toVerse()
    {
        return $this->belongsTo(Verse::class, 'to_verse');
    }
}
