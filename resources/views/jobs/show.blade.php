@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">รายละเอียดงาน: {{ $job->job_number }}</h4>
                    <div>
                        @can('job-edit')
                        <a href="{{ route('jobs.edit', $job->job_id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> แก้ไข
                        </a>
                        @endcan
                        <a href="{{ route('jobs.index') }}" class="btn btn-secondary">
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
                    @if(session('warning'))
                        <div class="alert alert-warning">{{ session('warning') }}</div>
                    @endif

                    <div class="row">
                        <!-- Job Information -->
                        <div class="col-md-8">
                            <h5 class="border-bottom pb-2 mb-3">ข้อมูลงาน</h5>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>หมายเลขงาน:</strong><br>
                                    <span class="fs-5 text-primary">{{ $job->job_number }}</span>
                                </div>
                                <div class="col-md-6">
                                    <strong>สถานะ:</strong><br>
                                    <span class="badge fs-6 {{ $job->job_status == 'เปิดรับสมัคร' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $job->job_status }}
                                    </span>
                                    @if($job->is_expired)
                                        <span class="badge bg-warning">หมดอายุแล้ว</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <strong>ชื่องาน:</strong><br>
                                    <span class="fs-5">{{ $job->job_name }}</span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>ประเทศ:</strong><br>
                                    <i class="flag-icon flag-icon-{{ strtolower($job->country->country_code ?? 'xx') }}"></i>
                                    {{ $job->country->country_name_th ?? 'N/A' }}
                                    <br><small class="text-muted">{{ $job->country->country_name_en ?? '' }}</small>
                                </div>
                                <div class="col-md-6">
                                    <strong>บริษัทที่ขอ:</strong><br>
                                    {{ $job->demand->dm_com_name ?? 'N/A' }}
                                    <br><small class="text-muted">{{ $job->demand->dm_let_no ?? '' }}</small>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>จำนวนเปิดรับ:</strong><br>
                                    <span class="fs-5 text-info">{{ number_format($job->job_total) }} คน</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>วันเริ่มรับสมัคร:</strong><br>
                                    {{ $job->job_start_date->format('d/m/Y') }}
                                </div>
                                <div class="col-md-4">
                                    <strong>วันปิดรับสมัคร:</strong><br>
                                    @if($job->job_end_date)
                                        {{ $job->job_end_date->format('d/m/Y') }}
                                    @else
                                        <span class="text-info">รับสมัครต่อเนื่อง</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>ผู้สร้าง:</strong><br>
                                    {{ $job->createdBy->name ?? 'N/A' }}
                                    <br><small class="text-muted">{{ $job->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                                <div class="col-md-6">
                                    <strong>อัปเดตล่าสุด:</strong><br>
                                    {{ $job->updatedBy->name ?? 'N/A' }}
                                    <br><small class="text-muted">{{ $job->updated_at->format('d/m/Y H:i') }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Statistics -->
                        <div class="col-md-4">
                            <h5 class="border-bottom pb-2 mb-3">สถิติ</h5>
                            
                            <div class="card bg-light mb-3">
                                <div class="card-body text-center">
                                    <h2 class="text-primary mb-1">{{ number_format($stats['total_applications']) }}</h2>
                                    <p class="mb-0">ใบสมัครทั้งหมด</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="card bg-success text-white">
                                        <div class="card-body text-center p-2">
                                            <h4 class="mb-1">{{ number_format($stats['by_status']['ตอบรับ'] ?? 0) }}</h4>
                                            <small>ได้งานแล้ว</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card bg-info text-white">
                                        <div class="card-body text-center p-2">
                                            <h4 class="mb-1">{{ number_format($stats['remaining_positions']) }}</h4>
                                            <small>เหลือ</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h6 class="text-muted mb-2">สถานะใบสมัคร:</h6>
                            <div class="mb-3">
                                @foreach(['ร่าง', 'ส่งแล้ว', 'กำลังพิจารณา', 'นัดสัมภาษณ์', 'เสนองาน', 'ตอบรับ', 'ปฏิเสธ', 'ถอน'] as $status)
                                    @if(isset($stats['by_status'][$status]) && $stats['by_status'][$status] > 0)
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="small">{{ $status }}:</span>
                                        <span class="badge bg-secondary">{{ $stats['by_status'][$status] }}</span>
                                    </div>
                                    @endif
                                @endforeach
                            </div>

                            <div class="card bg-warning text-dark">
                                <div class="card-body text-center p-2">
                                    <h5 class="mb-1">{{ number_format($stats['locked_leads']) }}</h5>
                                    <small>คนงานที่ล็อค</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add Applicants Section -->
                    @if($job->remaining_positions > 0 && $job->job_status == 'เปิดรับสมัคร')
                        @can('job-lead-create')
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card border-success">
                                    <div class="card-header bg-success text-white">
                                        <h5 class="mb-0"><i class="bi bi-person-plus"></i> เพิ่มผู้สมัครในงานนี้</h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- Search Box -->
                                        <div class="row mb-3">
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <input type="text" id="leadSearch" class="form-control" 
                                                           placeholder="ค้นหาผู้สมัคร (ชื่อ, เลขหนังสือเดินทาง, ตำแหน่ง)">
                                                    <button type="button" id="searchLeadsBtn" class="btn btn-outline-primary">
                                                        <i class="bi bi-search"></i> ค้นหา
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <span class="text-muted small">เหลือ {{ $job->remaining_positions }} ตำแหน่ง</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Available Leads -->
                                        <div id="availableLeads" class="mb-3" style="max-height: 300px; overflow-y: auto; display: none;">
                                            <!-- Results will be loaded here -->
                                        </div>
                                        
                                        <!-- Selected Leads for Adding -->
                                        <div id="selectedLeads" style="display: none;">
                                            <h6>ผู้สมัครที่เลือก:</h6>
                                            <div id="selectedLeadsList" class="mb-3"></div>
                                            <button type="button" id="addSelectedLeads" class="btn btn-success" disabled>
                                                <i class="bi bi-check-circle"></i> เพิ่มผู้สมัครที่เลือก
                                            </button>
                                            <button type="button" id="clearSelection" class="btn btn-outline-secondary">
                                                <i class="bi bi-x-circle"></i> ยกเลิก
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endcan
                    @else
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="alert alert-warning">
                                    @if($job->remaining_positions <= 0)
                                        <i class="bi bi-exclamation-triangle"></i> งานเต็มแล้ว ไม่สามารถเพิ่มผู้สมัครได้
                                    @elseif($job->job_status != 'เปิดรับสมัคร')
                                        <i class="bi bi-lock"></i> งานปิดรับสมัครแล้ว
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="d-flex gap-2 flex-wrap">

                                @can('job-lead-list')
                                <a href="{{ route('job-leads.index', ['job_id' => $job->job_id]) }}" 
                                   class="btn btn-info">
                                    <i class="bi bi-list-ul"></i> ดูใบสมัครทั้งหมด
                                </a>
                                @endcan

                                @can('job-status-toggle')
                                <form action="{{ route('jobs.toggle-status', $job->job_id) }}" 
                                      method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-outline-secondary">
                                        <i class="bi bi-toggle-{{ $job->job_status == 'เปิดรับสมัคร' ? 'on' : 'off' }}"></i>
                                        {{ $job->job_status == 'เปิดรับสมัคร' ? 'ปิดรับสมัคร' : 'เปิดรับสมัคร' }}
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </div>
                    </div>

                    <!-- Current Applicants Management -->
                    @if($job->jobLeads->count() > 0)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0"><i class="bi bi-people"></i> ผู้สมัครในงานนี้ ({{ $job->jobLeads->count() }} คน)</h5>
                                    @can('job-lead-delete')
                                    <small class="text-muted">สามารถยกเลิกได้เฉพาะสถานะ "ร่าง" และ "ส่งแล้ว"</small>
                                    @endcan
                                </div>
                                <div class="card-body">
                                    <!-- Statistics -->
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <h4 class="text-primary mb-0">{{ $job->jobLeads->count() }}</h4>
                                                <small class="text-muted">ผู้สมัครทั้งหมด</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <h4 class="text-success mb-0">{{ $job->jobLeads->where('job_lead_status', 'ตอบรับ')->count() }}</h4>
                                                <small class="text-muted">ได้งานแล้ว</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <h4 class="text-warning mb-0">{{ $job->jobLeads->where('is_locked', true)->count() }}</h4>
                                                <small class="text-muted">ล็อคอยู่</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <h4 class="text-info mb-0">{{ $job->remaining_positions }}</h4>
                                                <small class="text-muted">ตำแหน่งที่เหลือ</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Job Leads -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5 class="border-bottom pb-2 mb-3">รายการใบสมัคร</h5>
                            
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>หมายเลขใบสมัคร</th>
                                            <th>ชื่อผู้สมัคร</th>
                                            <th>Passport</th>
                                            <th>สถานะ</th>
                                            <th>Lock</th>
                                            <th>วันที่สร้าง</th>
                                            <th>ผู้สร้าง</th>
                                            <th width="100">การดำเนินการ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($job->jobLeads->take(10) as $jobLead)
                                        <tr>
                                            <td>
                                                <a href="{{ route('job-leads.show', $jobLead->job_lead_id) }}" 
                                                   class="text-decoration-none">
                                                    {{ $jobLead->job_lead_number }}
                                                </a>
                                            </td>
                                            <td>
                                                <strong>{{ $jobLead->lead ? $jobLead->lead->getFullNameAttribute() : 'ไม่พบข้อมูล' }}</strong>
                                                @if($jobLead->lead && $jobLead->lead->lead_phone)
                                                    <br><small class="text-muted">{{ $jobLead->lead->lead_phone }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <code>{{ $jobLead->lead ? ($jobLead->lead->lead_passport_number ?: 'ไม่มี') : 'ไม่พบข้อมูล' }}</code>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $jobLead->status_badge_color }}">
                                                    {{ $jobLead->job_lead_status }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($jobLead->is_locked)
                                                    <i class="bi bi-lock-fill text-danger" title="ล็อค"></i>
                                                @else
                                                    <i class="bi bi-unlock text-success" title="ไม่ล็อค"></i>
                                                @endif
                                            </td>
                                            <td>{{ $jobLead->created_at->format('d/m/Y') }}</td>
                                            <td>{{ $jobLead->createdBy->name ?? 'N/A' }}</td>
                                            <td>
                                                @can('job-lead-delete')
                                                    @if($jobLead->job_lead_status == 'ร่าง' || $jobLead->job_lead_status == 'ส่งแล้ว')
                                                    <button type="button" class="btn btn-danger btn-sm" 
                                                            onclick="cancelApplication({{ $jobLead->job_lead_id }}, '{{ $jobLead->job_lead_number }}')"
                                                            title="ยกเลิกใบสมัคร">
                                                        <i class="bi bi-x-circle"></i>
                                                    </button>
                                                    @else
                                                    <span class="text-muted small">ไม่สามารถยกเลิกได้</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted small">ไม่มีสิทธิ์</span>
                                                @endcan
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            @if($job->jobLeads->count() > 10)
                            <div class="text-center mt-2">
                                <a href="{{ route('job-leads.index', ['job_id' => $job->job_id]) }}" 
                                   class="btn btn-outline-primary btn-sm">
                                    ดูทั้งหมด ({{ $job->jobLeads->count() }} รายการ)
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
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
document.addEventListener('DOMContentLoaded', function() {
    const leadSearch = document.getElementById('leadSearch');
    const searchLeadsBtn = document.getElementById('searchLeadsBtn');
    const availableLeads = document.getElementById('availableLeads');
    const selectedLeadsSection = document.getElementById('selectedLeads');
    const selectedLeadsList = document.getElementById('selectedLeadsList');
    const addSelectedLeads = document.getElementById('addSelectedLeads');
    const clearSelection = document.getElementById('clearSelection');
    
    let selectedLeads = [];
    let availableLeadsData = [];
    const maxSelections = {{ $job->remaining_positions }};
    const jobId = {{ $job->job_id }};
    
    // Search functionality
    if (searchLeadsBtn) {
        searchLeadsBtn.addEventListener('click', searchLeads);
        leadSearch.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchLeads();
            }
        });
    }
    
    function searchLeads() {
        const search = leadSearch.value.trim();
        
        if (search.length < 2) {
            alert('กรุณากรอกคำค้นหาอย่างน้อย 2 ตัวอักษร');
            return;
        }
        
        availableLeads.style.display = 'block';
        availableLeads.innerHTML = '<div class="text-center py-3"><div class="spinner-border" role="status"></div><p class="mt-2">กำลังค้นหา...</p></div>';
        
        const url = `{{ route('job-leads.search-available') }}?job_id=${jobId}&search=${encodeURIComponent(search)}`;
        
        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            availableLeadsData = data;
            displayLeads();
        })
        .catch(error => {
            console.error('Error:', error);
            availableLeads.innerHTML = '<div class="alert alert-danger">เกิดข้อผิดพลาดในการค้นหา</div>';
        });
    }
    
    function displayLeads() {
        if (availableLeadsData.length === 0) {
            availableLeads.innerHTML = '<div class="text-center text-muted py-3"><i class="bi bi-person-x fs-1"></i><p>ไม่พบผู้สมัครที่ค้นหา</p></div>';
            return;
        }
        
        let html = '<div class="row">';
        availableLeadsData.forEach(lead => {
            const isSelected = selectedLeads.find(s => s.id === lead.id);
            const isDisabled = lead.is_locked || lead.already_applied;
            
            html += `
                <div class="col-md-6 mb-2">
                    <div class="card ${isDisabled ? 'bg-light' : 'bg-white'} ${isSelected ? 'border-success' : ''}">
                        <div class="card-body p-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="flex-grow-1">
                                    <strong class="small">${lead.name}</strong>
                                    <br><span class="text-muted small">${lead.passport} | ${lead.position}</span>
                                    ${lead.is_locked ? '<br><span class="text-warning small">🔒 ล็อค</span>' : ''}
                                    ${lead.already_applied ? '<br><span class="text-success small">✅ เคยส่งแล้ว</span>' : ''}
                                </div>
                                <div>
                                    ${!isDisabled && !isSelected ? `
                                        <button type="button" class="btn btn-sm btn-success" onclick="selectLead(${lead.id})">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    ` : isSelected ? `
                                        <button type="button" class="btn btn-sm btn-danger" onclick="unselectLead(${lead.id})">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                    ` : `
                                        <span class="text-muted small">ไม่สามารถเลือกได้</span>
                                    `}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        
        availableLeads.innerHTML = html;
    }
    
    window.selectLead = function(leadId) {
        if (selectedLeads.length >= maxSelections) {
            alert(`สามารถเลือกได้สูงสุด ${maxSelections} คน`);
            return;
        }
        
        const lead = availableLeadsData.find(l => l.id === leadId);
        if (lead && !selectedLeads.find(s => s.id === leadId)) {
            selectedLeads.push(lead);
            updateSelectedLeads();
            displayLeads();
        }
    };
    
    window.unselectLead = function(leadId) {
        selectedLeads = selectedLeads.filter(l => l.id !== leadId);
        updateSelectedLeads();
        displayLeads();
    };
    
    function updateSelectedLeads() {
        if (selectedLeads.length === 0) {
            selectedLeadsSection.style.display = 'none';
            return;
        }
        
        selectedLeadsSection.style.display = 'block';
        
        let html = '<div class="row">';
        selectedLeads.forEach(lead => {
            html += `
                <div class="col-md-4 mb-2">
                    <div class="badge bg-success p-2 w-100 text-start">
                        ${lead.name}
                        <button type="button" class="btn-close btn-close-white ms-1" onclick="unselectLead(${lead.id})" style="font-size: 0.8em;"></button>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        
        selectedLeadsList.innerHTML = html;
        addSelectedLeads.disabled = selectedLeads.length === 0;
    }
    
    // Add selected leads
    if (addSelectedLeads) {
        addSelectedLeads.addEventListener('click', function() {
            if (selectedLeads.length === 0) return;
            
            const leadIds = selectedLeads.map(l => l.id);
            
            // Create form data
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            formData.append('job_id', jobId);
            leadIds.forEach(id => formData.append('leads[]', id));
            
            // Show loading
            addSelectedLeads.disabled = true;
            addSelectedLeads.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>กำลังเพิ่ม...';
            
            // Submit
            fetch('{{ route("job-leads.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (response.ok) {
                    location.reload(); // Refresh to show new applications
                } else {
                    throw new Error('Network response was not ok');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('เกิดข้อผิดพลาดในการเพิ่มผู้สมัคร');
                addSelectedLeads.disabled = false;
                addSelectedLeads.innerHTML = '<i class="bi bi-check-circle"></i> เพิ่มผู้สมัครที่เลือก';
            });
        });
    }
    
    // Clear selection
    if (clearSelection) {
        clearSelection.addEventListener('click', function() {
            selectedLeads = [];
            updateSelectedLeads();
            displayLeads();
        });
    }
});

// Cancel application function
function cancelApplication(jobLeadId, jobLeadNumber) {
    if (confirm(`คุณต้องการยกเลิกใบสมัคร "${jobLeadNumber}" หรือไม่?\n\nการยกเลิกจะปลดล็อคคนงานและลบใบสมัครออกจากระบบ`)) {
        
        // Show loading state
        const btn = event.target.closest('button');
        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        
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
                // Show success message and reload
                alert(data.success);
                location.reload();
            } else if (data.error) {
                alert(data.error);
                // Restore button state
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('เกิดข้อผิดพลาดในการยกเลิกใบสมัคร');
            
            // Restore button state
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        });
    }
}
</script>
@endsection