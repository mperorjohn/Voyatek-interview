<?php
namespace App\Http;

use App\Http\Middleware\TokenMiddleware;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middlewareGroups = [
        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class
        ]
    ];

    protected $routeMiddleware = [
        'verify_token' => TokenMiddleware::class
    ];
}