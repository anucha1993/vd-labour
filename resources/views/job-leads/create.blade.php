@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">เพิ่มใบสมัครงานใหม่</h4>
                    <a href="{{ route('job-leads.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> กลับ
                    </a>
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

                    <form action="{{ route('job-leads.store') }}" method="POST" id="jobLeadForm">
                        @csrf
                        
                        <!-- Job Selection -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="job_id" class="form-label">เลือกงาน <span class="text-danger">*</span></label>
                                    <select class="form-select @error('job_id') is-invalid @enderror" 
                                            id="job_id" name="job_id" required>
                                        <option value="">-- เลือกงาน --</option>
                                        @foreach($jobs as $jobOption)
                                            <option value="{{ $jobOption->job_id }}" 
                                                    {{ old('job_id', $job?->job_id) == $jobOption->job_id ? 'selected' : '' }}
                                                    data-country="{{ $jobOption->country->country_name_th ?? '' }}"
                                                    data-company="{{ $jobOption->demand->dm_com_name ?? '' }}"
                                                    data-total="{{ $jobOption->job_total }}"
                                                    data-remaining="{{ $jobOption->remaining_positions }}">
                                                {{ $jobOption->job_number }} - {{ $jobOption->job_name }}
                                                (เหลือ {{ $jobOption->remaining_positions }} คน)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('job_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Job Info Display -->
                        <div id="jobInfo" class="card bg-light mb-4" style="{{ old('job_id', $job?->job_id) ? '' : 'display: none;' }}">
                            <div class="card-body">
                                <h6 class="card-title text-primary">ข้อมูลงาน</h6>
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong>ประเทศ:</strong><br>
                                        <span id="jobCountry">{{ $job->country->country_name_th ?? '' }}</span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>บริษัท:</strong><br>
                                        <span id="jobCompany">{{ $job->demand->dm_com_name ?? '' }}</span>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>จำนวนรับ:</strong><br>
                                        <span id="jobTotal">{{ $job->job_total ?? '' }}</span> คน
                                    </div>
                                    <div class="col-md-3">
                                        <strong>ตำแหน่งที่เหลือ:</strong><br>
                                        <span id="jobRemaining" class="text-info fw-bold">{{ $job->remaining_positions ?? '' }}</span> คน
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Lead Selection -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">เลือกคนงาน <span class="text-danger">*</span></label>
                                    
                                    <!-- Search Box -->
                                    <div class="mb-3">
                                        <div class="input-group">
                                            <input type="text" id="leadSearch" class="form-control" 
                                                   placeholder="ค้นหาคนงาน (ชื่อ, เลขที่หนังสือเดินทาง, ตำแหน่ง)">
                                            <button type="button" id="searchBtn" class="btn btn-outline-secondary">
                                                <i class="bi bi-search"></i> ค้นหา
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Available Leads -->
                                    <div id="availableLeads" class="border rounded p-3" style="max-height: 400px; overflow-y: auto;">
                                        <div class="text-center text-muted py-4">
                                            <i class="bi bi-person-plus fs-1"></i>
                                            <p>กรุณาเลือกงานและค้นหาคนงาน</p>
                                        </div>
                                    </div>

                                    <!-- Selected Leads -->
                                    <div class="mt-3">
                                        <h6>คนงานที่เลือก:</h6>
                                        <div id="selectedLeads" class="border rounded p-3 bg-light">
                                            <p class="text-muted mb-0">ยังไม่ได้เลือกคนงาน</p>
                                        </div>
                                    </div>

                                    @error('leads')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Hidden inputs for selected leads -->
                        <div id="hiddenLeads"></div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('job-leads.index') }}" class="btn btn-secondary me-2">
                                <i class="bi bi-x-circle"></i> ยกเลิก
                            </a>
                            <button type="submit" class="btn btn-success" id="submitBtn" disabled>
                                <i class="bi bi-save"></i> สร้างใบสมัคร
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const jobSelect = document.getElementById('job_id');
    const jobInfo = document.getElementById('jobInfo');
    const leadSearch = document.getElementById('leadSearch');
    const searchBtn = document.getElementById('searchBtn');
    const availableLeads = document.getElementById('availableLeads');
    const selectedLeads = document.getElementById('selectedLeads');
    const hiddenLeads = document.getElementById('hiddenLeads');
    const submitBtn = document.getElementById('submitBtn');
    
    let selectedLeadIds = [];
    let availableLeadsData = [];
    
    // Job selection change
    jobSelect.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        
        if (this.value) {
            // Show job info
            document.getElementById('jobCountry').textContent = option.dataset.country;
            document.getElementById('jobCompany').textContent = option.dataset.company;
            document.getElementById('jobTotal').textContent = option.dataset.total;
            document.getElementById('jobRemaining').textContent = option.dataset.remaining;
            jobInfo.style.display = 'block';
            
            // Check if job is full
            const remaining = parseInt(option.dataset.remaining);
            if (remaining <= 0) {
                availableLeads.innerHTML = `
                    <div class="text-center text-warning py-4">
                        <i class="bi bi-exclamation-triangle fs-1"></i>
                        <p>งานนี้เต็มแล้ว ไม่สามารถเพิ่มใบสมัครได้</p>
                    </div>
                `;
                return;
            }
            
            // Load available leads
            loadAvailableLeads();
        } else {
            jobInfo.style.display = 'none';
            availableLeads.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="bi bi-person-plus fs-1"></i>
                    <p>กรุณาเลือกงานและค้นหาคนงาน</p>
                </div>
            `;
        }
        
        // Reset selections
        selectedLeadIds = [];
        updateSelectedLeads();
    });
    
    // Search leads
    searchBtn.addEventListener('click', loadAvailableLeads);
    leadSearch.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            loadAvailableLeads();
        }
    });
    
    function loadAvailableLeads() {
        const jobId = jobSelect.value;
        const search = leadSearch.value;
        
        if (!jobId) {
            alert('กรุณาเลือกงานก่อน');
            return;
        }
        
        availableLeads.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">กำลังค้นหาคนงาน...</p>
            </div>
        `;
        
        // Call actual API
        const baseUrl = '{{ route("job-leads.search-available") }}';
        const url = `${baseUrl}?job_id=${jobId}&search=${encodeURIComponent(search)}`;
        console.log('Fetching:', url); // Debug
        
        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            availableLeadsData = data;
            displayAvailableLeads();
        })
        .catch(error => {
            console.error('Error:', error);
            availableLeads.innerHTML = `
                <div class="text-center text-danger py-4">
                    <i class="bi bi-exclamation-triangle fs-1"></i>
                    <p>เกิดข้อผิดพลาดในการโหลดข้อมูล</p>
                    <button class="btn btn-outline-primary btn-sm" onclick="loadAvailableLeads()">
                        <i class="bi bi-arrow-clockwise"></i> ลองใหม่
                    </button>
                </div>
            `;
        });
    }
    
    function displayAvailableLeads() {
        if (availableLeadsData.length === 0) {
            availableLeads.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="bi bi-person-x fs-1"></i>
                    <p>ไม่พบคนงานที่ค้นหา</p>
                </div>
            `;
            return;
        }
        
        let html = '';
        availableLeadsData.forEach(lead => {
            const isSelected = selectedLeadIds.includes(lead.id);
            const isDisabled = lead.is_locked || lead.already_applied;
            
            html += `
                <div class="border rounded p-3 mb-2 ${isDisabled ? 'bg-light' : 'bg-white'} ${isSelected ? 'border-primary' : ''}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${lead.name}</strong>
                            <br><small class="text-muted">
                                ${lead.passport} | ${lead.position}
                                ${lead.is_locked ? ' | 🔒 ล็อค' : ''}
                                ${lead.already_applied ? ' | ✅ เคยส่งแล้ว' : ''}
                            </small>
                        </div>
                        <div>
                            ${!isDisabled ? `
                                <button type="button" class="btn btn-sm ${isSelected ? 'btn-danger' : 'btn-primary'}" 
                                        onclick="${isSelected ? 'removeLead' : 'addLead'}(${lead.id})">
                                    ${isSelected ? '<i class="bi bi-dash"></i> ลบ' : '<i class="bi bi-plus"></i> เลือก'}
                                </button>
                            ` : `
                                <span class="text-muted small">ไม่สามารถเลือกได้</span>
                            `}
                        </div>
                    </div>
                </div>
            `;
        });
        
        availableLeads.innerHTML = html;
    }
    
    window.addLead = function(leadId) {
        if (!selectedLeadIds.includes(leadId)) {
            const jobOption = jobSelect.options[jobSelect.selectedIndex];
            const remaining = parseInt(jobOption.dataset.remaining);
            
            if (selectedLeadIds.length >= remaining) {
                alert(`สามารถเลือกได้สูงสุด ${remaining} คน`);
                return;
            }
            
            selectedLeadIds.push(leadId);
            updateSelectedLeads();
            displayAvailableLeads();
        }
    };
    
    window.removeLead = function(leadId) {
        const index = selectedLeadIds.indexOf(leadId);
        if (index > -1) {
            selectedLeadIds.splice(index, 1);
            updateSelectedLeads();
            displayAvailableLeads();
        }
    };
    
    function updateSelectedLeads() {
        // Update display
        if (selectedLeadIds.length === 0) {
            selectedLeads.innerHTML = '<p class="text-muted mb-0">ยังไม่ได้เลือกคนงาน</p>';
        } else {
            let html = '<div class="row">';
            selectedLeadIds.forEach(leadId => {
                const lead = availableLeadsData.find(l => l.id === leadId);
                if (lead) {
                    html += `
                        <div class="col-md-6 mb-2">
                            <div class="badge bg-primary p-2 w-100 text-start">
                                ${lead.name} (${lead.passport})
                                <button type="button" class="btn-close btn-close-white ms-2" 
                                        onclick="removeLead(${leadId})" style="font-size: 0.8em;"></button>
                            </div>
                        </div>
                    `;
                }
            });
            html += '</div>';
            selectedLeads.innerHTML = html;
        }
        
        // Update hidden inputs
        hiddenLeads.innerHTML = '';
        selectedLeadIds.forEach(leadId => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'leads[]';
            input.value = leadId;
            hiddenLeads.appendChild(input);
        });
        
        // Update submit button
        submitBtn.disabled = selectedLeadIds.length === 0;
    }
    
    // Load initial data if job is pre-selected
    if (jobSelect.value) {
        jobSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection