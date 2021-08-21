<?php

namespace App\Models\User;

use App\Models\User\Savelist\Product;
use Illuminate\Database\Eloquent\Model;

class Savelist extends Model
{
    protected $table = 'user_savelists';

    protected $fillable = [
        'user_savelist_type_id',
        'user_id',
        'slug',
        'name',
    ];

    protected $visible = [
        'slug',
        'name',
    ];

    public function user_savelist_products()
    {
        return $this->hasMany(Product::class, 'user_savelist_id');
    }
}
