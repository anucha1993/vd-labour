<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            // ตัวอย่าง: เรียกใช้ helper ที่มี logic จริง (แก้ path ตามจริง)
            if (!function_exists('getExpiringDiseaseConstruct')) {
                require_once app_path('helpers/labourStatusHelper.php');
            }
            $view->with([
                'scopeExpiringDiseaseConstruct' => getExpiringDiseaseConstruct(),
                'scopeExpiringDiseaseFactory' => getExpiringDiseaseFactory(),
                'scopeExpiringPassport' => getExpiringPassport(),
                'scopeExpiringCIDConstruct' => getExpiringCIDConstruct(),
                'scopeExpiringCIDFactory' => getExpiringCIDFactory(),
                'scopeExpiringCidMoney' => getExpiringCidMoney(),
                'scopeExpiringAffidavit' => getExpiringAffidavit(),
            ]);
        });
    }
}
