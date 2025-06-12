<?php

namespace App\Http\Controllers;

use App\Models\labours\labourModel;

class LabourAlertController extends Controller
{
    public function show($type)
    {
        $labours = match ($type) {
            'passport'          => labourModel::ExpiringPassport()->with('customer')->get(),
            'disease-construct' => labourModel::ExpiringDiseaseConstruct()->with('customer')->get(),
            'disease-factory'   => labourModel::ExpiringDiseaseFactory()->with('customer')->get(),
            'cid-construct'     => labourModel::ExpiringCIDConstruct()->with('customer')->get(),
            'cid-factory'       => labourModel::ExpiringCIDFactory()->with('customer')->get(),
            default             => collect(),
        };

        return view('labours.alert-list', compact('labours', 'type'));
    }
}
