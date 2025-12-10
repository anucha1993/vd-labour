@extends('layouts.main')

@section('styles')
<style>
    /* Fix dropdown being cut off in table */
    .table-responsive {
        overflow: visible !important;
    }
    
    .card.card-custom {
        overflow: visible !important;
    }
    
    .card-body {
        overflow: visible !important;
    }
    
    table {
        overflow: visible !important;
    }
    
    tbody {
        overflow: visible !important;
    }
    
    tr {
        overflow: visible !important;
    }
    
    td {
        overflow: visible !important;
    }
    
    @media screen and (max-width: 768px) {
        .table-responsive {
            overflow-x: auto !important;
            overflow-y: visible !important;
        }
    }
    
    /* Ensure dropdown appears above other elements */
    .dropdown-menu {
        z-index: 9999 !important;
        position: absolute !important;
    }
    
    .btn-group {
        position: static !important;
    }
</style>
@endsection

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ $message }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ $message }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card card-custom mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <h4 class="mb-0"><i class="bi bi-person-plus-fill me-2 text-primary"></i>จัดการข้อมูลผู้สนใจ (Leads)</h4>
                <div class="d-flex gap-2">
                    @can('view lead')
                    <div class="btn-group">
                        <a href="{{ route('pdf.leads.list', request()->query()) }}" class="btn btn-success" target="_blank">
                            <i class="bi bi-file-pdf"></i> Preview PDF
                        </a>
                        <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                            <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('pdf.download.leads.list', request()->query()) }}">
                                <i class="bi bi-download"></i> ดาวน์โหลด PDF
                            </a></li>
                        </ul>
                    </div>
                    @endcan
                    @can('create lead')
                    <a href="{{ route('leads.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle-fill"></i> เพิ่มผู้สนใจ
                    </a>
                    @endcan
                </div>
            </div>

            <!-- Search Form -->
            <form method="GET" action="{{ route('leads.index') }}" class="mb-3">
                <div class="row g-2">
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="search" placeholder="ค้นหา เลขที่ผู้สมัคร, ชื่อ, โทร, พาสปอร์ต, ผู้ดูแล, สายแนะนำ..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="lead_status">
                            <option value="all">-- สถานะทั้งหมด --</option>
                            <option value="new" {{ request('lead_status') == 'new' ? 'selected' : '' }}>ใหม่</option>
                            <option value="contacted" {{ request('lead_status') == 'contacted' ? 'selected' : '' }}>ติดต่อแล้ว</option>
                            <option value="interview" {{ request('lead_status') == 'interview' ? 'selected' : '' }}>นัดสัมภาษณ์</option>
                            <option value="qualified" {{ request('lead_status') == 'qualified' ? 'selected' : '' }}>ผ่านคุณสมบัติ</option>
                            <option value="converted" {{ request('lead_status') == 'converted' ? 'selected' : '' }}>Convert แล้ว</option>
                            <option value="rejected" {{ request('lead_status') == 'rejected' ? 'selected' : '' }}>ไม่ผ่าน</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="position_id">
                            <option value="all">-- ตำแหน่งทั้งหมด --</option>
                            @foreach($positions as $position)
                                <option value="{{ $position->position_id }}" {{ request('position_id') == $position->position_id ? 'selected' : '' }}>
                                    {{ $position->position_name_th ?? $position->position_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> ค้นหา</button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('leads.index') }}" class="btn btn-secondary w-100"><i class="bi bi-arrow-clockwise"></i> รีเซ็ต</a>
                    </div>
                </div>
            </form>

            <div class="table-responsive card card-custom p-0">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" width="80">#</th>
                            <th class="text-center" width="80"><i class="bi bi-image me-1"></i></th>
                            <th> ชื่อ-นามสกุล</th>
                            <th>โทรศัพท์</th>
                            <th> ตำแหน่ง</th>
                            <th class="text-center">ประเทศ</th>
                            <th> ผู้ดูแล/สายแนะนำ</th>
                            <th class="text-center">
                                สถานะใบสมัคร
                                <i class="bi bi-info-circle text-primary ms-1" 
                                   style="cursor: help;"
                                   data-bs-toggle="tooltip" 
                                   data-bs-placement="top" 
                                   data-bs-html="true"
                                   title="<div style='text-align: left;'><strong>คำอธิบายสถานะใบสมัคร:</strong><br>
                                   • <strong>ร่าง</strong>: ยังไม่ดำเนินการส่งใบสมัคร<br>
                                   • <strong>ส่งแล้ว</strong>: ส่งให้นายจ้างแล้ว<br>
                                   • <strong>กำลังพิจารณา</strong>: นายจ้างกำลังพิจารณา<br>
                                   • <strong>นัดสัมภาษณ์</strong>: นายจ้างนัดสัมภาษณ์<br>
                                   • <strong>เสนองาน</strong>: นายจ้างเสนองาน/นายจ้างเลือก<br>
                                   • <strong>ตอบรับ</strong>: ได้งานแล้ว ผู้สมัครตอบรับงานแล้ว<br>
                                   • <strong>ปฏิเสธ</strong>: ไม่ผ่าน/นายจ้างไม่เลือก (ต้องระบุเหตุผล) *จะไม่สามารถสมัครงานนี้ใหม่ได้<br>
                                   • <strong>ถอน</strong>: ผู้สมัครถอนตัว (ต้องระบุเหตุผล) *จะไม่สามารถสมัครงานนี้ใหม่ได้</div>"></i>
                            </th>
                            <th class="text-center">
                               สถานะ Lead
                                <i class="bi bi-info-circle text-primary ms-1" 
                                   style="cursor: help;"
                                   data-bs-toggle="tooltip" 
                                   data-bs-placement="top" 
                                   data-bs-html="true"
                                   title="<div style='text-align: left;'><strong>คำอธิบายสถานะ Lead:</strong><br>
                                   • <strong>ใหม่</strong>: Lead ที่เพิ่งสร้างใหม่<br>
                                   • <strong>ติดต่อแล้ว</strong>: ได้ติดต่อ Lead แล้ว<br>
                                   • <strong>นัดสัมภาษณ์</strong>: นัดหมายสัมภาษณ์แล้ว<br>
                                   • <strong>ผ่านคุณสมบัติ</strong>: ผ่านการพิจารณาคุณสมบัติ<br>
                                   • <strong>Convert แล้ว</strong>: แปลงเป็น Labour แล้ว<br>
                                   • <strong>ไม่ผ่าน</strong>: ไม่ผ่านการพิจารณา</div>"></i>
                            </th>
                            <th class="text-center">วันที่สร้าง</th>
                            <th class="text-center" width="150"><i class="bi bi-gear me-1"></i> จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($leads as $key => $item)
                            <tr>
                                <td class="text-center">{{ $leads->firstItem() + $key }}</td>
                                <td class="text-center">
                                    @if($item->lead_photo)
                                        <img src="{{ asset('storage/' . $item->lead_photo) }}" 
                                             class="rounded-circle border cursor-pointer" 
                                             style="width: 50px; height: 50px; object-fit: cover; cursor: pointer;"
                                             alt="รูปถ่าย {{ $item->fullName }}"
                                             data-bs-toggle="modal"
                                             data-bs-target="#photoModal"
                                             onclick="showPhoto('{{ asset('storage/' . $item->lead_photo) }}', '{{ $item->fullName }}')"
                                             title="คลิกเพื่อดูรูปใหญ่">.
                                        
                                    @else
                                        <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px;">
                                            <i class="bi bi-person text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $item->fullName }}</strong>
                                    @if($item->lead_bmi)
                                        @php
                                            $bmi = $item->lead_bmi;
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
                                        <br>
                                        <small>
                                  
                                            <span class="badge bg-{{ $badgeClass }}" style="font-size: 0.65rem;">BMI : {{ number_format($bmi, 1) }} {{ $category }}</span>
                                        </small><br>
                                           <small>{{$item->lead_number}}</small>
                                    @endif
                                </td>
                                <td>{{ $item->lead_phone ?? '-' }}</td>
                                <td>
                                    @if($item->position)
                                        <span class="badge bg-info">{{ $item->position->position_name_th ?? $item->position->position_name ?? '' }}</span>
                                   
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($item->country)
                                        {{ $item->country->country_name_th ?? $item->country->country_name_en }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                      
                                            <small><b>ผู้ดูแล: </b>{{ $item->staff->staff_name?? '-' }}</small>
                                            <br>
                                             <small><b>สายแนะนำ: </b>{{ $item->recommenderStaff->staff_sub_name?? '-' }}</small>
                                      
                                    </td>
                                <td class="text-center">
                                    @php
                                        $jobLeadsCount = $item->jobLeads->count();
                                        $draftCount = $item->jobLeads->where('job_lead_status', 'ร่าง')->count();
                                        $sentCount = $item->jobLeads->where('job_lead_status', 'ส่งแล้ว')->count();
                                        $consideringCount = $item->jobLeads->where('job_lead_status', 'กำลังพิจารณา')->count();
                                        $interviewCount = $item->jobLeads->where('job_lead_status', 'นัดสัมภาษณ์')->count();
                                        $offerCount = $item->jobLeads->where('job_lead_status', 'เสนองาน')->count();
                                        $acceptedCount = $item->jobLeads->where('job_lead_status', 'ตอบรับ')->count();
                                        $rejectedCount = $item->jobLeads->where('job_lead_status', 'ปฏิเสธ')->count();
                                        $withdrawnCount = $item->jobLeads->where('job_lead_status', 'ถอน')->count();
                                    @endphp
                                    
                                    @if($jobLeadsCount > 0)
                                        <div class="d-flex flex-column gap-1 align-items-start">
                                            @if($draftCount > 0)
                                                <span class="badge bg-secondary" style="font-size: 0.7rem;">
                                                    <i class="bi bi-file-earmark"></i> ร่าง: {{ $draftCount }}
                                                </span>
                                            @endif
                                            @if($sentCount > 0)
                                                <span class="badge bg-info" style="font-size: 0.7rem;">
                                                    <i class="bi bi-send"></i> ส่งแล้ว: {{ $sentCount }}
                                                </span>
                                            @endif
                                            @if($consideringCount > 0)
                                                <span class="badge bg-primary" style="font-size: 0.7rem;">
                                                    <i class="bi bi-hourglass-split"></i> กำลังพิจารณา: {{ $consideringCount }}
                                                </span>
                                            @endif
                                            @if($interviewCount > 0)
                                                <span class="badge bg-warning" style="font-size: 0.7rem;">
                                                    <i class="bi bi-calendar-event"></i> นัดสัมภาษณ์: {{ $interviewCount }}
                                                </span>
                                            @endif
                                            @if($offerCount > 0)
                                                <span class="badge bg-info" style="font-size: 0.7rem;">
                                                    <i class="bi bi-briefcase"></i> เสนองาน: {{ $offerCount }}
                                                </span>
                                            @endif
                                            @if($acceptedCount > 0)
                                                <span class="badge bg-success" style="font-size: 0.7rem;">
                                                    <i class="bi bi-check-circle-fill"></i> ตอบรับ: {{ $acceptedCount }}
                                                </span>
                                            @endif
                                            @if($rejectedCount > 0)
                                                <span class="badge bg-danger" style="font-size: 0.7rem;">
                                                    <i class="bi bi-x-circle"></i> ปฏิเสธ: {{ $rejectedCount }}
                                                </span>
                                            @endif
                                            @if($withdrawnCount > 0)
                                                <span class="badge bg-dark" style="font-size: 0.7rem;">
                                                    <i class="bi bi-arrow-return-left"></i> ถอน: {{ $withdrawnCount }}
                                                </span>
                                            @endif
                                            <small class="text-muted mt-1"><strong>รวม: {{ $jobLeadsCount }} ใบสมัคร</strong></small>
                                        </div>
                                    @else
                                        <span class="badge bg-light text-dark border" style="font-size: 0.7rem;">
                                            <i class="bi bi-dash-circle"></i> ยังไม่มีใบสมัคร
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">{!! $item->statusBadge !!}</td>
                                <td class="text-center">{{ $item->created_at->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    <div class="btn-group dropstart">
                                        <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
                                            <i class="bi bi-gear"></i> จัดการ
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)" onclick="showLeadTimeline({{ $item->lead_id }}, '{{ $item->getFullNameAttribute() }}')">
                                                    <i class="bi bi-clock-history text-info"></i> ประวัติการดำเนินการ
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('leads.resume', $item->lead_id) }}" target="_blank">
                                                    <i class="bi bi-file-person text-secondary"></i> Resume/CV
                                                </a>
                                            </li>
                                            @can('view lead')
                                            <li>
                                                <a class="dropdown-item" href="{{ route('leads.show', $item->lead_id) }}">
                                                    <i class="bi bi-eye-fill text-primary"></i> ดูรายละเอียด
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('pdf.cv.form', $item->lead_id) }}" target="_blank">
                                                    <i class="bi bi-file-pdf-fill text-success"></i> Preview PDF
                                                </a>
                                            </li>
                                            @endcan
                                            @can('update lead')
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('leads.edit', $item->lead_id) }}">
                                                    <i class="bi bi-pencil-fill text-warning"></i> แก้ไข
                                                </a>
                                            </li>
                                            @endcan
                                            @can('delete lead')
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                @if($item->isConverted())
                                                    @can('delete converted lead')
                                                        <a class="dropdown-item text-danger" href="javascript:void(0)" 
                                                           onclick="if(confirm('Lead นี้ถูก Convert แล้ว คุณแน่ใจหรือไม่ว่าต้องการลบ?')) document.getElementById('delete-form-{{ $item->lead_id }}').submit();">
                                                            <i class="bi bi-trash-fill"></i> ลบ (Converted)
                                                        </a>
                                                        <form id="delete-form-{{ $item->lead_id }}" action="{{ route('leads.destroy', $item->lead_id) }}" method="POST" class="d-none">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    @else
                                                        <a class="dropdown-item disabled" href="javascript:void(0)">
                                                            <i class="bi bi-lock-fill"></i> ไม่มีสิทธิ์ลบ
                                                        </a>
                                                    @endcan
                                                @else
                                                    <a class="dropdown-item text-danger" href="javascript:void(0)" 
                                                       onclick="if(confirm('คุณแน่ใจหรือไม่ว่าต้องการลบผู้สนใจนี้?')) document.getElementById('delete-form-{{ $item->lead_id }}').submit();">
                                                        <i class="bi bi-trash-fill"></i> ลบ
                                                    </a>
                                                    <form id="delete-form-{{ $item->lead_id }}" action="{{ route('leads.destroy', $item->lead_id) }}" method="POST" class="d-none">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                @endif
                                            </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    ไม่มีข้อมูลผู้สนใจ
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                 {!! $leads->withQueryString()->links('pagination::bootstrap-5') !!}
            </div>
        </div>
    </div>
