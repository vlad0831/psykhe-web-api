<?php

namespace App\Models\User\Savelist;

use App\Models\User;
use App\Models\User\Savelist;
use Illuminate\Database\Eloquent\Model;

/*
* The Type table exists for ease of querying, but contains only hard-coded
* static data
*/

class Type extends Model
{
    protected $table = 'user_savelist_types';

    protected $fillable = [
        'id',
        'identifier',
    ];

    protected $visible = [
        'id',
        'identifier',
    ];

    const LIKE    = 1;
    const DISLIKE = 2;
    const DEFAULT = 3;
    const CUSTOM  = 4;

    public function savelist(User $user)
    {
        return $this->hasOne(Savelist::class, 'user_savelist_type_id')->where('user_id', '=', $user->id);
    }
}
