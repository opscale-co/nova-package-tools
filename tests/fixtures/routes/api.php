<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('ping', static fn (): string => 'api-pong')->name('opscale.test.api.ping');
