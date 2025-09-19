<?php

namespace Opscale\Nova;

use Laravel\Nova\Resource;
use stdClass;

class SecondTestResource extends Resource
{
    public static $model = stdClass::class;

    public static $title = 'title';

    public static $search = [
        'id', 'title',
    ];

    public function fields($request)
    {
        return [];
    }
}
