<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Favorite
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $favoritable_type
 * @property integer $favoritable_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $user
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $model
 * @method static \Illuminate\Database\Query\Builder|\App\Favorite whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Favorite whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Favorite whereFavoritableType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Favorite whereFavoritableId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Favorite whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Favorite whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Favorite extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'favorites';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['favoritable_type', 'favoritable_id', 'user_id'];

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
        return $this->morphTo('favoritable');
    }
}
