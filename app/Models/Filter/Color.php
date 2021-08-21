<?php

namespace App\Models\Filter;

class Color
{
    public static function List()
    {
        $colors = [
            'black',
            'grey',
            'white',
            'silver',
            'gold',
            'blue',
            'pink',
            'green',
            'purple',
            'print',
            'yellow',
            'orange',
            'red',
            'neutrals',
            'brown',
        ];

        $expanded = [];
        foreach ($colors as $identifier) {
            $expanded[] = [
                'identifier' => $identifier,
                'name'       => ucwords($identifier),
            ];
        }

        return $expanded;
    }
}
