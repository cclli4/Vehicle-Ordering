<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * URI yang harus dikecualikan dari verifikasi CSRF.
     *
     * @var array
     */
    protected $except = [
        //
    ];
}
