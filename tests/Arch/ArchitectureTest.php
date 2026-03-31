<?php

arch('models have factories')
    ->expect('App\Models')
    ->toHaveMethod('factory');

arch('controllers are invokable')
    ->expect('App\Http\Controllers')
    ->not->toBeAbstract()
    ->ignoring('App\Http\Controllers\Controller');
