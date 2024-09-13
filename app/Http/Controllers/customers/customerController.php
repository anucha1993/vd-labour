<?php

namespace App\Http\Controllers\customers;

use App\Http\Controllers\Controller;
use App\Models\country\countryModel;
use App\Models\customers\customerModel;
use Illuminate\Http\Request;

class customerController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $customers = customerModel::select('customers.customer_id','customers.customer_status','customers.customer_name','country.country_name_th','customers.created_at')->where('customer_status','active')
        ->leftjoin('country','country.country_id','customers.country_id')
        ->latest('customers.created_at')
        ->get();

        $country = countryModel::where('country_status','active')->latest()->get();

        //dd($customers);
        return view('customers.index',compact('customers','country'));
    }

    public function store(Request $request)
    {
        customerModel::create($request->all());

        return redirect()->back();
    }

    public function edit(customerModel $customerModel)
    {
        $country = countryModel::where('country_status','active')->latest()->get();
        return view('customers.modal-edit',compact('customerModel','country'));
    }

    public function update(customerModel $customerModel, Request $request)
    {
        $customerModel->update($request->all());
    }
}
