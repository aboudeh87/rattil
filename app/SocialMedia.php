<?php

namespace App;


use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class SocialMedia
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
