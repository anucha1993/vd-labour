@extends('layouts.main')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    .select2-container--bootstrap-5 .select2-selection {
        min-height: 38px;
    }
    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
    }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="bi bi-file-earmark-excel me-2 text-success"></i>รายงาน Excel ใบสมัครงาน
        </h4>
        <a href="{{ route('my-leads.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> กลับ
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Export Job Applications Form -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-funnel me-2"></i>กรองข้อมูลสำหรับ Export</h5>
        </div>
        <div class="card-body">
            <form id="exportJobApplicationsForm" method="POST" action="{{ route('reports.job-applications.export') }}">
                @csrf
                
                <div class="row g-4">
                    <!-- Select Job Applications -->
                    <div class="col-md-6">
                        <div class="border rounded p-3 bg-light">
                            <label class="form-label fw-bold mb-3">
                                <i class="bi bi-1-circle-fill text-primary me-2"></i>เลือกใบสมัครที่ต้องการ Export
                            </label>
                            <select class="form-select select2-job-leads" name="job_lead_ids[]" multiple id="jobLeadsList">
                                <option value="">กำลังโหลด...</option>
                            </select>
                            <small class="text-muted mt-2 d-block">
                                <i class="bi bi-info-circle me-1"></i>หากไม่เลือก ระบบจะ Export ทั้งหมดตามเงื่อนไขการกรอง
                            </small>
                        </div>
                    </div>

                    <!-- Filter by Status -->
                    <div class="col-md-6">
                        <div class="border rounded p-3 bg-light">
                            <label class="form-label fw-bold mb-3">
                                <i class="bi bi-2-circle-fill text-primary me-2"></i>กรองตามสถานะใบสมัคร
                            </label>
                            <select class="form-select select2-status" name="statuses[]" multiple>
                                <option value="ร่าง">📝 ร่าง</option>
                                <option value="ส่งแล้ว">📤 ส่งแล้ว</option>
                                <option value="กำลังพิจารณา">⏳ กำลังพิจารณา</option>
                                <option value="นัดสัมภาษณ์">📅 นัดสัมภาษณ์</option>
                                <option value="เสนองาน">💼 เสนองาน</option>
                                <option value="ตอบรับ">✅ ตอบรับ</option>
                                <option value="ปฏิเสธ">❌ ปฏิเสธ</option>
                                <option value="ถอน">🔙 ถอน</option>
                            </select>
                            <small class="text-muted mt-2 d-block">
                                <i class="bi bi-info-circle me-1"></i>สามารถเลือกได้หลายสถานะ
                            </small>
                        </div>
                    </div>

                    <!-- Filter by Staff -->
                    <div class="col-md-6">
                        <div class="border rounded p-3 bg-light h-100">
                            <label class="form-label fw-bold mb-3">
                                <i class="bi bi-3-circle-fill text-primary me-2"></i>กรองตาม Staff (สรรหา)
                            </label>
                            <select class="form-select select2-staff" name="staff_ids[]" multiple>
                                @php
                                    $staffs = \App\Models\staff\staffModel::where('staff_status', 'active')->orderBy('staff_name')->get();
                                @endphp
                                @foreach($staffs as $staff)
                                    <option value="{{ $staff->staff_id }}">
                                        {{ $staff->staff_name }}
                                        @if($staff->staff_nickname)
                                            ({{ $staff->staff_nickname }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted mt-2 d-block">
                                <i class="bi bi-info-circle me-1"></i>สามารถเลือกได้หลาย Staff
                            </small>
                        </div>
                    </div>

                    <!-- Filter by Staff Sub -->
                    <div class="col-md-6">
                        <div class="border rounded p-3 bg-light h-100">
                            <label class="form-label fw-bold mb-3">
                                <i class="bi bi-4-circle-fill text-primary me-2"></i>กรองตาม Staff Sub (สายหาคน)
                            </label>
                            <select class="form-select select2-staff-sub" name="recommender_staff_ids[]" multiple>
                                @php
                                    $staffSubs = \App\Models\staff\staffSubModel::where('staff_sub_status', 'active')->orderBy('staff_sub_name')->get();
                                @endphp
                                @foreach($staffSubs as $staffSub)
                                    <option value="{{ $staffSub->staff_sub_id }}">
                                        {{ $staffSub->staff_sub_name }}
                                        @if($staffSub->staff_sub_phone)
                                            ({{ $staffSub->staff_sub_phone }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted mt-2 d-block">
                                <i class="bi bi-info-circle me-1"></i>สามารถเลือกได้หลาย Staff Sub
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Export Button -->
                <div class="text-center mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-success btn-lg px-5">
                        <i class="bi bi-download me-2"></i>Export ไฟล์ Excel
                    </button>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>ไฟล์ Excel จะถูกดาวน์โหลดอัตโนมัติเมื่อกดปุ่ม Export
                        </small>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Load job leads first
    loadJobLeadsForExport();
});

function loadJobLeadsForExport() {
    fetch('{{ route('reports.job-applications.list') }}')
        .then(response => response.json())
        .then(data => {
            const select = $('#jobLeadsList');
            select.empty(); // Clear loading option
            
            if (data.success && data.jobLeads.length > 0) {
                data.jobLeads.forEach(jobLead => {
                    const option = new Option(
                        `${jobLead.job_lead_number} - ${jobLead.lead_name} - ${jobLead.job_name} (${jobLead.status})`,
                        jobLead.job_lead_id,
                        false,
                        false
                    );
                    select.append(option);
                });
            } else {
                select.append(new Option('ไม่พบใบสมัครงาน', '', false, false));
            }
            
            // Initialize Select2 after loading data
            $('.select2-job-leads').select2({
                theme: 'bootstrap-5',
                placeholder: 'เลือกใบสมัคร...',
                allowClear: true,
                width: '100%'
            });
            
            // Initialize other Select2
            $('.select2-status').select2({
                theme: 'bootstrap-5',
                placeholder: 'เลือกสถานะ...',
                allowClear: true,
                width: '100%'
            });

            $('.select2-staff').select2({
                theme: 'bootstrap-5',
                placeholder: 'เลือก Staff...',
                allowClear: true,
                width: '100%'
            });

            $('.select2-staff-sub').select2({
                theme: 'bootstrap-5',
                placeholder: 'เลือก Staff Sub...',
                allowClear: true,
                width: '100%'
            });
        })
        .catch(error => {
            console.error('Error:', error);
            $('#jobLeadsList').empty().append(new Option('เกิดข้อผิดพลาดในการโหลดข้อมูล', '', false, false));
        });
}
</script>
@endsection
