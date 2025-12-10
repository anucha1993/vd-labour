@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">รายละเอียดใบสมัคร: {{ $jobLead->job_lead_number }}</h4>
                    <div>
                        @can('job-lead-edit')
                        <a href="{{ route('job-leads.edit', $jobLead->job_lead_id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> แก้ไข
                        </a>
                        @endcan
                        <a href="{{ route('job-leads.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> กลับ
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="row">
                        <!-- Job Lead Information -->
                        <div class="col-md-8">
                            <h5 class="border-bottom pb-2 mb-3">ข้อมูลใบสมัคร</h5>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>หมายเลขใบสมัคร:</strong><br>
                                    <span class="fs-5 text-primary">{{ $jobLead->job_lead_number }}</span>
                                </div>
                                <div class="col-md-6">
                                    <strong>สถานะ:</strong><br>
                                    <span class="badge fs-6 bg-{{ $jobLead->status_badge_color }}">
                                        {{ $jobLead->job_lead_status }}
                                    </span>
                                    @if($jobLead->is_locked)
                                        <span class="badge bg-warning">🔒 ล็อค</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Lead ID:</strong><br>
                                    <span class="badge bg-secondary fs-6">{{ $jobLead->lead_id }}</span>
                                </div>
                                <div class="col-md-6">
                                    <strong>สถานะล็อค:</strong><br>
                                    @if($jobLead->is_locked)
                                        <i class="bi bi-lock-fill text-warning"></i> ล็อคแล้ว
                                        @if($jobLead->locked_at)
                                            <br><small class="text-muted">เมื่อ: {{ $jobLead->locked_at->format('d/m/Y H:i') }}</small>
                                        @endif
                                    @else
                                        <i class="bi bi-unlock text-success"></i> ไม่ได้ล็อค
                                        @if($jobLead->unlocked_at)
                                            <br><small class="text-muted">ปลดล็อคเมื่อ: {{ $jobLead->unlocked_at->format('d/m/Y H:i') }}</small>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            <!-- Job Information -->
                            <h5 class="border-bottom pb-2 mb-3 mt-4">ข้อมูลงาน</h5>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>หมายเลขงาน:</strong><br>
                                    <a href="{{ route('jobs.show', $jobLead->job->job_id) }}" class="text-decoration-none">
                                        {{ $jobLead->job->job_number ?? 'N/A' }}
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <strong>สถานะงาน:</strong><br>
                                    <span class="badge {{ ($jobLead->job->job_status ?? '') == 'เปิดรับสมัคร' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $jobLead->job->job_status ?? 'N/A' }}
                                    </span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <strong>ชื่องาน:</strong><br>
                                    <span class="fs-5">{{ $jobLead->job->job_name ?? 'N/A' }}</span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>ประเทศ:</strong><br>
                                    <i class="flag-icon flag-icon-{{ strtolower($jobLead->job->country->country_code ?? 'xx') }}"></i>
                                    {{ $jobLead->job->country->country_name_th ?? 'N/A' }}
                                </div>
                                <div class="col-md-6">
                                    <strong>บริษัทที่ขอ:</strong><br>
                                    {{ $jobLead->job->demand->dm_com_name ?? 'N/A' }}
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>จำนวนเปิดรับ:</strong><br>
                                    {{ number_format($jobLead->job->job_total ?? 0) }} คน
                                </div>
                                <div class="col-md-4">
                                    <strong>ได้งานแล้ว:</strong><br>
                                    <span class="text-success">{{ number_format($jobLead->job->accepted_count ?? 0) }} คน</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>เหลือ:</strong><br>
                                    <span class="text-info">{{ number_format($jobLead->job->remaining_positions ?? 0) }} คน</span>
                                </div>
                            </div>

                            <!-- Lead Physical Information -->
                            @if($jobLead->lead)
                            <h5 class="border-bottom pb-2 mb-3 mt-4">ข้อมูลร่างกายผู้สมัคร</h5>
                            
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <strong>ชื่อ-สกุล:</strong><br>
                                    <span class="fs-5">{{ $jobLead->lead->getFullNameAttribute() }}</span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <strong>ส่วนสูง:</strong><br>
                                    <span class="fs-5">{{ $jobLead->lead->lead_height ?? '-' }}</span> <small class="text-muted">cm</small>
                                </div>
                                <div class="col-md-3">
                                    <strong>น้ำหนัก:</strong><br>
                                    <span class="fs-5">{{ $jobLead->lead->lead_weight ?? '-' }}</span> <small class="text-muted">kg</small>
                                </div>
                                <div class="col-md-6">
                                    <strong>BMI:</strong><br>
                                    @if($jobLead->lead->lead_bmi)
                                        @php
                                            $bmi = $jobLead->lead->lead_bmi;
                                            $category = '';
                                            $badgeClass = 'secondary';
                                            $description = '';
                                            
                                            if ($bmi < 18) {
                                                $category = 'ต่ำกว่าเกณฑ์';
                                                $badgeClass = 'danger';
                                                $description = 'Below Standard';
                                            } elseif ($bmi >= 18 && $bmi <= 30) {
                                                $category = 'ผ่านเกณฑ์';
                                                $badgeClass = 'success';
                                                $description = 'Pass';
                                            } else {
                                                $category = 'เกินเกณฑ์';
                                                $badgeClass = 'danger';
                                                $description = 'Above Standard';
                                            }
                                        @endphp
                                        <div>
                                            <span class="fs-4 fw-bold">{{ number_format($bmi, 2) }}</span>
                                            <span class="badge bg-{{ $badgeClass }} fs-6 ms-2">{{ $category }}</span>
                                        </div>
                                        <small class="text-muted">{{ $description }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </div>
                            </div>

                            @if($jobLead->lead->lead_height && $jobLead->lead->lead_weight && $jobLead->lead->lead_bmi)
                            <div class="alert alert-info">
                                <small>
                                    <strong>เกณฑ์ BMI สำหรับคนเอเชีย:</strong><br>
                                    • ผอม: &lt; 18.50 | 
                                    • ปกติ: 18.50-22.90 | 
                                    • ท้วม: 23-24.90 | 
                                    • อ้วน 1: 25-29.90 | 
                                    • อ้วน 2: ≥ 30
                                </small>
                            </div>
                            @endif
                            @endif

                            <!-- Remarks/History -->
                            @if($jobLead->remarks)
                            <h5 class="border-bottom pb-2 mb-3 mt-4">ประวัติและหมายเหตุ</h5>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <pre style="white-space: pre-wrap; font-family: inherit; margin-bottom: 0;">{{ $jobLead->remarks }}</pre>
                                </div>
                            </div>
                            @endif

                            <!-- Created/Updated Info -->
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <strong>ผู้สร้าง:</strong><br>
                                    {{ $jobLead->createdBy->name ?? 'N/A' }}
                                    <br><small class="text-muted">{{ $jobLead->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                                <div class="col-md-6">
                                    <strong>อัปเดตล่าสุด:</strong><br>
                                    {{ $jobLead->updatedBy->name ?? 'N/A' }}
                                    <br><small class="text-muted">{{ $jobLead->updated_at->format('d/m/Y H:i') }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions Sidebar -->
                        <div class="col-md-4">
                            <h5 class="border-bottom pb-2 mb-3">การดำเนินการ</h5>
                            
                            <div class="d-grid gap-2">
                                @can('job-lead-edit')
                                <a href="{{ route('job-leads.edit', $jobLead->job_lead_id) }}" 
                                   class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> แก้ไขใบสมัคร
                                </a>
                                @endcan

                                @if($jobLead->is_locked)
                                    @can('job-lead-force-unlock')
                                    <button type="button" class="btn btn-outline-danger" 
                                            onclick="forceUnlock({{ $jobLead->job_lead_id }})">
                                        <i class="bi bi-unlock"></i> ปลดล็อคบังคับ
                                    </button>
                                    @endcan
                                @endif

                                <a href="{{ route('jobs.show', $jobLead->job->job_id) }}" 
                                   class="btn btn-info">
                                    <i class="bi bi-briefcase"></i> ดูข้อมูลงาน
                                </a>

                                <a href="{{ route('job-leads.index', ['job_id' => $jobLead->job_id]) }}" 
                                   class="btn btn-outline-primary">
                                    <i class="bi bi-list-ul"></i> ใบสมัครงานนี้ทั้งหมด
                                </a>
                            </div>

                            <!-- Status Flow Guide -->
                            <div class="mt-4">
                                <h6 class="text-muted mb-3">🔄 ขั้นตอนสถานะ</h6>
                                <div class="small">
                                    <div class="mb-2 {{ $jobLead->job_lead_status == 'ร่าง' ? 'text-primary fw-bold' : 'text-muted' }}">
                                        📝 ร่าง
                                    </div>
                                    <div class="mb-2 {{ $jobLead->job_lead_status == 'ส่งแล้ว' ? 'text-primary fw-bold' : 'text-muted' }}">
                                        ✉️ ส่งแล้ว <small>(🔒 ล็อค)</small>
                                    </div>
                                    <div class="mb-2 {{ $jobLead->job_lead_status == 'กำลังพิจารณา' ? 'text-primary fw-bold' : 'text-muted' }}">
                                        👁️ กำลังพิจารณา <small>(🔒 ล็อค)</small>
                                    </div>
                                    <div class="mb-2 {{ $jobLead->job_lead_status == 'นัดสัมภาษณ์' ? 'text-primary fw-bold' : 'text-muted' }}">
                                        🎯 นัดสัมภาษณ์ <small>(🔒 ล็อค)</small>
                                    </div>
                                    <div class="mb-2 {{ $jobLead->job_lead_status == 'เสนองาน' ? 'text-primary fw-bold' : 'text-muted' }}">
                                        💼 เสนองาน <small>(🔒 ล็อค)</small>
                                    </div>
                                    <div class="mb-2 {{ $jobLead->job_lead_status == 'ตอบรับ' ? 'text-success fw-bold' : 'text-muted' }}">
                                        ✅ ตอบรับ <small>(🔒 ล็อค)</small>
                                    </div>
                                    <div class="mb-2 {{ $jobLead->job_lead_status == 'ปฏิเสธ' ? 'text-danger fw-bold' : 'text-muted' }}">
                                        ❌ ปฏิเสธ <small>(🔓 ปลดล็อค)</small>
                                    </div>
                                    <div class="mb-2 {{ $jobLead->job_lead_status == 'ถอน' ? 'text-warning fw-bold' : 'text-muted' }}">
                                        🚫 ถอน <small>(🔓 ปลดล็อค)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Force Unlock Modal -->
<div class="modal fade" id="forceUnlockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ปลดล็อคคนงานบังคับ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="forceUnlockForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        <strong>คำเตือน:</strong> การปลดล็อคบังคับจะทำให้คนงานสามารถถูกเลือกสำหรับงานอื่นได้
                    </div>
                    <div class="mb-3">
                        <label for="unlockReason" class="form-label">เหตุผลในการปลดล็อค <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="unlockReason" name="reason" rows="3" required
                                  placeholder="กรุณาระบุเหตุผลในการปลดล็อคบังคับ..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-danger">ปลดล็อคบังคับ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.flag-icon {
    width: 1.5em;
    height: 1em;
    margin-right: 0.5em;
}
</style>

<script>
// Force Unlock Function
function forceUnlock(jobLeadId) {
    const modal = new bootstrap.Modal(document.getElementById('forceUnlockModal'));
    const form = document.getElementById('forceUnlockForm');
    
    form.action = `/job-leads/${jobLeadId}/force-unlock`;
    document.getElementById('unlockReason').value = '';
    
    modal.show();
}
</script>
@endsection