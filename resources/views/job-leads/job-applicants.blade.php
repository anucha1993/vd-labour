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
                        <div>
                            <button type="button" class="btn btn-info btn-sm me-2" data-bs-toggle="modal" data-bs-target="#jobDetailModal">
                                <i class="bi bi-info-circle"></i> ดูรายละเอียดงาน
                            </button>
                            @if($job->demand)
                            <button type="button" class="btn btn-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#demandDetailModal">
                                <i class="bi bi-file-text"></i> ดูรายละเอียด Demand
                            </button>
                            @endif
                            <a href="{{ route('job-leads.index') }}" class="btn btn-light btn-sm">
                                <i class="bi bi-arrow-left"></i> กลับ
                            </a>
                        </div>
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
                            @php
                                $exportQuery = http_build_query(request()->only(['search','job_lead_status','is_locked']));
                                $exportUrl = route('job-leads.job-applicants.export', $job->job_id) . ($exportQuery ? '?' . $exportQuery : '');
                            @endphp

                            <a href="{{ $exportUrl }}" class="btn btn-success me-2">
                                <i class="bi bi-file-earmark-excel"></i> Export Excel
                            </a>

                            @if(request()->hasAny(['search', 'job_lead_status', 'is_locked']))
                            <a href="{{ route('job-leads.job-applicants', $job->job_id) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> ล้างตัวกรอง
                            </a>
                            @endif
                        </div>
                    </div>

                    <!-- Mass Update Form -->
                    @can('job-lead-bulk-update')
                    <form id="massUpdateForm" method="POST" action="{{ route('job-leads.bulk-update') }}">
                        @csrf
                        @method('PATCH')
                        <div class="card border-warning mb-4">
                            <div class="card-header bg-warning bg-opacity-10">
                                <h6 class="mb-0"><i class="bi bi-lightning"></i> อัปเดตสถานะหลายรายการ</h6>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-end">
                                    <div class="col-md-3">
                                        <label class="form-label small">
                                            สถานะใหม่:
                                            <i class="bi bi-info-circle text-primary" 
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
                                               • <strong>ปฏิเสธ</strong>: ไม่ผ่าน/นายจ้างไม่เลือก (ต้องระบุเหตุผล)  *จะไม่สามารถสมัครงานนี้ใหม่ได้<br>
                                               • <strong>ถอน</strong>: ผู้สมัครถอนตัว (ต้องระบุเหตุผล) *จะไม่สามารถสมัครงานนี้ใหม่ได้</div>"></i>
                                        </label>
                                        <select name="new_status" class="form-select" id="bulkStatusSelect" required>
                                            <option value="">-- เลือกสถานะ --</option>
                                            <option value="ร่าง">📝 ร่าง</option>
                                            <option value="ส่งแล้ว">📤 ส่งแล้ว</option>
                                            <option value="กำลังพิจารณา">🔍 กำลังพิจารณา</option>
                                            <option value="นัดสัมภาษณ์">📅 นัดสัมภาษณ์</option>
                                            <option value="เสนองาน">💼 เสนองาน</option>
                                            <option value="ตอบรับ">✅ ตอบรับ</option>
                                            <option value="ปฏิเสธ">❌ ปฏิเสธ</option>
                                            <option value="ถอน">🔙 ถอน</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3" id="bulkReasonField" style="display: none;">
                                        <label class="form-label small text-danger">
                                            เหตุผล <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="reason" id="bulkReason" class="form-control" 
                                               placeholder="ระบุเหตุผลสำหรับ ปฏิเสธ/ถอน">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small">หมายเหตุเพิ่มเติม:</label>
                                        <input type="text" name="remarks" class="form-control" placeholder="หมายเหตุ (ถ้ามี)">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-warning" id="massUpdateBtn" disabled>
                                            <i class="bi bi-lightning"></i> อัปเดต (<span id="selectedCount">0</span>)
                                        </button>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-outline-secondary" id="selectAllBtn">
                                            <i class="bi bi-check-all"></i> เลือกทั้งหมด
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

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
                                    <th>หมายเลขผู้สมัคร</th>
                                     <th class="text-center" width="80"><i class="bi bi-image me-1"></i></th>
                                    <th>ชื่อผู้สมัคร</th>
                                    <th>Passport</th>
                                    <th>เบอร์โทร</th>
                                    <th class="text-center" width="100">BMI</th>
                                    <th>ผู้แนะนำ</th>

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
                                        @if($jobLead->lead && $jobLead->lead->isConverted())
                                            <input type="checkbox" name="job_lead_ids[]" 
                                                   value="{{ $jobLead->job_lead_id }}" 
                                                   class="form-check-input applicant-checkbox"
                                                   disabled
                                                   title="Lead ถูก Convert เป็น Labour แล้ว ไม่สามารถแก้ไขได้">
                                        @else
                                            <input type="checkbox" name="job_lead_ids[]" 
                                                   value="{{ $jobLead->job_lead_id }}" 
                                                   class="form-check-input applicant-checkbox">
                                        @endif
                                    </td>
                                    @endcan
                                    <td>
                                        <a href="{{ route('job-leads.show', $jobLead->job_lead_id) }}" 
                                           class="text-decoration-none fw-bold">
                                            {{ $jobLead->lead->lead_number }}
                                        </a>
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
                                 
                                    <td class="text-center">
                                       

                                        @if($jobLead->lead && $jobLead->lead->lead_bmi)
                                            @php
                                                $bmi = $jobLead->lead->lead_bmi;
                                                $category = '';
                                                $badgeClass = 'secondary';
                                                
                                                if ($bmi < 18.50) {
                                                    $category = 'ผอม';
                                                    $badgeClass = 'primary';
                                                } elseif ($bmi >= 18.50 && $bmi <= 22.90) {
                                                    $category = 'ปกติ';
                                                    $badgeClass = 'success';
                                                } elseif ($bmi >= 23 && $bmi <= 24.90) {
                                                    $category = 'ท้วม';
                                                    $badgeClass = 'warning';
                                                } elseif ($bmi >= 25 && $bmi <= 29.90) {
                                                    $category = 'อ้วน 1';
                                                    $badgeClass = 'warning';
                                                } else {
                                                    $category = 'อ้วน 2';
                                                    $badgeClass = 'danger';
                                                }
                                            @endphp
                                            <div>
                                                <strong>{{ number_format($bmi, 2) }}</strong>
                                            </div>
                                            <span class="badge bg-{{ $badgeClass }}" style="font-size: 0.65rem;">{{ $category }}</span>
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                    <td>
                                      
                                            <small><b>ผู้ดูแล: </b>{{ $jobLead->lead->staff->staff_name?? '-' }}</small>
                                            <br>
                                             <small><b>สายแนะนำ: </b>{{ $jobLead->lead->recommenderStaff->staff_sub_name?? '-' }}</small>
                                      
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
                                        <div class="btn-group-vertical" role="group">
                                            <!-- Resume & CV Buttons -->
                                            <div class="btn-group mb-1" role="group">
                                                @if($jobLead->lead)
                                                <a href="{{ route('leads.resume', $jobLead->lead->lead_id) }}" 
                                                   class="btn btn-sm btn-success me-1" 
                                                   title="ดู Resume"
                                                   target="_blank">
                                                    <i class="bi bi-file-earmark-person"></i> Resume
                                                </a>
                                                <a href="{{ route('pdf.cv.form', $jobLead->lead->lead_id) }}" 
                                                   class="btn btn-sm btn-info me-1" 
                                                   title="ดู CV Form"
                                                   target="_blank">
                                                    <i class="bi bi-file-earmark-text"></i> CV
                                                </a>
                                                @endif
                                            </div>

                                            
                                            <!-- Action Buttons -->
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-info me-1" 
                                                        onclick="showTimeline({{ $jobLead->job_lead_id }}, '{{ $jobLead->lead->getFullNameAttribute() }}')"
                                                        title="ดูประวัติการดำเนินการ">
                                                    <i class="bi bi-clock-history"></i>
                                                </button>
                                                
                                                @can('job-lead-edit')
                                                <a href="{{ route('job-leads.edit', $jobLead->job_lead_id) }}" 
                                                   class="btn btn-sm btn-outline-primary me-1" title="แก้ไข">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                @endcan

                                                
                                                @can('job-lead-delete')
                                                    @if(in_array($jobLead->job_lead_status, ['ร่าง', 'ส่งแล้ว']))
                                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                                            onclick="cancelApplication({{ $jobLead->job_lead_id }}, '{{ $jobLead->job_lead_number }}')"
                                                            title="ยกเลิกใบสมัคร *จะลบข้อมูลใบสมัครนี้ออกสามารถสมัครใหม่ได้">
                                                        <i class="bi bi-x-circle"></i>
                                                    </button>
                                                   
                                                    @endif
                                                @endcan
                                            </div>
                                        </div>
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
                    </form>
                    @endcan
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

