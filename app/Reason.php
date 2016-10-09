<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Reason
 *
 * @property integer $id
 * @property string $model
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ReasonContent[] $content
 * @method static \Illuminate\Database\Query\Builder|\App\Reason whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Reason whereModel($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Reason whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Reason whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Reason extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reasons';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['model'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function content()
    {
        return $this->hasMany(ReasonContent::class);
    }
}
