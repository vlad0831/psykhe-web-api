<?php

namespace App\Models\Filter;

class Mood
{
    public static function List()
    {
        return [
            ['identifier' => 'baseline',  'name' => ''],
            ['identifier' => 'happy', 'name' => 'Happy'],
            ['identifier' => 'calm',  'name' => 'Calm'],
            ['identifier' => 'confident',  'name' => 'Confident'],
            ['identifier' => 'adventurous',  'name' => 'Adventurous'],
            ['identifier' => 'romantic',  'name' => 'Romantic'],
        ];
    }
}
