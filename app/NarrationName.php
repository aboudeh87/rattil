<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

/**
 * App\NarrationName
 *
 * @property integer $id
 * @property integer $narration_id
 * @property string $language_key
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Narration $names
 * @method static \Illuminate\Database\Query\Builder|\App\NarrationName whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\NarrationName whereNarrationId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\NarrationName whereLanguageKey($value)
 * @method static \Illuminate\Database\Query\Builder|\App\NarrationName whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\NarrationName whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\NarrationName whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class NarrationName extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'narration_name';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'language_key'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function names()
    {
        return $this->belongsTo(Narration::class);
    }
}
