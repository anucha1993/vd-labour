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
                            <h5 class="border-bottom pb-2 mb-3"><i class="bi bi-briefcase me-2"></i>ข้อมูลงาน</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="text-muted small">หมายเลขงาน</label>
                                        <div><span class="badge bg-primary fs-6">{{ $job->job_number }}</span></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="text-muted small">สถานะงาน</label>
                                        <div>
                                            <span class="badge {{ $job->job_status == 'เปิดรับสมัคร' ? 'bg-success' : 'bg-secondary' }} fs-6">
                                                {{ $job->job_status }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="text-muted small">ชื่องาน</label>
                                <div class="fs-5 fw-bold">{{ $job->job_name }}</div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="text-muted small">ประเทศ</label>
                                        <div>
                                            @if($job->country)
                                                <i class="flag-icon flag-icon-{{ strtolower($job->country->country_code ?? 'xx') }}"></i>
                                                {{ $job->country->country_name_th }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="text-muted small">บริษัทนายจ้าง</label>
                                        <div>{{ $job->customer->customer_name ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="text-muted small">ประเภทงาน (Job Group)</label>
                                        <div>
                                            @if($job->jobGroup)
                                                {{ $job->jobGroup->job_group_name }} ({{ $job->jobGroup->job_group_name_th }})
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="text-muted small">ตำแหน่ง (Position)</label>
                                        <div>
                                            @if($job->position)
                                                {{ $job->position->position_name }} ({{ $job->position->position_name_th }})
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="text-muted small">วันเริ่มรับสมัคร</label>
                                        <div>
                                            @if($job->job_start_date)
                                                <i class="bi bi-calendar-check text-success"></i>
                                                {{ $job->job_start_date->format('d/m/Y') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="text-muted small">วันปิดรับสมัคร {{ number_format($job->job_total) }} <small class="text-muted fs-6">คน</small></label>
                                        <div>
                                            @if($job->job_end_date)
                                                <i class="bi bi-calendar-x text-danger"></i>
                                                {{ $job->job_end_date->format('d/m/Y') }}
                                            @else
                                                <span class="badge bg-info">รับสมัครต่อเนื่อง</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                               
                            </div>

                            @if($job->job_description)
                            <div class="mb-3">
                                <label class="text-muted small">รายละเอียดงาน</label>
                                <div class="border rounded p-3 bg-light">
                                    {!! nl2br(e($job->job_description)) !!}
                                </div>
                            </div>
                            @endif

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="text-muted small">สร้างโดย</label>
                                        <div>
                                            <i class="bi bi-person-circle"></i> {{ $job->createdBy->name ?? 'N/A' }}
                                            <br><small class="text-muted">{{ $job->created_at->format('d/m/Y H:i') }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="text-muted small">อัปเดตล่าสุด</label>
                                        <div>
                                            <i class="bi bi-pencil-square"></i> {{ $job->updatedBy->name ?? 'N/A' }}
                                            <br><small class="text-muted">{{ $job->updated_at->format('d/m/Y H:i') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="bi bi-graph-up"></i> สถิติผู้สมัคร</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center mb-3">
                                        <div class="col-6">
                                            <div class="border rounded p-2 bg-success bg-opacity-10">
                                                <h3 class="text-success mb-0">{{ number_format($stats['by_status']['ตอบรับ'] ?? 0) }}</h3>
                                                <small class="text-muted">ได้งาน</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="border rounded p-2 bg-primary bg-opacity-10">
                                                <h3 class="text-primary mb-0">{{ number_format($stats['remaining_positions']) }}</h3>
                                                <small class="text-muted">เหลือ</small>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="small">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span><i class="bi bi-people"></i> ทั้งหมด:</span>
                                            <strong>{{ $stats['total_applications'] ?? 0 }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span><i class="bi bi-file-text"></i> ร่าง:</span>
                                            <span class="badge bg-secondary">{{ $stats['by_status']['ร่าง'] ?? 0 }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span><i class="bi bi-send"></i> ส่งแล้ว:</span>
                                            <span class="badge bg-info">{{ $stats['by_status']['ส่งแล้ว'] ?? 0 }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span><i class="bi bi-eye"></i> กำลังพิจารณา:</span>
                                            <span class="badge bg-warning">{{ $stats['by_status']['กำลังพิจารณา'] ?? 0 }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span><i class="bi bi-calendar-check"></i> นัดสัมภาษณ์:</span>
                                            <span class="badge bg-primary">{{ $stats['by_status']['นัดสัมภาษณ์'] ?? 0 }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span><i class="bi bi-briefcase"></i> เสนองาน:</span>
                                            <span class="badge bg-info">{{ $stats['by_status']['เสนองาน'] ?? 0 }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span><i class="bi bi-x-circle"></i> ปฏิเสธ:</span>
                                            <span class="badge bg-danger">{{ $stats['by_status']['ปฏิเสธ'] ?? 0 }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span><i class="bi bi-dash-circle"></i> ถอน:</span>
                                            <span class="badge bg-secondary">{{ $stats['by_status']['ถอน'] ?? 0 }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span><i class="bi bi-lock"></i> ล็อคอยู่:</span>
                                            <span class="badge bg-warning">{{ $stats['locked_leads'] ?? 0 }}</span>
                                        </div>
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
                                    <i class="bi bi-info-circle me-2"></i>รายละเอียด Demand #{{ $job->demand->dm_let_no }}
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
                                            <th>เลขที่ผู้สมัคร</th>
                                              <th class="text-center" width="80"><i class="bi bi-image me-1"></i></th>
                                            <th>ชื่อ-สกุล</th>
                                          
                                            <th class="text-center" width="120">BMI</th>
                                              <th>ผู้แนะนำ</th>
                                            <th>
                                                สถานะ
                                                <i class="bi bi-info-circle text-primary ms-1" 
                                                   style="cursor: help;"
                                                   data-bs-toggle="tooltip" 
                                                   data-bs-placement="top" 
                                                   data-bs-html="true"
                                                   title="<div style='text-align: left;'><strong>คำอธิบายสถานะ:</strong><br>
                                               • <strong>ร่าง</strong>: ยังไม่ดำเนินการส่งใบสมัคร<br>
                                               • <strong>ส่งแล้ว</strong>: ส่งให้นายจ้างแล้ว<br>
                                               • <strong>กำลังพิจารณา</strong>: นายจ้างกำลังพิจารณา<br>
                                               • <strong>นัดสัมภาษณ์</strong>: นายจ้างนัดสัมภาษณ์<br>
                                               • <strong>เสนองาน</strong>: นายจ้างเสนองาน/นายจ้างเลือก<br>
                                               • <strong>ตอบรับ</strong>: ได้งานแล้ว ผู้สมัครตอบรับงานแล้ว<br>
                                               • <strong>ปฏิเสธ</strong>: ไม่ผ่าน/นายจ้างไม่เลือก *จะไม่สามารถสมัครงานนี้ใหม่ได้<br>
                                               • <strong>ถอน</strong>: ผู้สมัครถอนตัว *จะไม่สามารถสมัครงานนี้ใหม่ได้</div>"></i>
                                            </th>
                                            <th>วันที่สมัคร</th>
                                            <th width="150">จัดการ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($job->jobLeads->take(8) as $jobLead)
                                        <tr>
                                            <td>
                                                <a href="{{ route('job-leads.show', $jobLead->job_lead_id) }}" class="text-decoration-none fw-bold">
                                                    {{ $jobLead->lead->lead_number }}
                                                </a>
                                                @if($jobLead->is_locked)
                                                    <i class="bi bi-lock-fill text-warning ms-1" title="ล็อค"></i>
                                                @endif
                                            </td>

                                             <td class="text-center">
                                    @if($jobLead->lead->lead_photo)
                                        <img src="{{ asset('storage/' . $jobLead->lead->lead_photo) }}" 
                                             class="rounded-circle border cursor-pointer" 
                                             style="width: 50px; height: 50px; object-fit: cover; cursor: pointer;"
                                             alt="รูปถ่าย {{ $jobLead->lead->getFullNameAttribute() }}"
                                             data-bs-toggle="modal"
                                             data-bs-target="#photoModal"
                                             onclick="showPhoto('{{ asset('storage/' . $jobLead->lead->lead_photo) }}', '{{ $jobLead->lead->fullName }}')"
                                             title="คลิกเพื่อดูรูปใหญ่">
                                    @else
                                        <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px;">
                                            <i class="bi bi-person text-muted"></i>
                                        </div>
                                    @endif
                                </td>

                                            
                                            <td>{{ $jobLead->lead ? $jobLead->lead->getFullNameAttribute() : 'ไม่พบข้อมูล' }}</td>
                                            
                                            
                                            
                                            <td class="text-center">
                                                @if($jobLead->lead && $jobLead->lead->lead_bmi)
                                                    @php
                                                        $bmi = $jobLead->lead->lead_bmi;
                                                        $category = '';
                                                        $badgeClass = 'secondary';
                                                        
                                                        if ($bmi < 18) {
                                                            $category = 'ต่ำกว่าเกณฑ์';
                                                            $badgeClass = 'danger';
                                                        } elseif ($bmi >= 18 && $bmi <= 30) {
                                                            $category = 'ผ่านเกณฑ์';
                                                            $badgeClass = 'success';
                                                        } else {
                                                            $category = 'เกินเกณฑ์';
                                                            $badgeClass = 'danger';
                                                        }
                                                    @endphp
                                                    <div>
                                                        <strong>{{ number_format($bmi, 2) }}</strong>
                                                    </div>
                                                    <span class="badge bg-{{ $badgeClass }} mt-1" style="font-size: 0.7rem;">{{ $category }}</span>
                                                @else
                                                    <small class="text-muted">-</small>
                                                @endif
                                            </td>
                                            <td>
                                      
                                            <small><b>ผู้ดูแล: </b>{{ $jobLead->lead->staff->staff_name?? '-' }}</small>
                                            <br>
                                             <small><b>สายแนะนำ: </b>{{ $jobLead->lead->recommenderStaff->staff_sub_name?? '-' }}</small>
                                      
                                    </td>
                                            
                                            <td><span class="badge bg-{{ $jobLead->status_badge_color }}">{{ $jobLead->job_lead_status }}</span></td>

                                            <td>{{ $jobLead->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-outline-info btn-sm" 
                                                            onclick="showTimeline({{ $jobLead->job_lead_id }}, '{{ $jobLead->lead->getFullNameAttribute() }}')"
                                                            title="ดูประวัติการดำเนินการ">
                                                        <i class="bi bi-clock-history"></i>
                                                    </button>
                                                    
                                                    @if($jobLead->lead)
                                                    <a href="{{ route('leads.resume', $jobLead->lead->lead_id) }}" 
                                                       class="btn btn-success btn-sm" 
                                                       title="ดู Resume"
                                                       target="_blank">
                                                        <i class="bi bi-file-earmark-person"></i>
                                                    </a>
                                                    <a href="{{ route('pdf.cv.form', $jobLead->lead->lead_id) }}" 
                                                       class="btn btn-info btn-sm" 
                                                       title="ดู CV"
                                                       target="_blank">
                                                        <i class="bi bi-file-earmark-text"></i>
                                                    </a>
                                                    @endif
                                                    
                                                    @can('job-lead-delete')
                                                        @if(in_array($jobLead->job_lead_status, ['ร่าง', 'ส่งแล้ว']))
                                                        <button type="button" class="btn btn-outline-danger btn-sm" 
                                                                onclick="cancelApplication({{ $jobLead->job_lead_id }}, '{{ $jobLead->job_lead_number }}')"
                                                                title="ยกเลิก">
                                                            <i class="bi bi-x"></i>
                                                        </button>
                                                        @endif
                                                    @endcan
                                                </div>
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
        
        console.log('Available Leads Data:', availableLeadsData); // Debug log
        
        let html = '<div class="row">';
        availableLeadsData.forEach(lead => {
            console.log('Processing lead:', lead); // Debug each lead
            
            const isSelected = selectedLeads.find(s => s.id === lead.id);
            const isDisabled = lead.is_locked || lead.already_applied;
            
            let cardClass = 'bg-white';
            let statusBadge = '';
            
            if (lead.is_locked) {
                cardClass = 'bg-danger bg-opacity-10 border-danger';
                statusBadge = '<span class="badge bg-danger ms-1"><i class="bi bi-exclamation-triangle-fill"></i> มีใบสมัครแล้ว</span>';
            } else if (lead.already_applied) {
                cardClass = 'bg-warning bg-opacity-10 border-warning';  
                statusBadge = '<span class="badge bg-warning text-dark ms-1"><i class="bi bi-check-circle-fill"></i> เคยส่งแล้ว</span>';
            } else if (isSelected) {
                cardClass = 'bg-success bg-opacity-10 border-success';
                statusBadge = '<span class="badge bg-success ms-1"><i class="bi bi-check-circle-fill"></i> เลือกแล้ว</span>';
            }
            
            html += `
                <div class="col-md-6 mb-2">
                    <div class="card ${cardClass}">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center flex-wrap mb-1">
                                        <strong class="small">${lead.name}</strong>
                                        ${statusBadge}
                                    </div>
                                    <small class="text-muted d-block">
                                        <i class="bi bi-card-text"></i> ${lead.passport} | 
                                        <i class="bi bi-briefcase"></i> ${lead.position} |
                                        <i class="bi bi-geo-alt"></i> ${lead.country}
                                        ${lead.phone ? ` | <i class="bi bi-telephone"></i> ${lead.phone}` : ''}
                                        ${lead.age ? ` | อายุ ${lead.age} ปี` : ''}
                                    </small>
                                    ${(() => {
                                        if (lead.height || lead.weight || lead.bmi) {
                                            let bmiInfo = '';
                                            if (lead.height || lead.weight) {
                                                bmiInfo += `<i class="bi bi-person-bounding-box"></i> ${lead.height || '-'} cm / ${lead.weight || '-'} kg`;
                                            }
                                            if (lead.bmi) {
                                                const bmi = parseFloat(lead.bmi);
                                                let category = '';
                                                let badgeClass = 'secondary';
                                                
                                                if (bmi < 18.50) {
                                                    category = 'ผอม';
                                                    badgeClass = 'primary';
                                                } else if (bmi >= 18.50 && bmi <= 22.90) {
                                                    category = 'ปกติ';
                                                    badgeClass = 'success';
                                                } else if (bmi >= 23 && bmi <= 24.90) {
                                                    category = 'ท้วม';
                                                    badgeClass = 'warning';
                                                } else if (bmi >= 25 && bmi <= 29.90) {
                                                    category = 'อ้วน 1';
                                                    badgeClass = 'warning';
                                                } else {
                                                    category = 'อ้วน 2';
                                                    badgeClass = 'danger';
                                                }
                                                
                                                bmiInfo += ` | <strong>BMI:</strong> ${bmi.toFixed(2)} <span class="badge bg-${badgeClass}" style="font-size: 0.7rem;">${category}</span>`;
                                            }
                                            return `<small class="text-muted d-block mt-1">${bmiInfo}</small>`;
                                        }
                                        return '';
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
                                <div class="ms-2 d-flex flex-column gap-1">
                                    <!-- Resume & CV Buttons -->
                                    <a href="/leads/resume/${lead.id}" 
                                       class="btn btn-sm btn-success" 
                                       title="ดู Resume"
                                       target="_blank">
                                        <i class="bi bi-file-earmark-person"></i>
                                    </a>
                                    <a href="/cv-form/${lead.id}" 
                                       class="btn btn-sm btn-info" 
                                       title="ดู CV"
                                       target="_blank">
                                        <i class="bi bi-file-earmark-text"></i>
                                    </a>
                                    
                                    <!-- Select/Unselect Button -->
                                    ${!isDisabled && !isSelected ? `
                                        <button type="button" class="btn btn-sm btn-outline-success" onclick="selectLead(${lead.id})" 
                                                title="คลิกเพื่อเลือกคนงานนี้">
                                            <i class="bi bi-plus-circle"></i>
                                        </button>
                                    ` : isSelected ? `
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="unselectLead(${lead.id})"
                                                title="คลิกเพื่อยกเลิกการเลือก">
                                            <i class="bi bi-dash-circle"></i>
                                        </button>
                                    ` : `
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="hideLead(${lead.id})" 
                                                title="ยกเลิกการแสดงคนงานนี้">
                                            <i class="bi bi-x"></i>
                                        </button>
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
        const lead = availableLeadsData.find(l => l.id === leadId);
        
        // ตรวจสอบว่า lead ถูก lock หรือไม่
        if (lead && lead.is_locked) {
            alert('❌ ไม่สามารถเลือกคนงานนี้ได้\n\nเหตุผล: คนงานนี้มีใบสมัครงานอื่นอยู่แล้ว\n(อาจถูกล็อคหรือมีสถานะการสมัครที่ยังดำเนินอยู่)\n\nกรุณาเลือกคนงานคนอื่น');
            return;
        }
        
        if (lead && lead.already_applied) {
            if (!confirm('⚠️ คนงานนี้เคยส่งใบสมัครงานนี้แล้ว\n\nคุณต้องการเลือกต่อไปหรือไม่?')) {
                return;
            }
        }
        
        if (selectedLeads.length >= maxSelections) {
            alert(`❌ เลือกคนงานได้สูงสุด ${maxSelections} คน\n\nงานนี้เหลือตำแหน่งว่าง ${maxSelections} ตำแหน่ง`);
            return;
        }
        
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

    // Hide a lead from the current search results (dismiss the card)
    window.hideLead = function(leadId) {
        // remove from available list
        availableLeadsData = availableLeadsData.filter(l => l.id !== leadId);
        // also remove from selected if somehow present
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

// Show timeline function
function showTimeline(jobLeadId, applicantName) {
    const modal = new bootstrap.Modal(document.getElementById('timelineModal'));
    const modalTitle = document.getElementById('timelineModalLabel');
    const modalBody = document.getElementById('timelineModalBody');
    
    modalTitle.textContent = `ประวัติการดำเนินการ - ${applicantName}`;
    modalBody.innerHTML = '<div class="text-center py-5"><div class="spinner-border" role="status"></div><p class="mt-2">กำลังโหลดข้อมูล...</p></div>';
    
    modal.show();
    
    // Fetch timeline data
    fetch(`{{ url('job-leads') }}/${jobLeadId}/timeline`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            modalBody.innerHTML = data.html;
        } else {
            modalBody.innerHTML = '<div class="alert alert-danger">ไม่สามารถโหลดข้อมูลได้</div>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        modalBody.innerHTML = '<div class="alert alert-danger">เกิดข้อผิดพลาด: ' + error.message + '</div>';
    });
}

// Initialize Bootstrap tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, {
            html: true
        })
    });
    
    // Add custom CSS for tooltip alignment and width
    var style = document.createElement('style');
    style.textContent = `
        .tooltip-inner { 
            text-align: left !important; 
            max-width: 500px !important;
            width: 500px;
        }
    `;
    document.head.appendChild(style);
});
</script>

<!-- Timeline Modal -->
<div class="modal fade" id="timelineModal" tabindex="-1" aria-labelledby="timelineModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="timelineModalLabel">
                    <i class="bi bi-clock-history"></i> ประวัติการดำเนินการ
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="timelineModalBody">
                <div class="text-center py-5">
                    <div class="spinner-border" role="status"></div>
                    <p class="mt-2">กำลังโหลดข้อมูล...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

@endsection