<!-- Job Detail Modal -->
<div class="modal fade" id="jobDetailModal" tabindex="-1" aria-labelledby="jobDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="jobDetailModalLabel">
                    <i class="bi bi-briefcase"></i> รายละเอียดงาน
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="text-muted small">หมายเลขงาน</label>
                        <div><span class="badge bg-primary">{{ $job->job_number }}</span></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">สถานะงาน</label>
                        <div>
                            <span class="badge {{ $job->job_status == 'เปิดรับสมัคร' ? 'bg-success' : 'bg-secondary' }}">
                                {{ $job->job_status }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="text-muted small">ชื่องาน</label>
                    <div class="fs-5 fw-bold">{{ $job->job_name }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="text-muted small">ประเทศ</label>
                        <div>{{ $job->country->country_name_th ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">บริษัทนายจ้าง</label>
                        <div>{{ $job->customer->customer_name ?? '-' }}</div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="text-muted small">ประเภทงาน (Job Group)</label>
                        <div>
                            @if($job->jobGroup)
                                {{ $job->jobGroup->job_group_name }} ({{ $job->jobGroup->job_group_name_th }})
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">ตำแหน่ง (Position)</label>
                        <div>
                            @if($job->position)
                                {{ $job->position->position_name }} ({{ $job->position->position_name_th }})
                            @else
                                -
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="text-muted small">วันเริ่มรับสมัคร</label>
                        <div>
                            @if($job->job_start_date)
                                {{ $job->job_start_date->format('d/m/Y') }}
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">วันปิดรับสมัคร</label>
                        <div>
                            @if($job->job_end_date)
                                {{ $job->job_end_date->format('d/m/Y') }}
                            @else
                                <span class="badge bg-info">รับสมัครต่อเนื่อง</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">จำนวนเปิดรับ</label>
                        <div class="fs-4 fw-bold text-info">{{ number_format($job->job_total) }} <small class="text-muted">คน</small></div>
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

                <div class="row">
                    <div class="col-md-6">
                        <label class="text-muted small">สร้างโดย</label>
                        <div>
                            {{ $job->createdBy->name ?? '-' }}
                            @if($job->created_at)
                                <br><small class="text-muted">{{ $job->created_at->format('d/m/Y H:i') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">อัปเดตล่าสุด</label>
                        <div>
                            {{ $job->updatedBy->name ?? '-' }}
                            @if($job->updated_at)
                                <br><small class="text-muted">{{ $job->updated_at->format('d/m/Y H:i') }}</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

<!-- Demand Detail Modal -->
@if($job->demand)
<div class="modal fade" id="demandDetailModal" tabindex="-1" aria-labelledby="demandDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="demandDetailModalLabel">
                    <i class="bi bi-file-text"></i> รายละเอียด Demand
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="text-muted small">เลขที่หนังสือ</label>
                        <div class="fw-bold">{{ $job->demand->dm_let_no ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">ชื่อบริษัท</label>
                        <div class="fw-bold">{{ $job->demand->dm_com_name ?? '-' }}</div>
                    </div>
                </div>

                @if($job->demand->industryType)
                <div class="mb-3">
                    <label class="text-muted small">ประเภทอุตสาหกรรม</label>
                    <div>{{ $job->demand->industryType->industry_type_name }}</div>
                </div>
                @endif

                @if($job->demand->dm_job)
                <div class="mb-3">
                    <label class="text-muted small">รายละเอียดงาน</label>
                    <div class="border rounded p-3 bg-light">
                        {!! nl2br(e($job->demand->dm_job)) !!}
                    </div>
                </div>
                @endif


                @if($job->demand->dm_sa)

                <div class="mb-3">
                    <label class="text-muted small">เงินเดือน/สวัสดิการ</label>
                    <div class="border rounded p-3 bg-success bg-opacity-10">
                        <div class="text-success">
                            {!! nl2br(e($job->demand->dm_sa)) !!}
                        </div>
                    </div>
                </div> 

                @endif

                @if($job->demand->dm_time_work)
                <div class="mb-3">
                    <label class="text-muted small">เวลาทำงาน</label>
                    <div>{{ $job->demand->dm_time_work }}</div>
                </div>
                @endif

                @if($job->demand->dm_exp && is_array($job->demand->dm_exp) && count($job->demand->dm_exp) > 0)
                <div class="mb-3">
                    <label class="text-muted small">สวัสดิการ</label>
                    <div>
                        @php
                            $benefitMap = ['accomm' => 'ที่พัก', 'food' => 'อาหาร', 'med' => 'รักษาพยาบาล', 'shuttle' => 'รถรับส่ง'];
                        @endphp
                        @foreach($job->demand->dm_exp as $benefit)
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <span>{{ $benefitMap[$benefit] ?? $benefit }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($job->demand->dm_note)
                <div class="mb-3">
                    <label class="text-muted small">หมายเหตุ</label>
                    <div class="border rounded p-3 bg-light">
                        {!! nl2br(e($job->demand->dm_note)) !!}
                    </div>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>
@endif

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
    
    // Bulk status select - toggle reason field
    const bulkStatusSelect = document.getElementById('bulkStatusSelect');
    const bulkReasonField = document.getElementById('bulkReasonField');
    const bulkReason = document.getElementById('bulkReason');
    
    if (bulkStatusSelect) {
        bulkStatusSelect.addEventListener('change', function() {
            const selectedStatus = this.value;
            const requiresReason = ['ปฏิเสธ', 'ถอน'].includes(selectedStatus);
            
            if (requiresReason) {
                bulkReasonField.style.display = 'block';
                bulkReason.required = true;
            } else {
                bulkReasonField.style.display = 'none';
                bulkReason.required = false;
                bulkReason.value = '';
            }
        });
    }
    
    // Mass update form submission
    const massUpdateForm = document.getElementById('massUpdateForm');
    if (massUpdateForm) {
        massUpdateForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default first
            
            const selectedCheckboxes = document.querySelectorAll('.applicant-checkbox:checked');
            const selectedCount = selectedCheckboxes.length;
            
            if (selectedCount === 0) {
                alert('กรุณาเลือกผู้สมัครอย่างน้อย 1 คน');
                return;
            }
            
            const newStatus = this.querySelector('[name="new_status"]').value;
            if (!newStatus) {
                alert('กรุณาเลือกสถานะใหม่');
                return;
            }
            
            // Check if reason is required and provided
            const requiresReason = ['ปฏิเสธ', 'ถอน'].includes(newStatus);
            const reason = bulkReason.value.trim();
            if (requiresReason && !reason) {
                alert(`กรุณาระบุเหตุผลสำหรับการเปลี่ยนสถานะเป็น "${newStatus}"`);
                return;
            }
            
            if (!confirm(`คุณต้องการเปลี่ยนสถานะของผู้สมัคร ${selectedCount} คน เป็น "${newStatus}" หรือไม่?`)) {
                return;
            }
            
            // Add selected job_lead_ids to form
            selectedCheckboxes.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'job_lead_ids[]';
                input.value = checkbox.value;
                this.appendChild(input);
            });
            
            // Now submit the form
            this.submit();
        });
    }
});

// Cancel application function  
function cancelApplication(jobLeadId, jobLeadNumber) {
    // Prompt for cancellation reason
    const reason = prompt(`กรุณาระบุเหตุผลในการยกเลิกใบสมัคร "${jobLeadNumber}":`);
    
    // Check if user cancelled the prompt or entered empty reason
    if (reason === null) {
        return; // User clicked cancel
    }
    
    if (reason.trim() === '') {
        alert('กรุณาระบุเหตุผลในการยกเลิก');
        return;
    }
    
    if (confirm(`คุณต้องการยกเลิกใบสมัคร "${jobLeadNumber}" หรือไม่?\n\nเหตุผล: ${reason}\n\nการยกเลิกจะปลดล็อคคนงานและลบใบสมัครออกจากระบบ`)) {
        fetch(`{{ url('job-leads') }}/${jobLeadId}/cancel`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                reason: reason.trim()
            })
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
            alert('เกิดข้อผิดพลาด: ' + error.message);
        });
    }
} //

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

<script>
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
            max-width: 400px !important;
            width: 400px;
        }
    `;
    document.head.appendChild(style);
});
</script>

@endsection