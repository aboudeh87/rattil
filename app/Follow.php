<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Follow
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $followable_type
 * @property integer $followable_id
 * @property boolean $accepted
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $user
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $model
 * @method static \Illuminate\Database\Query\Builder|\App\Follow whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Follow whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Follow whereFollowableType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Follow whereFollowableId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Follow whereAccepted($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Follow whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Follow whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Follow extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'followers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['followable_type', 'followable_id', 'user_id'];

    /**
     * The relationships that should be touched on save.
     *
     * @var array
     */
    protected $touches = ['model', 'user'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function model()
    {
        return $this->morphTo('followable');
    }
}
