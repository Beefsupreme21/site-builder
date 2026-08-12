<?php

namespace App\Providers;

use App\Models\Block;
use App\Models\Layout;
use App\Models\SitePage;
use Carbon\CarbonImmutable;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        RouteFacade::bind('block', function (string $value, Route $route): Block {
            $page = $route->parameter('page');
            $layout = $route->parameter('layout');

            if ($page !== null) {
                if (! $page instanceof SitePage) {
                    $page = SitePage::query()->whereKey($page)->firstOrFail();
                }

                return $page->blocks()->whereKey($value)->firstOrFail();
            }

            if ($layout !== null) {
                if (! $layout instanceof Layout) {
                    $layout = Layout::query()->whereKey($layout)->firstOrFail();
                }

                return $layout->blocks()->whereKey($value)->firstOrFail();
            }

            return Block::query()->whereKey($value)->firstOrFail();
        });

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
