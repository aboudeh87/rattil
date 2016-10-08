<?php

namespace App;


use App\Traits\Likable;
use App\Traits\Commentable;
use App\Traits\Favoritable;
use App\Contracts\LikableContract;
use App\Contracts\CommentableContract;
use App\Contracts\FavoritableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Recitation
 *
 * @property integer                                                       $id
 * @property integer                                                       $user_id
 * @property integer                                                       $sura_id
 * @property integer                                                       $narration_id
 * @property integer                                                       $from_verse
 * @property integer                                                       $to_verse
 * @property string                                                        $slug
 * @property string                                                        $description
 * @property string                                                        $url
 * @property string                                                        $length
 * @property boolean                                                       $verified
 * @property boolean                                                       $disabled
 * @property \Carbon\Carbon                                                $created_at
 * @property \Carbon\Carbon                                                $updated_at
 * @property string                                                        $deleted_at
 * @property-read \App\User                                                $user
 * @property-read \App\Sura                                                $sura
 * @property-read \App\Narration                                           $narration
 * @property-read \App\Verse                                               $fromVerse
 * @property-read \App\Verse                                               $toVerse
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Like[]     $likes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Favorite[] $favorators
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Comment[] $comments
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
class Recitation extends Model implements LikableContract,
                                          FavoritableContract,
                                          CommentableContract
{

    use Likable, Favoritable, Commentable, SoftDeletes;

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
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

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
