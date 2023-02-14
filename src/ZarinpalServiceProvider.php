<?php

namespace Sajjadmgd\Zarinpal;

use Illuminate\Support\ServiceProvider;
use Sajjadmgd\Zarinpal\Facades\Payment;
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
    $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

    if ($this->app->runningInConsole()) {
      $this->commands([
        InstallZarinpal::class,
      ]);

      $this->publishes([
        __DIR__ . '/../config/config.php' => config_path('zarinpal.php'),
      ], 'config');

      $this->publishes([
        __DIR__ . '/../database/migrations/' => database_path('migrations')
      ], 'migration');
    }

    if (file_exists($file = app_path('src/helpers.php'))) {
      require $file;
    }
  }
}
