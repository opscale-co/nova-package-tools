<?php

use Illuminate\Support\Facades\Artisan;

function artisanSuccessfulRun(string ...$commands): void
{
    foreach ($commands as $command) {
        Artisan::call($command);

        if (Artisan::output() === '') {
            return;
        }

        if (str_contains(Artisan::output(), 'INFO')) {
            return;
        }

        throw new Exception(sprintf('Artisan command [%s] failed.', $command));
    }
}