</div>

<!-- Photo Modal -->
<div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="photoModalLabel">
                    <i class="bi bi-image me-2"></i>รูปถ่าย: <span id="modalName"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalPhoto" src="" class="img-fluid rounded" alt="รูปถ่าย" style="max-height: 400px;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

<!-- Timeline Modal -->
<div class="modal fade" id="leadTimelineModal" tabindex="-1" aria-labelledby="leadTimelineModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="leadTimelineModalLabel">
                    <i class="bi bi-clock-history"></i> ประวัติการดำเนินการ
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="leadTimelineModalBody">
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
    function showPhoto(photoUrl, name) {
        document.getElementById('modalPhoto').src = photoUrl;
        document.getElementById('modalName').textContent = name;
    }
    
    // Show lead timeline function
    function showLeadTimeline(leadId, leadName) {
        const modal = new bootstrap.Modal(document.getElementById('leadTimelineModal'));
        const modalTitle = document.getElementById('leadTimelineModalLabel');
        const modalBody = document.getElementById('leadTimelineModalBody');
        
        modalTitle.innerHTML = `<i class="bi bi-clock-history"></i> ประวัติการดำเนินการ - ${leadName}`;
        modalBody.innerHTML = '<div class="text-center py-5"><div class="spinner-border" role="status"></div><p class="mt-2">กำลังโหลดข้อมูล...</p></div>';
        
        modal.show();
        
        // Fetch timeline data
        fetch(`{{ url('leads') }}/${leadId}/timeline`, {
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
    
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, { html: true });
        });
        
        // Custom tooltip styling
        var style = document.createElement('style');
        style.textContent = `.tooltip-inner { text-align: left !important; max-width: 500px !important; width: 500px; }`;
        document.head.appendChild(style);
    });
</script>

@endsection
