@extends('layouts.main')
@section('content')

<div class="container-fluid">

    {{-- บล็อกแรก --}}
    <div class="row g-3">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="bg-info text-white text-center p-3 rounded shadow">
                <i class="mdi mdi-account fs-3 mb-2"></i>
                <h1 class="mb-1">{{ number_format($countAll) }}</h1>
                <small>ข้อมูลคนงานทั้งหมด</small><br>
                <small>ทั้งหมดในระบบ</small>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="bg-success text-white text-center p-3 rounded shadow">
                <i class="mdi mdi-account fs-3 mb-2"></i>
                <h1 class="mb-1">{{ number_format($countSuccess) }}</h1>
                <small>จำนวนแรงงานไปทำงานแล้ว</small><br>
                <small>คนงานที่บินแล้วทั้งหมด</small>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <a href="{{ route('labours.alert.list', ['type' => 'disease-construct']) }}" class="text-decoration-none">
                <div class="bg-danger text-white text-center p-3 rounded shadow">
                    <i class="mdi mdi-account fs-3 mb-2"></i>
                    <h1 class="mb-1">{{ number_format($scopeExpiringDiseaseConstruct) }}</h1>
                    <small>แจ้งเตือนผลโรค ก่อสร้าง</small><br>
                    <small>ก่อนหมดอายุ 15 วัน</small>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <a href="{{ route('labours.alert.list', ['type' => 'disease-factory']) }}" class="text-decoration-none">
                <div class="bg-danger text-white text-center p-3 rounded shadow">
                    <i class="mdi mdi-account fs-3 mb-2"></i>
                    <h1 class="mb-1">{{ number_format($scopeExpiringDiseaseFactory) }}</h1>
                    <small>แจ้งเตือนผลโรค โรงงาน</small><br>
                    <small>ก่อนหมดอายุ 15 วัน</small>
                </div>
            </a>
        </div>
    </div>

    {{-- บล็อกที่สอง --}}
    <div class="row g-3 mt-3">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="bg-warning text-white text-center p-3 rounded shadow">
                <i class="mdi mdi-account fs-3 mb-2"></i>
                <h1 class="mb-1">{{ $countCancel }}</h1>
                <small>จำนวนคนงานยกเลิก</small><br>
                <small>ยกเลิกทั้งหมด</small>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <a href="{{ route('labours.alert.list', ['type' => 'passport']) }}" class="text-decoration-none">
                <div class="bg-danger text-white text-center p-3 rounded shadow">
                    <i class="mdi mdi-account fs-3 mb-2"></i>
                    <h1 class="mb-1">{{ number_format($scopeExpiringPassport) }}</h1>
                    <small>พาสปอร์ตหมดอายุ</small><br>
                    <small>ก่อนหมดอายุ 15 วัน</small>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <a href="{{ route('labours.alert.list', ['type' => 'cid-construct']) }}" class="text-decoration-none">
                <div class="bg-danger text-white text-center p-3 rounded shadow">
                    <i class="mdi mdi-account fs-3 mb-2"></i>
                    <h1 class="mb-1">{{ number_format($scopeExpiringCIDConstruct) }}</h1>
                    <small>แจ้งเตือน CID หมดอายุ ก่อสร้าง</small><br>
                    <small>ก่อนหมดอายุ 15 วัน</small>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <a href="{{ route('labours.alert.list', ['type' => 'cid-factory']) }}" class="text-decoration-none">
                <div class="bg-danger text-white text-center p-3 rounded shadow">
                    <i class="mdi mdi-account fs-3 mb-2"></i>
                    <h1 class="mb-1">{{ number_format($scopeExpiringCIDFactory) }}</h1>
                    <small>แจ้งเตือน CID หมดอายุ โรงงาน</small><br>
                    <small>ก่อนหมดอายุ 15 วัน</small>
                </div>
            </a>
        </div>
    </div>

    {{-- บล็อกที่สาม (เหลือบล็อกเดียว) --}}
    <div class="row g-3 mt-3">
        <div class="col-12 col-sm-6 col-md-3">
            <a href="{{ route('labours.alert.list', ['type' => 'cid-money']) }}" class="text-decoration-none">
                <div class="bg-danger text-white text-center p-3 rounded shadow">
                    <i class="mdi mdi-account fs-3 mb-2"></i>
                    <h1 class="mb-1">{{ number_format($scopeExpiringCidMoney) }}</h1>
                    <small>แจ้งเตือนคนงานที่ยังไม่ได้จ่ายเงินประกัน</small><br>
                    <small>ยื่น CID ไปแล้ว 15 วัน</small>
                </div>
            </a>
        </div>

         {{-- บล็อกที่สี่ --}}

        <div class="col-12 col-sm-6 col-md-3">
            <a href="{{ route('labours.alert.list', ['type' => 'affidavit']) }}" class="text-decoration-none">
                <div class="bg-danger text-white text-center p-3 rounded shadow">
                    <i class="mdi mdi-account fs-3 mb-2"></i>
                    <h1 class="mb-1">{{ number_format($scopeExpiringAffidavit) }}</h1>
                    <small>แจ้งเตือน Affidavit หมดอายุ</small><br>
                    <small>ก่อนหมดอายุ 15 วัน</small>
                </div>
            </a>
        </div>
   

    </div>
   

    {{-- ปุ่ม Export --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <a href="{{ route('labours.export.alerts') }}" class="btn btn-success btn-lg">
                        <i class="fas fa-file-excel"></i> Export แจ้งเตือนทั้งหมด (Excel)
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
