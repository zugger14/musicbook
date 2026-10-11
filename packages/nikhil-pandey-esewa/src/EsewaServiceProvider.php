<?php

namespace NikhilPandey\Esewa;

use Illuminate\Support\ServiceProvider;

class EsewaServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('esewa', function () {
            return new Esewa();
        });
    }
}
