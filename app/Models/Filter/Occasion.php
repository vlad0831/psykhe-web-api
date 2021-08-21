<?php

namespace App\Models\Filter;

class Occasion
{
    public static function List()
    {
        return [
            ['name' => 'Everyday', 'identifier' => 'everyday'],
            ['name' => 'Chill', 'identifier' => 'chill'],
            ['name' => 'Workout', 'identifier' => 'workout'],
            ['name' => 'Evening', 'identifier' => 'evening'],
            ['name' => 'Holiday', 'identifier' => 'holiday'],
            ['name' => 'Business', 'identifier' => 'business'],
        ];
    }
}
