<?php

declare(strict_types=1);

namespace App\Providers;

use AaronFrancis\Solo\Commands\EnhancedTailCommand;
use AaronFrancis\Solo\Facades\Solo;
use Illuminate\Support\ServiceProvider;

final class SoloServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Solo may not (should not!) exist in prod, so we have to
        // check here first to see if it's installed.
        if (class_exists(\AaronFrancis\Solo\Manager::class)) {
            $this->configure();
        }
    }

    public function configure(): void
    {
        Solo::useTheme('dark')
            // Commands that auto start.
            ->addCommands([
                'Vite' => 'npm run dev',
                EnhancedTailCommand::make('Logs', 'tail -f -n 100 '.storage_path('logs/laravel.log')),
            ])
            // Not auto-started
            ->addLazyCommands([
                'Queue' => 'php artisan queue:listen',
                'Flush' => 'sh entrypoints/flush.sh',
                'Migrate & Seed' => 'php artisan migrate:refresh --seed',
                'Code Fixer' => 'composer run code-fixer',
                'PHPStan' => 'composer run phpstan',
                'Pest' => 'composer run pest',
                'Route List' => 'php artisan route:list',
            ])
            // FQCNs of trusted classes that can add commands.
            ->allowCommandsAddedFrom([
                //
            ]);
    }

    public function boot(): void
    {
        //
    }
}
