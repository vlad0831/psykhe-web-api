<?php

namespace App\Models\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Referral extends Model
{
    protected $table = 'user_referrals';

    protected $fillable = [
        'user_id',
        'nonce',
        'to_name',
        'to_email',
    ];

    protected $visible = [
        'to_name',
        'to_email',
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->attributes['nonce'] = Str::random(64);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
