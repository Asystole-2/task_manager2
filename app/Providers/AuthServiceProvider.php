<?php

namespace App\Providers;

class AuthServiceProvider
{
    protected $policies = [
        Board::class => BoardPolicy::class,
    ];
}
