<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Listener
 *
 * @property integer $id
 * @property integer $recitation_id
 * @property integer $user_id
 * @property integer $count
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $user
 * @property-read \App\Recitation $recitation
 * @method static \Illuminate\Database\Query\Builder|\App\Listener whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Listener whereRecitationId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Listener whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Listener whereCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Listener whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Listener whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Listener extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'listeners';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['count', 'user_id'];

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
    public function recitation()
    {
        return $this->belongsTo(Recitation::class);
    }
}
