<?php

$app = new Illuminate\Foundation\Application(dirname(__DIR__));

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    Illuminate\Foundation\Console\Kernel::class
);

return $app;
