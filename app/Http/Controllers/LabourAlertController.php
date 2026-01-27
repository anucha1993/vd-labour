<?php

namespace App\Http\Controllers;

use App\Models\labours\labourModel;

class LabourAlertController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show($type)
    {
        $labours = match ($type) {
            'passport'          => labourModel::ExpiringPassport()->with('customer')->get(),
            'id-card'           => labourModel::ExpiringIdCard()->with('customer')->get(),
            'disease-construct' => labourModel::ExpiringDiseaseConstruct()->with('customer')->get(),
            'disease-factory'   => labourModel::ExpiringDiseaseFactory()->with('customer')->get(),
            'cid-construct'     => labourModel::ExpiringCIDConstruct()->with('customer')->get(),
            'cid-factory'       => labourModel::ExpiringCIDFactory()->with('customer')->get(),
            'cid-money'         => labourModel::ExpiringCidMoney()->with('customer')->get(),
            'affidavit'         => labourModel::ExpiringAffidavit()->with('customer')->get(),
            'visa-not-update'   => labourModel::VisaNotUpdate()->with('customer')->get(),
            'visa-approved'     => labourModel::VisaApproved()->with('customer')->get(),
            'visa-rejected'     => labourModel::VisaRejected()->with('customer')->get(),
            default             => collect(),
        };

        return view('labours.alert-list', compact('labours', 'type'));
    }
}
