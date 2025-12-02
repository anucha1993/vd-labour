@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="bi bi-arrow-repeat"></i> ตรวจสอบและ Convert ใบสมัคร</h4>
                    <a href="{{ route('job-leads.conversion.index') }}" class="btn btn-light">
                        <i class="bi bi-arrow-left"></i> กลับ
                    </a>
                </div>

                <div class="card-body">
                    <!-- Validation Results -->
                    @if(count($validationErrors) > 0)
                    <div class="alert alert-danger mb-4">
                        <h5 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> ไม่สามารถ Convert ได้ด้วยเหตุผลดังต่อไปนี้:</h5>
                        <ul class="mb-0">
                            @foreach($validationErrors as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @else
                    <div class="alert alert-success mb-4">
                        <i class="bi bi-check-circle"></i> ผ่านการตรวจสอบทั้งหมด พร้อมสำหรับการ Convert
                    </div>
                    @endif

                    <!-- Applicant Info -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h5 class="mb-0">ข้อมูลผู้สมัคร</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>ชื่อ-นามสกุล:</strong><br>
                                            {{ $jobLead->lead ? $jobLead->lead->getFullNameAttribute() : 'ไม่พบข้อมูล' }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>เบอร์โทร:</strong><br>
                                            {{ $jobLead->lead ? ($jobLead->lead->lead_phone ?: '-') : '-' }}
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <strong>Passport:</strong><br>
                                            <code>{{ $jobLead->lead ? ($jobLead->lead->lead_passport_number ?: '-') : '-' }}</code>
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <strong>วันเกิด:</strong><br>
                                            @if($jobLead->lead && $jobLead->lead->lead_birthday)
                                                {{ \Carbon\Carbon::parse($jobLead->lead->lead_birthday)->format('d/m/Y') }}
                                                (อายุ {{ \Carbon\Carbon::parse($jobLead->lead->lead_birthday)->age }} ปี)
                                            @else
                                                -
                                            @endif
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <strong>Passport Issue:</strong><br>
                                            {{ $jobLead->lead && $jobLead->lead->lead_passport_issue_date ? \Carbon\Carbon::parse($jobLead->lead->lead_passport_issue_date)->format('d/m/Y') : '-' }}
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <strong>Passport Expiry:</strong><br>
                                            @if($jobLead->lead && $jobLead->lead->lead_passport_expiry_date)
                                                <span class="{{ \Carbon\Carbon::parse($jobLead->lead->lead_passport_expiry_date)->isBefore(\Carbon\Carbon::now()->addYears(3)) ? 'text-danger fw-bold' : 'text-success fw-bold' }}">
                                                    {{ \Carbon\Carbon::parse($jobLead->lead->lead_passport_expiry_date)->format('d/m/Y') }}
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <strong>ประเทศ:</strong><br>
                                            {{ $jobLead->lead && $jobLead->lead->country ? $jobLead->lead->country->country_name_th : '-' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Job Info -->
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h5 class="mb-0">ข้อมูลงาน</h5>
                                </div>
                                <div class="card-body">
                                    <strong>หมายเลขงาน:</strong><br>
                                    {{ $jobLead->job ? $jobLead->job->job_number : '-' }}
                                    <hr>
                                    <strong>ชื่องาน:</strong><br>
                                    {{ $jobLead->job ? $jobLead->job->job_name : '-' }}
                                    <hr>
                                    <strong>ประเภทงาน:</strong><br>
                                    {{ $jobLead->job && $jobLead->job->jobGroup ? $jobLead->job->jobGroup->job_group_name : '-' }}
                                    <hr>
                                    <strong>ตำแหน่ง:</strong><br>
                                    {{ $jobLead->job && $jobLead->job->position ? $jobLead->job->position->position_name : '-' }}
                                    <hr> 
                                    <strong>ประเทศ:</strong><br>
                                    {{ $jobLead->job && $jobLead->job->country ? $jobLead->job->country->country_name_th : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Application Details -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">ข้อมูลใบสมัคร</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <strong>หมายเลขใบสมัคร:</strong><br>
                                    {{ $jobLead->job_lead_number }}
                                </div>
                                <div class="col-md-3">
                                    <strong>สถานะ:</strong><br>
                                    <span class="badge bg-success">{{ $jobLead->job_lead_status }}</span>
                                </div>
                                <div class="col-md-3">
                                    <strong>วันที่สมัคร:</strong><br>
                                    {{ $jobLead->created_at->format('d/m/Y H:i') }}
                                </div>
                                <div class="col-md-3">
                                    <strong>ล็อค:</strong><br>
                                    @if($jobLead->is_locked)
                                        <span class="badge bg-warning">ล็อคอยู่</span>
                                    @else
                                        <span class="badge bg-secondary">ไม่ล็อค</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('job-leads.conversion.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> ยกเลิก
                        </a>
                        @if(empty($validationErrors))
                        <button type="button" class="btn btn-success" id="convertBtn" onclick="performConversion()">
                            <i class="bi bi-check-circle"></i> ยืนยัน Convert
                        </button>
                        @else
                        <button type="button" class="btn btn-success" disabled>
                            <i class="bi bi-check-circle"></i> ไม่สามารถ Convert
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function performConversion() {
    if (!confirm('ยืนยันการ Convert ใบสมัครนี้ไปเป็น Labour?\n\nจะอัปเดต:\n- สถานะใบสมัคร → Completed\n- ล็อคใบสมัคร\n- สร้าง Labour record ใหม่\n- อัปเดตสถานะ Lead → Converted')) {
        return;
    }

    const btn = document.getElementById('convertBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> กำลัง Convert...';

    fetch('{{ route("job-leads.conversion.store", $jobLead->job_lead_id) }}', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✓ ' + data.message);
            window.location.href = '{{ route("job-leads.conversion.index") }}';
        } else {
            alert('✗ ' + (data.message || 'เกิดข้อผิดพลาด'));
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-circle"></i> ยืนยัน Convert';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('✗ เกิดข้อผิดพลาดในการเชื่อมต่อ');
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check-circle"></i> ยืนยัน Convert';
    });
}
</script>
@endsection
