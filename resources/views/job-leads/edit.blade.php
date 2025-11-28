@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">แก้ไขใบสมัคร: {{ $jobLead->job_lead_number }}</h4>
                    <div>
                        <a href="{{ route('job-leads.show', $jobLead->job_lead_id) }}" class="btn btn-info">
                            <i class="bi bi-eye"></i> ดูรายละเอียด
                        </a>
                        <a href="{{ route('job-leads.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> กลับ
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Job Information Card -->
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h6 class="card-title text-primary">ข้อมูลงาน</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>หมายเลขงาน:</strong><br>
                                    <span class="text-primary">{{ $jobLead->job->job_number ?? 'N/A' }}</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>ชื่องาน:</strong><br>
                                    {{ $jobLead->job->job_name ?? 'N/A' }}
                                </div>
                                <div class="col-md-4">
                                    <strong>ประเทศ:</strong><br>
                                    {{ $jobLead->job->country->country_name_th ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <strong>จำนวนเปิดรับ:</strong> {{ number_format($jobLead->job->job_total ?? 0) }} คน
                                </div>
                                <div class="col-md-4">
                                    <strong>สถานะงาน:</strong> 
                                    <span class="badge {{ ($jobLead->job->job_status ?? '') == 'เปิดรับสมัคร' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $jobLead->job->job_status ?? 'N/A' }}
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    <strong>บริษัท:</strong> {{ $jobLead->job->demand->dm_com_name ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('job-leads.update', $jobLead->job_lead_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="job_lead_number" class="form-label">หมายเลขใบสมัคร</label>
                                    <input type="text" class="form-control" id="job_lead_number" 
                                           value="{{ $jobLead->job_lead_number }}" readonly>
                                    <div class="form-text">หมายเลขใบสมัครไม่สามารถแก้ไขได้</div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="lead_id" class="form-label">Lead ID</label>
                                    <input type="text" class="form-control" id="lead_id" 
                                           value="{{ $jobLead->lead_id }}" readonly>
                                    <div class="form-text">Lead ID ไม่สามารถแก้ไขได้</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="job_lead_status" class="form-label">สถานะใบสมัคร <span class="text-danger">*</span></label>
                                    <select class="form-select @error('job_lead_status') is-invalid @enderror" 
                                            id="job_lead_status" name="job_lead_status" required>
                                        @foreach(['ร่าง', 'ส่งแล้ว', 'กำลังพิจารณา', 'นัดสัมภาษณ์', 'เสนองาน', 'ตอบรับ', 'ปฏิเสธ', 'ถอน'] as $status)
                                            <option value="{{ $status }}" 
                                                    {{ old('job_lead_status', $jobLead->job_lead_status) == $status ? 'selected' : '' }}>
                                                {{ $status }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('job_lead_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <small class="text-muted">
                                            <strong>ล็อคเมื่อ:</strong> ส่งแล้ว, กำลังพิจารณา, นัดสัมภาษณ์, เสนองาน, ตอบรับ<br>
                                            <strong>ปลดล็อคเมื่อ:</strong> ร่าง, ปฏิเสธ, ถอน
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">สถานะล็อค</label>
                                    <div class="form-control-plaintext">
                                        @if($jobLead->is_locked)
                                            <span class="badge bg-warning">
                                                <i class="bi bi-lock-fill"></i> ล็อค
                                            </span>
                                            @if($jobLead->locked_at)
                                                <small class="text-muted d-block">ล็อคเมื่อ: {{ $jobLead->locked_at->format('d/m/Y H:i') }}</small>
                                            @endif
                                        @else
                                            <span class="badge bg-success">
                                                <i class="bi bi-unlock"></i> ไม่ล็อค
                                            </span>
                                            @if($jobLead->unlocked_at)
                                                <small class="text-muted d-block">ปลดล็อคเมื่อ: {{ $jobLead->unlocked_at->format('d/m/Y H:i') }}</small>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="remarks" class="form-label">หมายเหตุ/เหตุผล</label>
                                    <textarea class="form-control @error('remarks') is-invalid @enderror" 
                                              id="remarks" name="remarks" rows="4"
                                              placeholder="บันทึกหมายเหตุเพิ่มเติม หรือเหตุผลการเปลี่ยนสถานะ...">{{ old('remarks', $jobLead->remarks) }}</textarea>
                                    @error('remarks')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">ระบบจะเพิ่มประวัติการเปลี่ยนสถานะอัตโนมัติ</div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Change History -->
                        @if($jobLead->remarks)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">ประวัติการเปลี่ยนแปลง</h6>
                                    </div>
                                    <div class="card-body">
                                        <pre class="bg-light p-3 rounded" style="white-space: pre-wrap; font-size: 0.9em;">{{ $jobLead->remarks }}</pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Creation Info -->
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body p-3">
                                        <h6 class="card-title">ข้อมูลการสร้าง</h6>
                                        <p class="mb-1"><strong>ผู้สร้าง:</strong> {{ $jobLead->createdBy->name ?? 'N/A' }}</p>
                                        <p class="mb-0"><strong>วันที่สร้าง:</strong> {{ $jobLead->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body p-3">
                                        <h6 class="card-title">ข้อมูลการอัปเดตล่าสุด</h6>
                                        <p class="mb-1"><strong>ผู้อัปเดต:</strong> {{ $jobLead->updatedBy->name ?? 'N/A' }}</p>
                                        <p class="mb-0"><strong>วันที่อัปเดต:</strong> {{ $jobLead->updated_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <div>
                                @if($jobLead->is_locked)
                                    @can('job-lead-force-unlock')
                                    <button type="button" class="btn btn-outline-danger" 
                                            onclick="forceUnlock({{ $jobLead->job_lead_id }})">
                                        <i class="bi bi-unlock"></i> ปลดล็อคบังคับ
                                    </button>
                                    @endcan
                                @endif
                            </div>
                            
                            <div>
                                <a href="{{ route('job-leads.index') }}" class="btn btn-secondary me-2">
                                    <i class="bi bi-x-circle"></i> ยกเลิก
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-save"></i> บันทึกการเปลี่ยนแปลง
                                </button>
                            </div>
                        </div>
                    </form>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('job_lead_status');
    
    // Show status change warning
    statusSelect.addEventListener('change', function() {
        const currentStatus = '{{ $jobLead->job_lead_status }}';
        const newStatus = this.value;
        
        if (currentStatus !== newStatus) {
            const lockedStatuses = ['ส่งแล้ว', 'กำลังพิจารณา', 'นัดสัมภาษณ์', 'เสนองาน', 'ตอบรับ'];
            const unlockedStatuses = ['ร่าง', 'ปฏิเสธ', 'ถอน'];
            
            let message = `เปลี่ยนสถานะจาก "${currentStatus}" เป็น "${newStatus}"`;
            
            if (lockedStatuses.includes(newStatus)) {
                message += '\n⚠️ สถานะนี้จะล็อคคนงานอัตโนมัติ';
            } else if (unlockedStatuses.includes(newStatus)) {
                message += '\n🔓 สถานะนี้จะปลดล็อคคนงานอัตโนมัติ';
            }
            
            // You could show a toast or alert here if needed
            console.log(message);
        }
    });
});

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