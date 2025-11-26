@extends('layouts.main')

@section('content')
    <div class="card card-custom mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0"><i class="bi bi-person-circle me-2 text-primary"></i>ข้อมูลผู้สนใจ (Lead): {{ $lead->getFullNameAttribute() }}</h4>
                <div class="d-flex gap-2">
                    <a href="{{ route('leads.edit', $lead->lead_id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil-square"></i> แก้ไข
                    </a>
                    <a href="{{ route('leads.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> กลับ
                    </a>
                </div>
            </div>

            <!-- 1. PERSONAL INFORMATION -->
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-person-fill me-2"></i>1. PERSONAL INFORMATION: ข้อมูลผู้สมัคร</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                @if($lead->lead_photo)
                                    <img src="{{ asset('storage/' . $lead->lead_photo) }}" class="img-thumbnail mb-2" style="width: 100%; max-height: 280px; object-fit: cover;">
                                @else
                                    <img src="https://via.placeholder.com/200x250?text=No+Photo" class="img-thumbnail mb-2" style="width: 100%; max-height: 280px; object-fit: cover;">
                                @endif
                                <p class="text-muted small">รูปถ่าย</p>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <strong>ชื่อ-นามสกุล:</strong> {{ $lead->getFullNameAttribute() }}
                                </div>
                                <div class="col-md-6">
                                    <strong>เพศ:</strong> {{ ucfirst($lead->lead_gender) }}
                                </div>
                                <div class="col-md-6">
                                    <strong>สถานะ:</strong> {{ ucfirst($lead->lead_marital_status) }}
                                </div>
                                <div class="col-md-6">
                                    <strong>วันเกิด:</strong> {{ $lead->lead_birthday ? $lead->lead_birthday->format('d/m/Y') : '-' }}
                                </div>
                                <div class="col-md-6">
                                    <strong>อายุ:</strong> {{ $lead->lead_age ?? '-' }} ปี
                                </div>
                                <div class="col-md-6">
                                    <strong>โทรศัพท์:</strong> {{ $lead->lead_phone ?? '-' }}
                                </div>
                                <div class="col-md-6">
                                    <strong>โทรศัพท์ (รอง):</strong> {{ $lead->lead_phone_2 ?? '-' }}
                                </div>
                                <div class="col-md-12">
                                    <strong>ที่อยู่:</strong><br>
                                    {{ $lead->lead_address ?? '-' }}
                                </div>
                                <div class="col-md-4">
                                    <strong>ส่วนสูง:</strong> {{ $lead->lead_height ?? '-' }} cm
                                </div>
                                <div class="col-md-4">
                                    <strong>น้ำหนัก:</strong> {{ $lead->lead_weight ?? '-' }} kg
                                </div>
                                <div class="col-md-4">
                                    <strong>BMI:</strong> {{ $lead->lead_bmi ?? '-' }}
                                </div>
                                <div class="col-md-6">
                                    <strong>Passport/ID Card No.:</strong> {{ $lead->lead_passport_number ?? '-' }}
                                </div>
                                <div class="col-md-6">
                                    <strong>วันหมดอายุ Passport:</strong> {{ $lead->lead_passport_expiry_date ? $lead->lead_passport_expiry_date->format('d/m/Y') : '-' }}
                                </div>
                                <div class="col-md-4">
                                    <strong>ไซส์เสื้อ:</strong> {{ $lead->lead_shirt_size ?? '-' }}
                                </div>
                                <div class="col-md-4">
                                    <strong>ไซส์กางเกง:</strong> {{ $lead->lead_pant_size ?? '-' }}
                                </div>
                                <div class="col-md-4">
                                    <strong>ไซส์รองเท้า:</strong> {{ $lead->lead_shoes_size ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. EDUCATION & LANGUAGES -->
            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-book-fill me-2"></i>2. EDUCATION / LANGUAGES SKILLS</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong>การศึกษา:</strong> 
                            @switch($lead->lead_education)
                                @case('elementary') ประถมศึกษา @break
                                @case('junior_high') มัธยมต้น @break
                                @case('high_school') มัธยมปลาย @break
                                @case('voc_cert') ปวช. @break
                                @case('high_voc_cert') ปวส. @break
                                @case('bachelor') ปริญญาตรี @break
                                @default {{ $lead->lead_education ?? '-' }}
                            @endswitch
                        </div>
                        <div class="col-md-6">
                            <strong>ภาษาอังกฤษ:</strong> {{ strtoupper($lead->lead_english_speaking ?? 'NO') }}
                        </div>
                        <div class="col-md-6">
                            <strong>ภาษาจีน:</strong> {{ strtoupper($lead->lead_chinese_speaking ?? 'NO') }}
                        </div>
                        <div class="col-md-6">
                            <strong>ภาษาอื่นๆ:</strong> {{ $lead->lead_other_language ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. ADDITIONAL INFORMATION -->
            <div class="card mb-3">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="bi bi-info-circle-fill me-2"></i>3. ADDITIONAL INFORMATION</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong>เคยทำงานในอิสราเอล:</strong> {{ strtoupper($lead->lead_work_israel ?? 'NO') }}
                        </div>
                        <div class="col-md-6">
                            <strong>ประวัติอาชญากรรม:</strong> {{ strtoupper($lead->lead_criminal_history ?? 'NO') }}
                        </div>
                        <div class="col-md-6">
                            <strong>การมองเห็น:</strong> {{ ucfirst($lead->lead_eyesight ?? 'Normal') }}
                        </div>
                        <div class="col-md-6">
                            <strong>ตาบอดสี:</strong> {{ strtoupper($lead->lead_color_blindness ?? 'NO') }}
                        </div>
                        @if($lead->lead_additional_details)
                        <div class="col-md-12">
                            <strong>รายละเอียดเพิ่มเติม:</strong><br>
                            <div class="bg-light p-3 rounded">
                                {!! nl2br(e($lead->lead_additional_details)) !!}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 4. POSITION AND SKILL -->
            <div class="card mb-3">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-briefcase-fill me-2"></i>4. POSITION AND SKILL</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <strong>ตำแหน่งที่ 1:</strong> {{ $lead->position->position_name ?? '-' }}
                        </div>
                        <div class="col-md-4">
                            <strong>ตำแหน่งที่ 2:</strong> {{ $lead->position2->position_name ?? '-' }}
                        </div>
                        <div class="col-md-4">
                            <strong>ตำแหน่งที่ 3:</strong> {{ $lead->position3->position_name ?? '-' }}
                        </div>
                        <div class="col-md-6">
                            <strong>ประเทศที่สนใจ:</strong> {{ $lead->country->country_name_th ?? $lead->country->country_name_en ?? '-' }}
                        </div>
                        <div class="col-md-6">
                            <strong>กลุ่มงาน:</strong> {{ $lead->jobGroup->job_group_name_th ?? $lead->jobGroup->job_group_name ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- 5. EMERGENCY CONTACT & DRIVING -->
            <div class="card mb-3">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="bi bi-telephone-fill me-2"></i>5. ข้อมูลเพิ่มเติม</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <strong>ผู้ติดต่อฉุกเฉิน:</strong> {{ $lead->lead_emergency_name ?? '-' }}
                        </div>
                        <div class="col-md-4">
                            <strong>ความสัมพันธ์:</strong> {{ $lead->lead_emergency_status ?? '-' }}
                        </div>
                        <div class="col-md-4">
                            <strong>เบอร์โทรฉุกเฉิน:</strong> {{ $lead->lead_emergency_phone ?? '-' }}
                        </div>
                        <div class="col-md-4">
                            <strong>ใบขับขี่:</strong> {{ strtoupper($lead->lead_driving_license ?? 'NO') }}
                        </div>
                        <div class="col-md-4">
                            <strong>ประเภทใบขับขี่:</strong> {{ $lead->lead_car_type ?? '-' }}
                        </div>
                        <div class="col-md-4">
                            <strong>วันหมดอายุใบขับขี่:</strong> {{ $lead->lead_license_valid_until ? $lead->lead_license_valid_until->format('d/m/Y') : '-' }}
                        </div>
                        <div class="col-md-6">
                            <strong>รอบสอบ:</strong> {{ $lead->examinationRound->examination_round_name ?? '-' }}
                        </div>
                        <div class="col-md-6">
                            <strong>ผู้แนะนำ:</strong> {{ $lead->recommenderStaff->staff_sub_name ?? '-' }}
                        </div>
                        <div class="col-md-6">
                            <strong>สถานะ Lead:</strong> 
                            <span class="badge 
                                @switch($lead->lead_status)
                                    @case('new') bg-primary @break
                                    @case('contacted') bg-info @break
                                    @case('interview') bg-warning @break
                                    @case('qualified') bg-success @break
                                    @case('converted') bg-dark @break
                                    @case('rejected') bg-danger @break
                                    @default bg-secondary
                                @endswitch
                            ">
                                @switch($lead->lead_status)
                                    @case('new') ใหม่ @break
                                    @case('contacted') ติดต่อแล้ว @break
                                    @case('interview') นัดสัมภาษณ์ @break
                                    @case('qualified') ผ่านคุณสมบัติ @break
                                    @case('converted') Convert แล้ว @break
                                    @case('rejected') ไม่ผ่าน @break
                                    @default {{ $lead->lead_status }}
                                @endswitch
                            </span>
                        </div>
                        <div class="col-md-6">
                            <strong>เจ้าหน้าที่:</strong> {{ $lead->staff->staff_name ?? '-' }}
                        </div>
                        @if($lead->lead_note)
                        <div class="col-md-12">
                            <strong>หมายเหตุ:</strong><br>
                            <div class="bg-light p-3 rounded">
                                {!! nl2br(e($lead->lead_note)) !!}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 6. DOCUMENT CHECKLIST -->
            <div class="card mb-3">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-file-earmark-check-fill me-2"></i>6. เอกสารคุณสมบัติ</h5>
                </div>
                <div class="card-body">
                    @php
                        $documents = $lead->documents ?? [];
                        $documentLabels = [
                            'รูปถ่ายขนาด_2_นิ้ว' => '1. รูปถ่ายขนาด 2 นิ้ว',
                            'Passport' => '2. Passport',
                            'บัตรประชาชน_สำเนา' => '3. บัตรประชาชน (สำเนา)',
                            'ผลโรค' => '4. ผลโรค',
                            'ใบผ่านงาน' => '5. ใบผ่านงาน',
                            'ใบอนุญาต_No' => '6. ใบอนุญาต No.',
                            'CID' => '7. CID',
                            'หนังสือเดินทาง_สำเนา' => '8. หนังสือเดินทาง (สำเนา)',
                            'ใบ_สผท' => '9. ใบ สผท.',
                            'ใบสุคันธ์_สำเนาจริงในเค' => '10. ใบสุคันธ์ (สำเนาจริงในเค)',
                            'CV' => '11. CV',
                            'หนังสือสมรส_กรณีสมรส' => '12. หนังสือสมรส (กรณีสมรส)',
                            'สำเนาบัตรประชาชนคู่สมรส_กรณีสมรส' => '13. สำเนาบัตรประชาชนคู่สมรส(กรณีสมรส)',
                            'สำเนาบัตรอัยพ่อแม่สกุล_กรณีเดคลแปลปธิ' => '14. สำเนาบัตรอัยพ่อแม่สกุล(กรณีเดคลแปลปธิ)'
                        ];
                    @endphp
                    
                    <div class="row g-2">
                        @foreach($documentLabels as $docKey => $docLabel)
                            <div class="col-md-6">
                                @if(in_array($docKey, $documents))
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <span class="text-success">{{ $docLabel }}</span>
                                @else
                                    <i class="bi bi-x-circle-fill text-danger me-2"></i>
                                    <span class="text-muted">{{ $docLabel }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- 7. JOB HISTORY -->
            <div class="card mb-3">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>7. JOB HISTORY: ประวัติการทำงาน</h5>
                </div>
                <div class="card-body">
                    @if($lead->jobHistory && count($lead->jobHistory) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>ช่วงเวลา</th>
                                        <th>ตำแหน่ง</th>
                                        <th>บริษัท/ประเภทงาน</th>
                                        <th>ประเทศ</th>
                                        <th>ปีประสบการณ์</th>
                                        <th>รายละเอียด</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lead->jobHistory->sortBy('display_order') as $index => $history)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                {{ $history->start_date }}
                                                @if($history->end_date)
                                                    <br><small class="text-muted">ถึง {{ $history->end_date }}</small>
                                                @else
                                                    <br><small class="badge bg-success">ปัจจุบัน</small>
                                                @endif
                                            </td>
                                            <td>{{ $history->position }}</td>
                                            <td>
                                                <strong>{{ $history->company_name }}</strong><br>
                                                <small class="text-muted">{{ $history->company_type }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $history->country }}</span>
                                            </td>
                                            <td>{{ $history->experience_years }} ปี</td>
                                            <td>
                                                @if($history->description)
                                                    <small>{!! nl2br(e($history->description)) !!}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>ยังไม่มีประวัติการทำงาน
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection