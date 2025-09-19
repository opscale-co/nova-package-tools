<?php

use Illuminate\Support\Facades\File;

expect()->extend('toBeFileOrDirectory', function () {
    return $this->toMatch(function ($path): bool {
        return is_file($path) || is_dir($path);
    });
});

expect()->extend('toPublishMigrations', function (): void {
    $published = 0;

    foreach ($this->value as $file) {
        $fileName = basename($file);

        // See: https://github.com/laravel/framework/blob/10.x/src/Illuminate/Database/MigrationServiceProvider.php#L85-L89
        if (preg_match('/\d{4}_\d{2}_\d{2}_\d{6}/', $fileName)) {
            $fileName = preg_replace('/\d{4}_\d{2}_\d{2}_\d{6}/', '2020_01_01_000000', $fileName);
        }

        $publishedFile = database_path('migrations/'.$fileName);
        if (File::exists($publishedFile)) {
            $published++;
        }
    }

    $this->toEqual($published);
});
