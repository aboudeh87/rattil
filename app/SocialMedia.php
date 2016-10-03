<?php

namespace App;


use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\SocialMedia
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $social_id
 * @property string $provider
 * @property string $token
 * @property string $secret
 * @property string $nickname
 * @property string $email
 * @property string $avatar
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\SocialMedia whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\SocialMedia whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\SocialMedia whereSocialId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\SocialMedia whereProvider($value)
 * @method static \Illuminate\Database\Query\Builder|\App\SocialMedia whereToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\SocialMedia whereSecret($value)
 * @method static \Illuminate\Database\Query\Builder|\App\SocialMedia whereNickname($value)
 * @method static \Illuminate\Database\Query\Builder|\App\SocialMedia whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\SocialMedia whereAvatar($value)
 * @method static \Illuminate\Database\Query\Builder|\App\SocialMedia whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\SocialMedia whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SocialMedia extends Authenticatable
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'social_id',
        'provider',
        'token',
        'secret',
        'nickname',
        'email',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'token',
        'secret',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
