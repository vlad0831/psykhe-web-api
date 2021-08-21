<?php

namespace App\Http\Requests\Query;

use App\Http\Requests\Request;

class StoreQueryRequest extends Request
{
    /** @var array */
    public $price;

    /** @var array */
    public $limit;

    /** @var array */
    public $brands;

    /** @var array */
    public $categories;

    /** @var array */
    public $colors;

    /** @var string */
    public $mood;

    /** @var array */
    public $occasions;

    /** @var array */
    public $partners;

    /** @var array */
    public $products;

    /** @var string */
    public $recommendation;

    /** @var array */
    public $savelists;

    /** @var array */
    public $options;

    /**
     * @return bool
     */
    public function authorize()
    {
        $this->price          = $this->input('price', []);
        $this->limit          = $this->input('limit', [0, 90]);
        $this->brands         = $this->input('brands', []);
        $this->categories     = $this->input('categories', []);
        $this->colors         = $this->input('colors', []);
        $this->mood           = $this->input('mood', 'baseline');
        $this->occasions      = $this->input('occasions', []);
        $this->partners       = $this->input('partners', []);
        $this->products       = $this->input('products', []);
        $this->recommendation = $this->input('recommendation', '');
        $this->savelists      = $this->input('savelists', []);
        $this->options        = $this->input('options', []);

        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'price'          => ['nullable', 'array'],
            'limit'          => ['nullable', 'integer'],
            'brands'         => ['nullable', 'array'],
            'categories'     => ['nullable', 'array'],
            'colors'         => ['nullable', 'array'],
            'mood'           => ['nullable', 'string'],
            'occasions'      => ['nullable', 'array'],
            'partners'       => ['nullable', 'array'],
            'products'       => ['nullable', 'array'],
            'recommendation' => ['nullable', 'string'],
            'savelists'      => ['nullable', 'array'],
            'options'        => ['nullable', 'array'],
        ];
    }
}
