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
                                                    data-job-number="{{ $jobOption->job_number }}"
                                                    data-job-name="{{ $jobOption->job_name }}"
                                                    data-job-status="{{ $jobOption->job_status }}"
                                                    data-country="{{ $jobOption->country->country_name_th ?? '' }}"
                                                    data-country-code="{{ $jobOption->country->country_code ?? '' }}"
                                                    data-customer="{{ $jobOption->customer->customer_name ?? '' }}"
                                                    data-company="{{ $jobOption->demand->dm_com_name ?? '' }}"
                                                    data-job-group="{{ $jobOption->jobGroup ? $jobOption->jobGroup->job_group_name . ' (' . $jobOption->jobGroup->job_group_name_th . ')' : '' }}"
                                                    data-position="{{ $jobOption->positions->count() > 0 ? $jobOption->positions->map(fn($p) => $p->position_name . ' (' . $p->position_name_th . ')')->implode(', ') : '' }}"
                                                    data-start-date="{{ $jobOption->job_start_date ? $jobOption->job_start_date->format('d/m/Y') : '' }}"
                                                    data-end-date="{{ $jobOption->job_end_date ? $jobOption->job_end_date->format('d/m/Y') : 'รับสมัครต่อเนื่อง' }}"
                                                    data-total="{{ $jobOption->job_total }}"
                                                    data-remaining="{{ $jobOption->remaining_positions }}"
                                                    data-demand-id="{{ $jobOption->demand->dm_id ?? '' }}"
                                                    data-demand-no="{{ $jobOption->demand->dm_let_no ?? '' }}"
                                                    data-demand-job="{{ $jobOption->demand->dm_job ?? '' }}"
                                                    data-demand-salary="{{ $jobOption->demand->dm_sa ?? '' }}"
                                                    data-demand-time="{{ $jobOption->demand->dm_time_work ?? '' }}"
                                                    data-demand-benefits="{{ $jobOption->demand->dm_exp ? json_encode($jobOption->demand->dm_exp) : '[]' }}">
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
                        <div id="jobInfo" class="card mb-4" style="{{ old('job_id', $job?->job_id) ? '' : 'display: none;' }}">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="bi bi-briefcase me-2"></i>ข้อมูลงาน</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="text-muted small">หมายเลขงาน</label>
                                        <div><span class="badge bg-primary fs-6" id="jobNumber">{{ $job->job_number ?? '' }}</span></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted small">สถานะงาน</label>
                                        <div><span class="badge bg-success fs-6" id="jobStatus">{{ $job->job_status ?? '' }}</span></div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="text-muted small">ชื่องาน</label>
                                    <div class="fs-5 fw-bold" id="jobName">{{ $job->job_name ?? '' }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="text-muted small">ประเทศ</label>
                                            <div id="jobCountry">{{ $job->country->country_name_th ?? '' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="text-muted small">บริษัทนายจ้าง</label>
                                            <div id="jobCustomer">{{ $job->customer->customer_name ?? '' }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="text-muted small">ประเภทงาน (Job Group)</label>
                                            <div id="jobGroup">{{ $job->jobGroup ? $job->jobGroup->job_group_name . ' (' . $job->jobGroup->job_group_name_th . ')' : '-' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="text-muted small">ตำแหน่ง (Position)</label>
                                            <div id="jobPosition">
                                                @if($job && $job->positions->count() > 0)
                                                    @foreach($job->positions as $pos)
                                                        <span class="badge bg-primary me-1">{{ $pos->position_name }} ({{ $pos->position_name_th }})</span>
                                                    @endforeach
                                                @else
                                                    -
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="text-muted small">วันเริ่มรับสมัคร</label>
                                            <div id="jobStartDate">
                                                <i class="bi bi-calendar-check text-success"></i>
                                                {{ $job->job_start_date ? $job->job_start_date->format('d/m/Y') : '-' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="text-muted small">วันปิดรับสมัคร</label>
                                            <div id="jobEndDate">
                                                @if($job?->job_end_date)
                                                    <i class="bi bi-calendar-x text-danger"></i>
                                                    {{ $job->job_end_date->format('d/m/Y') }}
                                                @else
                                                    <span class="badge bg-info">รับสมัครต่อเนื่อง</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="text-muted small">จำนวนรับทั้งหมด</label>
                                            <div class="fs-5 fw-bold text-primary" id="jobTotal">{{ $job->job_total ?? '' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="text-muted small">ตำแหน่งที่เหลือ</label>
                                            <div class="fs-5 fw-bold text-success" id="jobRemaining">{{ $job->remaining_positions ?? '' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="text-muted small">บริษัทจาก Demand</label>
                                            <div id="jobCompany">{{ $job->demand->dm_com_name ?? '' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Demand Details Accordion -->
                        <div id="demandAccordion" class="accordion mb-4" style="{{ old('job_id', $job?->job_id) ? '' : 'display: none;' }}">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                            data-bs-target="#demandDetails" aria-expanded="false">
                                        <i class="bi bi-info-circle me-2"></i>รายละเอียด Demand <span id="demandNo" class="ms-2"></span>
                                    </button>
                                </h2>
                                <div id="demandDetails" class="accordion-collapse collapse">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3" id="demandJobSection" style="display: none;">
                                                    <strong>รายละเอียดงาน:</strong>
                                                    <div class="small text-muted mt-1" id="demandJob"></div>
                                                </div>
                                                
                                                <div class="mb-3" id="demandSalarySection" style="display: none;">
                                                    <strong>เงินเดือน/สวัสดิการ:</strong>
                                                    <div class="small text-success mt-1" id="demandSalary"></div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="mb-3" id="demandTimeSection" style="display: none;">
                                                    <strong>เวลาทำงาน:</strong>
                                                    <div class="small text-muted mt-1" id="demandTime"></div>
                                                </div>
                                                
                                                <div class="mb-3" id="demandBenefitsSection" style="display: none;">
                                                    <strong>สวัสดิการ:</strong>
                                                    <div class="mt-1" id="demandBenefits"></div>
                                                </div>
                                            </div>
                                        </div>
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
        const demandAccordion = document.getElementById('demandAccordion');
        
        if (this.value) {
            // Update job info - basic
            document.getElementById('jobNumber').textContent = option.dataset.jobNumber || '';
            document.getElementById('jobStatus').textContent = option.dataset.jobStatus || '';
            document.getElementById('jobStatus').className = 'badge fs-6 ' + (option.dataset.jobStatus === 'เปิดรับสมัคร' ? 'bg-success' : 'bg-secondary');
            document.getElementById('jobName').textContent = option.dataset.jobName || '';
            
            // Update job info - details
            document.getElementById('jobCountry').textContent = option.dataset.country || '-';
            document.getElementById('jobCustomer').textContent = option.dataset.customer || '-';
            document.getElementById('jobGroup').textContent = option.dataset.jobGroup || '-';
            
            // Update positions as badges
            const posEl = document.getElementById('jobPosition');
            const posData = option.dataset.position;
            if (posData) {
                posEl.innerHTML = posData.split(', ').map(p => `<span class="badge bg-primary me-1">${p}</span>`).join('');
            } else {
                posEl.textContent = '-';
            }
            
            // Update dates
            const startDateEl = document.getElementById('jobStartDate');
            if (option.dataset.startDate) {
                startDateEl.innerHTML = '<i class="bi bi-calendar-check text-success"></i> ' + option.dataset.startDate;
            } else {
                startDateEl.textContent = '-';
            }
            
            const endDateEl = document.getElementById('jobEndDate');
            if (option.dataset.endDate && option.dataset.endDate !== 'รับสมัครต่อเนื่อง') {
                endDateEl.innerHTML = '<i class="bi bi-calendar-x text-danger"></i> ' + option.dataset.endDate;
            } else {
                endDateEl.innerHTML = '<span class="badge bg-info">รับสมัครต่อเนื่อง</span>';
            }
            
            // Update totals
            document.getElementById('jobTotal').textContent = option.dataset.total || '0';
            document.getElementById('jobRemaining').textContent = option.dataset.remaining || '0';
            document.getElementById('jobCompany').textContent = option.dataset.company || '-';
            
            // Update demand details
            const demandNo = option.dataset.demandNo;
            const demandJob = option.dataset.demandJob;
            const demandSalary = option.dataset.demandSalary;
            const demandTime = option.dataset.demandTime;
            const demandBenefitsJson = option.dataset.demandBenefits;
            
            if (demandNo) {
                document.getElementById('demandNo').textContent = '#' + demandNo;
            }
            
            // Show/hide demand sections based on data
            const demandJobSection = document.getElementById('demandJobSection');
            if (demandJob) {
                document.getElementById('demandJob').innerHTML = demandJob.replace(/\n/g, '<br>');
                demandJobSection.style.display = 'block';
            } else {
                demandJobSection.style.display = 'none';
            }
            
            const demandSalarySection = document.getElementById('demandSalarySection');
            if (demandSalary) {
                document.getElementById('demandSalary').innerHTML = demandSalary.replace(/\n/g, '<br>');
                demandSalarySection.style.display = 'block';
            } else {
                demandSalarySection.style.display = 'none';
            }
            
            const demandTimeSection = document.getElementById('demandTimeSection');
            if (demandTime) {
                document.getElementById('demandTime').textContent = demandTime;
                demandTimeSection.style.display = 'block';
            } else {
                demandTimeSection.style.display = 'none';
            }
            
            const demandBenefitsSection = document.getElementById('demandBenefitsSection');
            if (demandBenefitsJson) {
                try {
                    const benefits = JSON.parse(demandBenefitsJson);
                    const benefitMap = {
                        'accomm': 'ที่พัก',
                        'food': 'อาหาร',
                        'med': 'รักษาพยาบาล',
                        'shuttle': 'รถรับส่ง'
                    };
                    
                    let benefitsHtml = '';
                    benefits.forEach(benefit => {
                        const label = benefitMap[benefit] || benefit;
                        benefitsHtml += `<div class="d-flex align-items-center mb-1">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <span>${label}</span>
                        </div>`;
                    });
                    
                    if (benefitsHtml) {
                        document.getElementById('demandBenefits').innerHTML = benefitsHtml;
                        demandBenefitsSection.style.display = 'block';
                    } else {
                        demandBenefitsSection.style.display = 'none';
                    }
                } catch (e) {
                    demandBenefitsSection.style.display = 'none';
                }
            } else {
                demandBenefitsSection.style.display = 'none';
            }
            
            // Show job info and demand accordion
            jobInfo.style.display = 'block';
            demandAccordion.style.display = 'block';
            
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
            demandAccordion.style.display = 'none';
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
        
        console.log('Available Leads Data:', availableLeadsData); // Debug log
        
        let html = '';
        availableLeadsData.forEach(lead => {
            console.log('Processing lead:', lead); // Debug each lead
            const isSelected = selectedLeadIds.includes(lead.id);
            const isDisabled = lead.is_locked || lead.already_applied;
            
            let cardClass = 'bg-white';
            let statusBadge = '';
            
            if (lead.is_locked) {
                cardClass = 'bg-danger bg-opacity-10 border-danger';
                let appInfo = lead.existing_application;
                if (appInfo) {
                    statusBadge = `<span class="badge bg-danger ms-2"><i class="bi bi-exclamation-triangle-fill"></i> มีใบสมัครแล้ว</span>`;
                } else {
                    statusBadge = '<span class="badge bg-danger ms-2"><i class="bi bi-exclamation-triangle-fill"></i> มีใบสมัครอื่น</span>';
                }
            } else if (lead.already_applied) {
                cardClass = 'bg-warning bg-opacity-10 border-warning';  
                statusBadge = '<span class="badge bg-warning text-dark ms-2"><i class="bi bi-check-circle-fill"></i> เคยส่งแล้ว</span>';
            } else if (isSelected) {
                cardClass = 'bg-primary bg-opacity-10 border-primary';
                statusBadge = '<span class="badge bg-primary ms-2"><i class="bi bi-check-circle-fill"></i> เลือกแล้ว</span>';
            }
            
            html += `
                <div class="border rounded p-3 mb-2 ${cardClass}">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center flex-wrap mb-1">
                                <strong>${lead.name}</strong>
                                ${statusBadge}
                            </div>
                            <small class="text-muted d-block mb-2">
                                <i class="bi bi-card-text"></i> ${lead.passport} | 
                                <i class="bi bi-geo-alt"></i> ${lead.country}
                                ${lead.phone ? ` | <i class="bi bi-telephone"></i> ${lead.phone}` : ''}
                                ${lead.age ? ` | อายุ ${lead.age} ปี` : ''}
                            </small>
                            
                            ${(() => {
                                // แสดงข้อมูล Job Group และ Position
                                let infoHtml = '<div class="mb-2">';
                                
                                // Job Group
                                if (lead.job_group) {
                                    infoHtml += `<div class="mb-1"><small><i class="bi bi-diagram-3"></i> <strong>กลุ่มงาน:</strong> ${lead.job_group}</small></div>`;
                                }
                                
                                // แสดงตำแหน่งที่ตรงกับงาน (ถ้ามี)
                                if (lead.matched_position) {
                                    infoHtml += `<div class="mb-1"><small><i class="bi bi-briefcase-fill text-success"></i> <strong class="text-success">ตำแหน่งที่ตรงกับงาน:</strong> <span class="badge bg-success">${lead.matched_position}</span></small></div>`;
                                }
                                
                                // แสดงตำแหน่งทั้งหมด
                                if (lead.positions && lead.positions.length > 0) {
                                    infoHtml += `<div class="mb-1"><small><i class="bi bi-briefcase"></i> <strong>ตำแหน่งที่สนใจ:</strong> ${lead.positions.join(', ')}</small></div>`;
                                }
                                
                                infoHtml += '</div>';
                                return infoHtml;
                            })()}
                            
                            ${(() => {
                                if (lead.is_locked) {
                                    console.log('Lead is locked:', lead.name, 'Existing app:', lead.existing_application);
                                    if (lead.existing_application) {
                                        return `
                                            <div class="mt-2 p-2 border rounded bg-danger bg-opacity-5">
                                                <small class="text-white">
                                                    <i class="bi bi-exclamation-triangle-fill"></i> 
                                                    <strong>ไม่สามารถเลือกได้:</strong> คนงานนี้มีใบสมัครอยู่แล้ว<br>
                                                    <div class="mt-1">
                                                        <i class="bi bi-file-earmark-text"></i> <strong>เลขที่ใบสมัคร:</strong> ${lead.existing_application.job_lead_number || 'ไม่ระบุ'}<br>
                                                        <i class="bi bi-briefcase"></i> <strong>งาน:</strong> ${lead.existing_application.job_name || 'ไม่ระบุ'}<br>
                                                        <i class="bi bi-tag"></i> <strong>สถานะ:</strong> ${lead.existing_application.job_lead_status || 'ไม่ระบุ'}
                                                        ${lead.existing_application.is_locked ? ' <span class="badge bg-warning text-dark">ล็อค</span>' : ''}
                                                    </div>
                                                </small>
                                            </div>
                                        `;
                                    } else {
                                        return '<div class="mt-2"><small class="text-danger"><i class="bi bi-exclamation-triangle-fill"></i> <strong>ไม่สามารถเลือกได้:</strong> คนงานนี้มีใบสมัครงานอื่นอยู่แล้ว (ไม่พบรายละเอียด)</small></div>';
                                    }
                                }
                                return '';
                            })()}
                            ${lead.already_applied ? '<div class="mt-2"><small class="text-warning"><i class="bi bi-info-circle-fill"></i> <strong>เตือน:</strong> เคยส่งใบสมัครงานนี้แล้ว</small></div>' : ''}
                        </div>
                        <div class="ms-2">
                            ${!isDisabled ? `
                                <button type="button" class="btn btn-sm ${isSelected ? 'btn-outline-danger' : 'btn-outline-primary'}" 
                                        onclick="${isSelected ? 'removeLead' : 'addLead'}(${lead.id})" 
                                        title="${isSelected ? 'คลิกเพื่อยกเลิกการเลือก' : 'คลิกเพื่อเลือกคนงานนี้'}">
                                    ${isSelected ? '<i class="bi bi-dash-circle"></i>' : '<i class="bi bi-plus-circle"></i>'}
                                </button>
                            ` : `
                                <button type="button" class="btn btn-sm btn-outline-secondary" disabled 
                                        title="ไม่สามารถเลือกได้">
                                    <i class="bi bi-x-circle"></i>
                                </button>
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
            // ตรวจสอบว่า lead ถูก lock หรือไม่
            const lead = availableLeadsData.find(l => l.id === leadId);
            if (lead && lead.is_locked) {
                alert('❌ ไม่สามารถเลือกคนงานนี้ได้\n\nเหตุผล: คนงานนี้มีใบสมัครงานอื่นอยู่แล้ว\n(อาจถูกล็อคหรือมีสถานะการสมัครที่ยังดำเนินอยู่)\n\nกรุณาเลือกคนงานคนอื่น');
                return;
            }
            
            if (lead && lead.already_applied) {
                if (!confirm('⚠️ คนงานนี้เคยส่งใบสมัครงานนี้แล้ว\n\nคุณต้องการเลือกต่อไปหรือไม่?')) {
                    return;
                }
            }
            
            const jobOption = jobSelect.options[jobSelect.selectedIndex];
            const remaining = parseInt(jobOption.dataset.remaining);
            
            if (selectedLeadIds.length >= remaining) {
                alert(`❌ เลือกคนงานได้สูงสุด ${remaining} คน\n\nงานนี้เหลือตำแหน่งว่าง ${remaining} ตำแหน่ง`);
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