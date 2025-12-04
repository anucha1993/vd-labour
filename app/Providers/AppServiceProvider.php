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
        // Register MpdfService
        $this->app->singleton('mpdf', function () {
            return new \App\Services\MpdfService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register JobLeadObserver
        \App\Models\jobs\JobLeadModel::observe(\App\Observers\JobLeadObserver::class);
        
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
                'visaNotUpdate' => getVisaNotUpdate(),
                'visaApproved' => getVisaApproved(),
                'visaRejected' => getVisaRejected(),
                'pendingConversionCount' => \App\Models\jobs\JobLeadModel::pendingConversion()->count(),
            ]);
        });
    }
}
