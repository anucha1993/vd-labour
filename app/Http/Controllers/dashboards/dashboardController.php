<?php

namespace App\Http\Controllers\dashboards;

use App\Http\Controllers\Controller;
use App\Models\labours\labourModel;
use App\Services\JobLeadNotificationService;
use Illuminate\Http\Request;

class dashboardController extends Controller
{
    protected $notificationService;

    public function __construct(JobLeadNotificationService $notificationService)
    {
        $this->middleware('auth');
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $scopeExpiringDiseaseConstruct = labourModel::query()->ExpiringDiseaseConstruct()->count();
        $scopeExpiringDiseaseFactory   = labourModel::query()->ExpiringDiseaseFactory()->count();
        $scopeExpiringCIDConstruct    = labourModel::query()->ExpiringCIDConstruct()->count();
        $scopeExpiringCIDFactory   = labourModel::query()->ExpiringCIDFactory()->count();
        $scopeExpiringPassport   = labourModel::query()->ExpiringPassport()->count();
        $scopeExpiringIdCard   = labourModel::query()->ExpiringIdCard()->count();
        $scopeExpiringCidMoney   = labourModel::query()->ExpiringCidMoney()->count();
        $scopeExpiringAffidavit = labourModel::query()->ExpiringAffidavit()->count();

        // VISA Notifications
        $visaNotUpdate = labourModel::query()->VisaNotUpdate()->count();
        $visaApproved = labourModel::query()->VisaApproved()->count();  
        $visaRejected = labourModel::query()->VisaRejected()->count();

        $countCancel = labourModel::query()->CountCancel()->count();
        $countAll = labourModel::query()->CountAll()->count();
        $countSuccess = labourModel::query()->CountSuccess()->count();

        // Job Lead Notifications
        $jobLeadNotifications = $this->notificationService->getUnreadCount();

        return view('dashboards.index', compact( 'countCancel', 'countAll','scopeExpiringCidMoney', 'countSuccess','scopeExpiringPassport','scopeExpiringIdCard','scopeExpiringDiseaseConstruct','scopeExpiringDiseaseFactory','scopeExpiringCIDConstruct','scopeExpiringCIDFactory','scopeExpiringAffidavit', 'visaNotUpdate', 'visaApproved', 'visaRejected', 'jobLeadNotifications'));
    }
}
