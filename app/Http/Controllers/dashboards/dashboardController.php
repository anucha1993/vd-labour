<?php

namespace App\Http\Controllers\dashboards;

use App\Http\Controllers\Controller;
use App\Models\labours\labourModel;
use Illuminate\Http\Request;

class dashboardController extends Controller
{
    //

    public function index()
    {
        $scopeExpiringDiseaseConstruct = labourModel::query()->ExpiringDiseaseConstruct()->count();
        $scopeExpiringDiseaseFactory   = labourModel::query()->ExpiringDiseaseFactory()->count();
        $scopeExpiringCIDConstruct    = labourModel::query()->ExpiringCIDConstruct()->count();
        $scopeExpiringCIDFactory   = labourModel::query()->ExpiringCIDFactory()->count();
        $scopeExpiringPassport   = labourModel::query()->ExpiringPassport()->count();

        $countCancel = labourModel::query()->CountCancel()->count();
        $countAll = labourModel::query()->CountAll()->count();
        $countSuccess = labourModel::query()->CountSuccess()->count();


        return view('dashboards.index', compact( 'countCancel', 'countAll', 'countSuccess','scopeExpiringPassport','scopeExpiringDiseaseConstruct','scopeExpiringDiseaseFactory','scopeExpiringCIDConstruct','scopeExpiringCIDFactory'));
    }
}
