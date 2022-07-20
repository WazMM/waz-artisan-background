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
        $this->publishes([
            __DIR__ . '/../config/waz-artisan-background.php' => config_path('waz-artisan-background.php'),
        ]);

    }
}
