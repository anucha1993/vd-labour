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

                    <!-- Job Overview -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="120"><strong>งาน:</strong></td>
                                        <td>{{ $job->job_name }} 
                                            <span class="badge {{ $job->job_status == 'เปิดรับสมัคร' ? 'bg-success' : 'bg-secondary' }}">{{ $job->job_status }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>บริษัท:</strong></td>
                                        <td>{{ $job->demand->dm_com_name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>ประเทศ:</strong></td>
                                        <td>{{ $job->country->country_name_th ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>จำนวนรับ:</strong></td>
                                        <td><span class="text-info fw-bold">{{ number_format($job->job_total) }} คน</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border rounded p-2">
                                        <h4 class="text-success mb-0">{{ number_format($stats['by_status']['ตอบรับ'] ?? 0) }}</h4>
                                        <small class="text-muted">ได้งาน</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="border rounded p-2">
                                        <h4 class="text-primary mb-0">{{ number_format($stats['remaining_positions']) }}</h4>
                                        <small class="text-muted">เหลือ</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Demand Details -->
                    @if($job->demand)
                    <div class="accordion mb-4" id="demandAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#demandDetails" aria-expanded="true">
                                    <i class="bi bi-info-circle me-2"></i>รายละเอียด Demand
                                </button>
                            </h2>
                            <div id="demandDetails" class="accordion-collapse collapse show" data-bs-parent="#demandAccordion">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            @if($job->demand->dm_job)
                                            <div class="mb-3">
                                                <strong>รายละเอียดงาน:</strong>
                                                <div class="small text-muted mt-1">{!! nl2br(e($job->demand->dm_job)) !!}</div>
                                            </div>
                                            @endif
                                            
                                            @if($job->demand->dm_sa)
                                            <div class="mb-3">
                                                <strong>เงินเดือน/สวัสดิการ:</strong>
                                                <div class="small text-success mt-1">{!! nl2br(e($job->demand->dm_sa)) !!}</div>
                                            </div>
                                            @endif
                                        </div>
                                        
                                        <div class="col-md-6">
                                            @if($job->demand->dm_time_work)
                                            <div class="mb-3">
                                                <strong>เวลาทำงาน:</strong>
                                                <div class="small text-muted mt-1">{{ $job->demand->dm_time_work }}</div>
                                            </div>
                                            @endif
                                            
                                            @if($job->demand->dm_exp && is_array($job->demand->dm_exp) && count($job->demand->dm_exp) > 0)
                                            <div class="mb-3">
                                                <strong>สวัสดิการ:</strong>
                                                <div class="mt-1">
                                                    @foreach($job->demand->dm_exp as $benefit)
                                                        @php
                                                            $benefitMap = ['accomm' => 'ที่พัก', 'food' => 'อาหาร', 'med' => 'รักษาพยาบาล', 'shuttle' => 'รถรับส่ง'];
                                                        @endphp
                                                        <div class="d-flex align-items-center mb-1">
                                                            <i class="bi bi-check-circle text-success me-2"></i>
                                                            <span>{{ $benefitMap[$benefit] ?? $benefit }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

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

                    <!-- Job Applicants -->
                    @if($job->jobLeads->count() > 0)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0"><i class="bi bi-people me-2"></i>ผู้สมัครในงาน ({{ $job->jobLeads->count() }} คน)</h5>
                                <a href="{{ route('job-leads.job-applicants', $job->job_id) }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-list-ul me-1"></i>จัดการทั้งหมด
                                </a>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ใบสมัคร</th>
                                            <th>ชื่อ-สกุล</th>
                                            <th>สถานะ</th>
                                            <th>วันที่สมัคร</th>
                                            <th width="80">จัดการ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($job->jobLeads->take(8) as $jobLead)
                                        <tr>
                                            <td>
                                                <a href="{{ route('job-leads.show', $jobLead->job_lead_id) }}" class="text-decoration-none fw-bold">
                                                    {{ $jobLead->job_lead_number }}
                                                </a>
                                                @if($jobLead->is_locked)
                                                    <i class="bi bi-lock-fill text-warning ms-1" title="ล็อค"></i>
                                                @endif
                                            </td>
                                            <td>{{ $jobLead->lead ? $jobLead->lead->getFullNameAttribute() : 'ไม่พบข้อมูล' }}</td>
                                            <td><span class="badge bg-{{ $jobLead->status_badge_color }}">{{ $jobLead->job_lead_status }}</span></td>
                                            <td>{{ $jobLead->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                @can('job-lead-delete')
                                                    @if(in_array($jobLead->job_lead_status, ['ร่าง', 'ส่งแล้ว']))
                                                    <button type="button" class="btn btn-outline-danger btn-sm" 
                                                            onclick="cancelApplication({{ $jobLead->job_lead_id }}, '{{ $jobLead->job_lead_number }}')"
                                                            title="ยกเลิก">
                                                        <i class="bi bi-x"></i>
                                                    </button>
                                                    @endif
                                                @endcan
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
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