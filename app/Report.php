<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Report
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $reason_id
 * @property string $reportable_type
 * @property integer $reportable_id
 * @property string $message
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $user
 * @property-read \App\Reason $reason
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $reported
 * @method static \Illuminate\Database\Query\Builder|\App\Report whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Report whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Report whereReasonId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Report whereReportableType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Report whereReportableId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Report whereMessage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Report whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Report whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Report extends Model
{

    const AVAILABLE_TYPES = [
        'comments'    => Comment::class,
        'recitations' => Recitation::class,
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reports';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'reason_id', 'reportable_type', 'reportable_id', 'message'];

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
    public function reason()
    {
        return $this->belongsTo(Reason::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function reported()
    {
        return $this->morphTo('reportable');
    }
}
