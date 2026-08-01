<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Prism\Prism\PrismManager;
use Prism\Prism\Providers\OpenAI\OpenAI as OpenAIProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->configurePrism();
    }

    /**
     * Register SumoPod as an OpenAI-compatible Prism provider.
     */
    protected function configurePrism(): void
    {
        $this->app->make(PrismManager::class)->extend('sumopod', function ($app, array $config): OpenAIProvider {
            return new OpenAIProvider(
                apiKey: $config['api_key'] ?? '',
                url: $config['url'] ?? 'https://ai.sumopod.com/v1',
                organization: null,
                project: null,
            );
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
