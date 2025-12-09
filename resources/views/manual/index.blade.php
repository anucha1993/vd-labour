@extends('layouts.main')
@section('content')
    <div class="card card-custom mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">
                    <i class="bi bi-book-fill me-2 text-primary"></i>คู่มือการใช้งานระบบ
                </h4>
                <span class="badge bg-info">
                    <i class="bi bi-file-pdf-fill"></i> {{ count($manuals) }} ไฟล์
                </span>
            </div>

            @if($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>{{ $message }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="alert alert-info">
                <i class="bi bi-info-circle-fill me-2"></i>
                <strong>วิธีการใช้งาน:</strong> คลิกที่ชื่อไฟล์เพื่อเปิดดูคู่มือ หรือดาวน์โหลดไฟล์ได้
            </div>

            @if(count($manuals) > 0)
                <div class="row g-3">
                    @foreach($manuals as $manual)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm border-0 hover-shadow">
                                <div class="card-body">
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="bg-danger bg-opacity-10 rounded p-3 me-3">
                                            <i class="bi bi-file-pdf-fill text-danger" style="font-size: 2rem;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="card-title mb-1">{{ $manual['name'] }}</h6>
                                            <small class="text-muted">
                                                <i class="bi bi-hdd"></i> {{ $manual['size'] }}
                                            </small>
                                            <br>
                                            <small class="text-muted">
                                                <i class="bi bi-clock"></i> {{ $manual['modified'] }}
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <div class="d-grid gap-2">
                                        <a href="{{ asset($manual['path']) }}" 
                                           target="_blank" 
                                           class="btn btn-primary btn-sm">
                                            <i class="bi bi-eye-fill"></i> เปิดดูคู่มือ
                                        </a>
                                        <a href="{{ asset($manual['path']) }}" 
                                           download 
                                           class="btn btn-outline-secondary btn-sm">
                                            <i class="bi bi-download"></i> ดาวน์โหลด
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                    <h5 class="text-muted mt-3">ยังไม่มีไฟล์คู่มือในระบบ</h5>
                    <p class="text-muted">
                        กรุณาอัพโหลดไฟล์ PDF ลงในโฟลเดอร์ <code>public/manuals</code>
                    </p>
                </div>
            @endif

            <!-- Information Card -->
            <div class="card bg-light border-0 mt-4">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-lightbulb-fill text-warning me-2"></i>
                        หมายเหตุสำหรับผู้ดูแลระบบ
                    </h6>
                    <ul class="mb-0">
                        <li>วางไฟล์คู่มือ PDF ไว้ที่โฟลเดอร์: <code>public/manuals</code></li>
                        <li>ระบบจะอ่านและแสดงไฟล์ PDF ทั้งหมดในโฟลเดอร์อัตโนมัติ</li>
                        <li>สามารถเพิ่ม ลบ หรือแก้ไขไฟล์ได้ตลอดเวลา ระบบจะอัพเดททันที</li>
                        <li>รองรับเฉพาะไฟล์ PDF เท่านั้น</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <style>
        .hover-shadow {
            transition: all 0.3s ease;
        }
        .hover-shadow:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
    </style>
@endsection
