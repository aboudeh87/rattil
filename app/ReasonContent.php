<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

/**
 * App\ReasonContent
 *
 * @property integer $id
 * @property integer $reason_id
 * @property string $language_key
 * @property string $text
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Reason $reason
 * @method static \Illuminate\Database\Query\Builder|\App\ReasonContent whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ReasonContent whereReasonId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ReasonContent whereLanguageKey($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ReasonContent whereText($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ReasonContent whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ReasonContent whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ReasonContent extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reason_content';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['language_key', 'text'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reason()
    {
        return $this->belongsTo(Reason::class);
    }
}
