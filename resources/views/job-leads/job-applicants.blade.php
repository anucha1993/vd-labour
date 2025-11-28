@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <!-- Job Header -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0"><i class="bi bi-people"></i> ผู้สมัครงาน: {{ $job->job_name }}</h4>
                            <small>{{ $job->job_number }} | {{ $job->country->country_name_th ?? 'ไม่ระบุประเทศ' }}</small>
                        </div>
                        <a href="{{ route('job-leads.index') }}" class="btn btn-light">
                            <i class="bi bi-arrow-left"></i> กลับไปเลือกงาน
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Statistics -->
                    <div class="row text-center">
                        <div class="col-md-2">
                            <h3 class="text-primary mb-0">{{ $stats['total'] }}</h3>
                            <small class="text-muted">ผู้สมัครทั้งหมด</small>
                        </div>
                        <div class="col-md-2">
                            <h3 class="text-success mb-0">{{ $stats['by_status']['ตอบรับ'] ?? 0 }}</h3>
                            <small class="text-muted">ได้งานแล้ว</small>
                        </div>
                        <div class="col-md-2">
                            <h3 class="text-info mb-0">{{ $job->remaining_positions }}</h3>
                            <small class="text-muted">ตำแหน่งเหลือ</small>
                        </div>
                        <div class="col-md-2">
                            <h3 class="text-warning mb-0">{{ $stats['locked'] }}</h3>
                            <small class="text-muted">ล็อคอยู่</small>
                        </div>
                        <div class="col-md-2">
                            <h3 class="text-secondary mb-0">{{ $stats['by_status']['ร่าง'] ?? 0 }}</h3>
                            <small class="text-muted">ร่าง</small>
                        </div>
                        <div class="col-md-2">
                            <h3 class="text-primary mb-0">{{ $stats['by_status']['ส่งแล้ว'] ?? 0 }}</h3>
                            <small class="text-muted">ส่งแล้ว</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Management Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">จัดการผู้สมัคร</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if(session('warning'))
                        <div class="alert alert-warning">{{ session('warning') }}</div>
                    @endif

                    <!-- Search and Filters -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <form method="GET" class="d-flex">
                                <input type="hidden" name="job_id" value="{{ $job->job_id }}">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="ค้นหาผู้สมัคร (ชื่อ, Passport, เบอร์โทร)" 
                                       value="{{ request('search') }}">
                                <button type="submit" class="btn btn-outline-primary ms-2">
                                    <i class="bi bi-search"></i>
                                </button>
                            </form>
                        </div>
                        <div class="col-md-3">
                            <form method="GET" class="d-flex">
                                <input type="hidden" name="job_id" value="{{ $job->job_id }}">
                                @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif
                                <select name="job_lead_status" class="form-select" onchange="this.form.submit()">
                                    <option value="">-- ทุกสถานะ --</option>
                                    @foreach(['ร่าง', 'ส่งแล้ว', 'กำลังพิจารณา', 'นัดสัมภาษณ์', 'เสนองาน', 'ตอบรับ', 'ปฏิเสธ', 'ถอน'] as $status)
                                        <option value="{{ $status }}" {{ request('job_lead_status') == $status ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                        <div class="col-md-2">
                            <form method="GET">
                                <input type="hidden" name="job_id" value="{{ $job->job_id }}">
                                @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif
                                @if(request('job_lead_status'))
                                <input type="hidden" name="job_lead_status" value="{{ request('job_lead_status') }}">
                                @endif
                                <select name="is_locked" class="form-select" onchange="this.form.submit()">
                                    <option value="">-- ล็อค/ปลดล็อค --</option>
                                    <option value="1" {{ request('is_locked') == '1' ? 'selected' : '' }}>ล็อคอยู่</option>
                                    <option value="0" {{ request('is_locked') == '0' ? 'selected' : '' }}>ไม่ล็อค</option>
                                </select>
                            </form>
                        </div>
                        <div class="col-md-3 text-end">
                            @if(request()->hasAny(['search', 'job_lead_status', 'is_locked']))
                            <a href="{{ route('job-leads.job-applicants', $job->job_id) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> ล้างตัวกรอง
                            </a>
                            @endif
                        </div>
                    </div>

                    <!-- Mass Update Form -->
                    @can('job-lead-bulk-update')
                    <div class="card border-warning mb-4">
                        <div class="card-header bg-warning bg-opacity-10">
                            <h6 class="mb-0"><i class="bi bi-lightning"></i> อัปเดตสถานะหลายรายการ</h6>
                        </div>
                        <div class="card-body">
                            <form id="massUpdateForm" method="POST" action="{{ route('job-leads.bulk-update') }}">
                                @csrf
                                @method('PATCH')
                                <div class="row align-items-end">
                                    <div class="col-md-3">
                                        <label class="form-label small">สถานะใหม่:</label>
                                        <select name="new_status" class="form-select" required>
                                            <option value="">-- เลือกสถานะ --</option>
                                            @foreach(['ร่าง', 'ส่งแล้ว', 'กำลังพิจารณา', 'นัดสัมภาษณ์', 'เสนองาน', 'ตอบรับ', 'ปฏิเสธ', 'ถอน'] as $status)
                                                <option value="{{ $status }}">{{ $status }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small">หมายเหตุ (ถ้ามี):</label>
                                        <input type="text" name="remarks" class="form-control" placeholder="เหตุผลในการเปลี่ยนสถานะ">
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-warning" id="massUpdateBtn" disabled>
                                            <i class="bi bi-lightning"></i> อัปเดตที่เลือก (<span id="selectedCount">0</span>)
                                        </button>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-outline-secondary" id="selectAllBtn">
                                            <i class="bi bi-check-all"></i> เลือกทั้งหมด
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endcan

                    <!-- Applicants Table -->
                    @if($jobLeads->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    @can('job-lead-bulk-update')
                                    <th width="30">
                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                    </th>
                                    @endcan
                                    <th>หมายเลขใบสมัคร</th>
                                    <th>ชื่อผู้สมัคร</th>
                                    <th>Passport</th>
                                    <th>เบอร์โทร</th>
                                    <th>สถานะ</th>
                                    <th>ล็อค</th>
                                    <th>วันที่สมัคร</th>
                                    <th width="100">การดำเนินการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jobLeads as $jobLead)
                                <tr>
                                    @can('job-lead-bulk-update')
                                    <td>
                                        <input type="checkbox" name="job_lead_ids[]" 
                                               value="{{ $jobLead->job_lead_id }}" 
                                               class="form-check-input applicant-checkbox">
                                    </td>
                                    @endcan
                                    <td>
                                        <a href="{{ route('job-leads.show', $jobLead->job_lead_id) }}" 
                                           class="text-decoration-none fw-bold">
                                            {{ $jobLead->job_lead_number }}
                                        </a>
                                    </td>
                                    <td>
                                        <strong>{{ $jobLead->lead ? $jobLead->lead->getFullNameAttribute() : 'ไม่พบข้อมูล' }}</strong>
                                        @if($jobLead->lead && $jobLead->lead->lead_age)
                                            <br><small class="text-muted">อายุ {{ $jobLead->lead->lead_age }} ปี</small>
                                        @endif
                                    </td>
                                    <td>
                                        <code>{{ $jobLead->lead ? ($jobLead->lead->lead_passport_number ?: 'ไม่มี') : 'ไม่พบข้อมูล' }}</code>
                                    </td>
                                    <td>
                                        {{ $jobLead->lead ? ($jobLead->lead->lead_phone ?: '-') : '-' }}
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $jobLead->status_badge_color }} fs-6">
                                            {{ $jobLead->job_lead_status }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($jobLead->is_locked)
                                            <i class="bi bi-lock-fill text-danger fs-5" title="ล็อคอยู่"></i>
                                        @else
                                            <i class="bi bi-unlock text-success fs-5" title="ไม่ล็อค"></i>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $jobLead->created_at->format('d/m/Y') }}
                                        <br><small class="text-muted">{{ $jobLead->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        @can('job-lead-edit')
                                        <a href="{{ route('job-leads.edit', $jobLead->job_lead_id) }}" 
                                           class="btn btn-sm btn-outline-primary" title="แก้ไข">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @endcan
                                        
                                        @can('job-lead-delete')
                                            @if(in_array($jobLead->job_lead_status, ['ร่าง', 'ส่งแล้ว']))
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="cancelApplication({{ $jobLead->job_lead_id }}, '{{ $jobLead->job_lead_number }}')"
                                                    title="ยกเลิกใบสมัคร">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                            @endif
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $jobLeads->appends(request()->query())->links() }}
                    </div>
                    @else
                    <!-- No Applicants -->
                    <div class="text-center py-5">
                        <i class="bi bi-person-x fs-1 text-muted"></i>
                        <h4 class="text-muted mt-3">ไม่พบผู้สมัครในงานนี้</h4>
                        <p class="text-muted">
                            @if(request()->hasAny(['search', 'job_lead_status', 'is_locked']))
                                ไม่พบผู้สมัครที่ตรงกับเงื่อนไขที่ค้นหา
                            @else
                                ยังไม่มีผู้สมัครในงานนี้
                            @endif
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const applicantCheckboxes = document.querySelectorAll('.applicant-checkbox');
    const massUpdateBtn = document.getElementById('massUpdateBtn');
    const selectedCountSpan = document.getElementById('selectedCount');
    const selectAllBtn = document.getElementById('selectAllBtn');
    
    // Select All functionality
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            applicantCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectedCount();
        });
    }
    
    // Individual checkbox change
    applicantCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });
    
    // Select All button
    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', function() {
            applicantCheckboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
            if (selectAllCheckbox) selectAllCheckbox.checked = true;
            updateSelectedCount();
        });
    }
    
    function updateSelectedCount() {
        const selectedCount = document.querySelectorAll('.applicant-checkbox:checked').length;
        if (selectedCountSpan) selectedCountSpan.textContent = selectedCount;
        if (massUpdateBtn) massUpdateBtn.disabled = selectedCount === 0;
        
        // Update select all checkbox state
        if (selectAllCheckbox) {
            const allChecked = selectedCount === applicantCheckboxes.length && selectedCount > 0;
            selectAllCheckbox.checked = allChecked;
        }
    }
    
    // Mass update form submission
    const massUpdateForm = document.getElementById('massUpdateForm');
    if (massUpdateForm) {
        massUpdateForm.addEventListener('submit', function(e) {
            const selectedCount = document.querySelectorAll('.applicant-checkbox:checked').length;
            if (selectedCount === 0) {
                e.preventDefault();
                alert('กรุณาเลือกผู้สมัครอย่างน้อย 1 คน');
                return;
            }
            
            const newStatus = this.querySelector('[name="new_status"]').value;
            if (!newStatus) {
                e.preventDefault();
                alert('กรุณาเลือกสถานะใหม่');
                return;
            }
            
            if (!confirm(`คุณต้องการเปลี่ยนสถานะของผู้สมัคร ${selectedCount} คน เป็น "${newStatus}" หรือไม่?`)) {
                e.preventDefault();
            }
        });
    }
});

// Cancel application function  
function cancelApplication(jobLeadId, jobLeadNumber) {
    if (confirm(`คุณต้องการยกเลิกใบสมัคร "${jobLeadNumber}" หรือไม่?\n\nการยกเลิกจะปลดล็อคคนงานและลบใบสมัครออกจากระบบ`)) {
        fetch(`{{ url('job-leads') }}/${jobLeadId}/cancel`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.success);
                location.reload();
            } else if (data.error) {
                alert(data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('เกิดข้อผิดพลาดในการยกเลิกใบสมัคร');
        });
    }
}
</script>
@endsection