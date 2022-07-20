<?php

namespace Waz\WazArtisanBackground;

use Waz\WazArtisanBackground\Commands\ArtisanRunInBackground;
use Illuminate\Support\ServiceProvider;

class WazArtisanBackgroundProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ArtisanRunInBackground::class,
            ]);
        }
    }
}
