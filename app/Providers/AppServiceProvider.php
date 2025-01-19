<?php

declare(strict_types=1);

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->configureCommands();
        $this->configureModels();
        $this->configureDates();
        $this->configurePasswordValidation();
        $this->configureUrls();
        $this->configureVite();
    }

    /**
     * @return void
     */
    private function configureCommands(): void
    {
        DB::prohibitDestructiveCommands($this->app->isProduction());
    }

    /**
     * @return void
     */
    private function configureModels(): void
    {
        Model::shouldBeStrict();
    }

    /**
     * @return void
     */
    private function configureDates(): void
    {
        Date::use(CarbonImmutable::class);
    }

    /**
     * @return void
     */
    private function configurePasswordValidation(): void
    {
        Password::defaults(static fn () => Password::min(12)->uncompromised());
    }

    /**
     * @return void
     */
    private function configureUrls(): void
    {
        URL::forceHttps($this->app->isProduction());
    }

    /**
     * @return void
     */
    private function configureVite(): void
    {
        // Vite::prefetch(concurrency: 3);
        Vite::useAggressivePrefetching();
    }
}
