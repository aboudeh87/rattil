<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

/**
 * App\UserProperty
 *
 * @property integer        $id
 * @property integer        $user_id
 * @property string         $key
 * @property string         $value
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\UserProperty whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserProperty whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserProperty whereKey($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserProperty whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserProperty whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserProperty whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UserProperty extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_properties';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['value', 'key'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Find a property by key
     *
     * @param $key
     *
     * @return null|static
     */
    public function findByKey($key)
    {
        return self::whereKey($key)->first();
    }
}
