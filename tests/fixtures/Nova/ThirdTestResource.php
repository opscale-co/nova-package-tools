<?php

namespace Opscale\Nova;

use Laravel\Nova\Resource;
use stdClass;

class ThirdTestResource extends Resource
{
    public static $model = stdClass::class;

    public static $title = 'description';

    public static $search = [
        'id', 'description',
    ];

    public function fields($request)
    {
        return [];
    }
}
