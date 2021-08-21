<?php

namespace App\Models\User\Savelist;

use App\Models\User\Savelist;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'user_savelists_products';

    protected $fillable = [
        'user_savelist_id',
        'psykhe_product_identifier',
    ];

    protected $visible = [
        'user_savelist_id',
        'psykhe_product_identifier',
    ];

    const LIKE    = 1;
    const DISLIKE = 2;
    const CUSTOM  = 3;

    public function savelist()
    {
        return $this->belongsTo(Savelist::class, 'user_savelist_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Product', 'identifier', 'product_identifier');
    }
}
