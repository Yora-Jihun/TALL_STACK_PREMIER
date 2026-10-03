<?php

// Built-in rule sets
arch()->preset()->php();
arch()->preset()->security();

// php preset misses dd, so forbid it explicitly
arch('debugging functions are not used')
    ->expect(['dd', 'dump', 'ray'])
    ->not->toBeUsed();

arch('actions are final and have a handle method')
    ->expect('App\Actions')
    ->toBeFinal()
    ->toHaveMethod('handle');

arch('enums are enums')
    ->expect('App\Enums')
    ->toBeEnums();
