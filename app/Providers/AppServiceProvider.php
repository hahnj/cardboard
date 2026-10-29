<?php

namespace App\Providers;

use App\Contracts\MtgoPayloadParser;
use App\Services\Mtgo\MtgoChallengeParser;
use App\Services\Mtgo\MtgoDecklistParser;
use App\Services\Mtgo\MtgoLeagueParser;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
        $this->app->tag([
            MtgoChallengeParser::class,
            MtgoLeagueParser::class,
        ], 'mtgo.payload.parsers');
        $this->app->when(MtgoDecklistParser::class)
            ->needs(MtgoPayloadParser::class)
            ->giveTagged('mtgo.payload.parsers');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
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
