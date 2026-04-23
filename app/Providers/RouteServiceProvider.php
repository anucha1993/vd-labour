<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use App\Models\jobs\JobLeadModel;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        // Configure route model bindings
        Route::bind('jobLead', function ($value) {
            if ($value instanceof JobLeadModel) {
                return $value;
            }

            $rawValue = trim((string) $value);
            $lookupValue = $rawValue;

            // Defensive parse: some links may accidentally pass a CSV-like payload.
            if (str_contains($lookupValue, ',')) {
                $lookupValue = trim(strtok($lookupValue, ','));
            }

            if (is_numeric($lookupValue)) {
                return JobLeadModel::where('job_lead_id', (int) $lookupValue)->firstOrFail();
            }

            return JobLeadModel::where('job_lead_number', $lookupValue)->firstOrFail();
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
