<?php

namespace Sajjadmgd\Zarinpal;

use Sajjadmgd\Zarinpal\Payment;
use Illuminate\Support\ServiceProvider;
use Sajjadmgd\Zarinpal\Console\InstallZarinpal;

class ZarinpalServiceProvider extends ServiceProvider
{
  public function register()
  {
    $this->app->bind('payment', function ($app) {
      return new Payment();
    });
    $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'zarinpal');
  }

  public function boot()
  {
    $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    
    if ($this->app->runningInConsole()) {
      $this->commands([
        InstallZarinpal::class,
      ]);
      $this->publishes([
        __DIR__ . '/../config/config.php' => config_path('zarinpal.php'),
      ], 'config');
    }

    if (file_exists($file = app_path('src/helpers.php'))) {
      require $file;
    }
  }
}
