<?php

namespace App\Models\Filter;

class Option
{
    public static function List()
    {
        return [
            [
                'identifier' => 'black-designers',
                'name'       => 'Black Designers',
            ],
            [
                'identifier' => 'sustainable',
                'name'       => 'Sustainable',
            ],
        ];
    }
}
