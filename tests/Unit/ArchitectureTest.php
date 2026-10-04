<?php

// Built-in rule sets
arch()->preset()->php();
arch()->preset()->security();

// php preset misses dd, so forbid it explicitly
arch('debugging functions are not used')
    ->expect(['dd', 'dump', 'ray'])
    ->not->toBeUsed();

// Fortify's generated actions follow Fortify's contracts, not ours
arch('actions are final')
    ->expect('App\Actions')
    ->toBeFinal()
    ->ignoring('App\Actions\Fortify');

arch('actions have a handle method')
    ->expect('App\Actions')
    ->toHaveMethod('handle')
    ->ignoring('App\Actions\Fortify');

arch('enums are enums')
    ->expect('App\Enums')
    ->toBeEnums();
