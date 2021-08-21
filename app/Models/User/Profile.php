<?php

namespace App\Models\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string name_first
 * @property string  dob
 */
class Profile extends Model
{
    protected $table = 'user_profiles';

    protected $fillable = [
        'user_id',
        'name_first',
        'name_last',
        'dob',
        'avatar',
        'avatar_uploaded_at',
        'options',
    ];

    protected $visible = [
        'name_first',
        'name_last',
        'dob',
        'avatar',
        'avatar_uploaded_at',
    ];

    protected $casts = [
        'options' => 'json',
        'avatar'  => 'boolean',
    ];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
