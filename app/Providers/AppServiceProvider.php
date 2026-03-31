<?php

namespace App\Providers;

use App\Models\Embed;
use App\Models\Link;
use App\Models\SocialLink;
use App\Policies\EmbedPolicy;
use App\Policies\LinkPolicy;
use App\Policies\SocialLinkPolicy;
use Carbon\CarbonImmutable;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
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
        $this->configureDefaults();
        $this->configurePolicies();
        $this->configureRateLimiting();
    }

    /**
     * Register authorization policies.
     */
    protected function configurePolicies(): void
    {
        Gate::policy(Link::class, LinkPolicy::class);
        Gate::policy(SocialLink::class, SocialLinkPolicy::class);
        Gate::policy(Embed::class, EmbedPolicy::class);
    }

    /**
     * Configure rate limiters for public routes.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('profile-view', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });

        RateLimiter::for('click-track', function (Request $request) {
            return Limit::perMinute(30)->by($request->ip());
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
