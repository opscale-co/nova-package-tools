<?php

namespace Opscale\Nova;

use Laravel\Nova\Resource;
use stdClass;

class TestResource extends Resource
{
    public static $model = stdClass::class;

    public static $title = 'name';

    public static $search = [
        'id', 'name',
    ];

    public function fields($request)
    {
        return [];
    }
}
