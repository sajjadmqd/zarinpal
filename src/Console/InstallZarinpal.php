<?php

namespace Sajjadmgd\Zarinpal\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallZarinpal extends Command
{
    protected $hidden = true;
    protected $signature = 'zarinpal:install';
    protected $description = 'Install the Zarinpal payment package';

    public function handle()
    {
        $this->info('Installing Zarinpal...');

        $this->info('Publishing configuration...');

        if (!$this->configExists('zarinpal.php')) {
            $this->publishConfiguration();
            $this->info('Published configuration');
        } else {
            if ($this->shouldOverwriteConfig()) {
                $this->info('Overwriting configuration file...');
                $this->publishConfiguration($force = true);
            } else {
                $this->info('Existing configuration was not overwritten');
            }
        }

        $this->info('Installed Zarinpal');
    }

    private function configExists($fileName)
    {
        return File::exists(config_path($fileName));
    }

    private function shouldOverwriteConfig()
    {
        return $this->confirm(
            'Config file already exists. Do you want to overwrite it?',
            false
        );
    }

    private function publishConfiguration($forcePublish = false)
    {
        $params = [
            '--provider' => "Sajjadmgd\Zarinpal\ZarinpalServiceProvider",
            '--tag' => "config"
        ];

        if ($forcePublish === true) {
            $params['--force'] = true;
        }

        $this->call('vendor:publish', $params);
    }
}
