@extends('layouts.main')
@section('content')

<div class="col-lg-12">
    <div class="row">
      <div class="col-3">
        <div class="bg-info p-10 text-white text-center">
          <i class="mdi mdi-account fs-3 mb-1 font-20"></i>
          <h5 class="mb-0 mt-1">{{number_format($countAll)}}</h5>
          <small class="font-16">ข้อมูลคนงานทั้งหมด</small><br>
          <small class="font-16">ทั้งหมดในระบบ</small>
        </div>
      </div>
      <div class="col-3">

        <div class="bg-success p-10 text-white text-center">
          <i class="mdi mdi-account fs-3 mb-1 font-16"></i>
          <h5 class="mb-0 mt-1">{{number_format($countSuccess)}}</h5>
          <small class="font-16">จำนวนแรงงานไปทำงานแล้ว</small><br>
          <small class="font-16">คนงานที่บินแล้วทั้งหมด</small>
        </div>
        </a>
      </div>

      <div class="col-3">
        <a href="{{ route('labours.alert.list', ['type' => 'disease-construct']) }}">
        <div class="bg-danger p-10 text-white text-center">
            <i class="mdi mdi-account fs-3 mb-1 font-16"></i>
            <h5 class="mb-0 mt-1 font-16">{{number_format($scopeExpiringDiseaseConstruct)}}</h5>
          <small class="font-16">แจ้งเตือนผลโรค ก่อสร้าง </small><br>
          <small class="font-16">ก่อนหมดอายุ 15 วัน</small>
        </div>
        </a>
      </div>

      <div class="col-3">
        <a href="{{ route('labours.alert.list', ['type' => 'disease-factory']) }}">
        <div class="bg-danger p-10 text-white text-center">
            <i class="mdi mdi-account fs-3 mb-1 font-16"></i>
            <h5 class="mb-0 mt-1 font-16">{{number_format($scopeExpiringDiseaseFactory)}}</h5>
          <small class="font-16">แจ้งเตือนผลโรค โรงงาน</small><br>
          <small class="font-16">ก่อนหมดอายุ 15 วัน</small>
        </div>
        </a>
      </div>

  
    </div>
  </div>

  <div class="col-lg-12 mt-3">
    <div class="row">
      <div class="col-3">
        <div class="bg-warning p-10 text-white text-center">
          <i class="mdi mdi-account fs-3 mb-1 font-20"></i>
          <h5 class="mb-0 mt-1 font-16">{{$countCancel}}</h5>
          <small class="font-16">จำนวนคนงานยกเลิก</small><br>
          <small class="font-16">ยกเลิกทั้งหมด</small>
        </div>
      </div>
      <div class="col-3">
        <a href="{{ route('labours.alert.list', ['type' => 'passport']) }}">
        <div class="bg-danger p-10 text-white text-center">
          <i class="mdi mdi-account fs-3 mb-1 font-20"></i>
          <h5 class="mb-0 mt-1 font-16">{{number_format($scopeExpiringPassport)}}</h5>
          <small class="font-16">พาสปอตหมดอายุ</small><br>
          <small class="font-16">ก่อนหมดอายุ 15 วัน</small>
        </div>
        </a>
      </div>
      <div class="col-3">
        <a href="{{ route('labours.alert.list', ['type' => 'cid-construct']) }}">
        <div class="bg-danger p-10 text-white text-center">
          <i class="mdi mdi-account fs-3 mb-1 font-20"></i>
          <h5 class="mb-0 mt-1 font-16">{{number_format($scopeExpiringCIDConstruct)}}</h5>
          <small class="font-16">แจ้งเตือน CID หมดอายุ ก่อสร้าง</small><br>
          <small class="font-16">ก่อนหมดอายุ 15 วัน</small>
        </div>
        </a>
      </div>
      <div class="col-3">
        <a href="{{ route('labours.alert.list', ['type' => 'cid-factory']) }}">
        <div class="bg-danger p-10 text-white text-center">
          <i class="mdi mdi-account fs-3 mb-1 font-20"></i>
          <h5 class="mb-0 mt-1 font-16">{{number_format($scopeExpiringCIDFactory)}}</h5>
          <small class="font-16">แจ้งเตือน CID หมดอายุ โรงงาน</small><br>
          <small class="font-16">ก่อนหมดอายุ 15 วัน</small>
        </div>
        </a>
      </div>
    </div>
  </div>



    <div class="row">
        <div class="card">
            <div class="card-body">
              <a href="{{ route('labours.export.alerts') }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Export แจ้งเตือนทั้งหมด (Excel)
            </a>
            </div>
        </div>
    </div>


@endsection
