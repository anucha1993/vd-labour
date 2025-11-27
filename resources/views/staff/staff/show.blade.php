@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="bi bi-eye me-2 text-info"></i>รายละเอียดเจ้าหน้าที่: {{ $staff->staff_name }}
        </h4>
        <div class="d-flex gap-2">
            <a href="{{ route('staff.edit', $staff->staff_id) }}" class="btn btn-warning">
                <i class="bi bi-pencil-square"></i> แก้ไข
            </a>
            <a href="{{ route('staff.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> กลับ
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- ข้อมูลหลัก -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-person-badge me-2"></i>ข้อมูลเจ้าหน้าที่
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-4">
                                    {{ strtoupper(substr($staff->staff_name, 0, 2)) }}
                                </div>
                                <div>
                                    <label class="form-label text-muted small">ชื่อเจ้าหน้าที่</label>
                                    <div class="fw-bold fs-4 text-primary">{{ $staff->staff_name }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label text-muted small">ชื่อเล่น</label>
                            <div class="fw-bold">
                                @if($staff->staff_nickname)
                                    <span class="badge bg-light text-dark fs-6 px-3 py-2">
                                        <i class="bi bi-chat-square-text me-1"></i>{{ $staff->staff_nickname }}
                                    </span>
                                @else
                                    <span class="text-muted">
                                        <i class="bi bi-dash-circle me-2"></i>ไม่ระบุ
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label text-muted small">สถานะ</label>
                            <div>
                                @if($staff->staff_status == 'active')
                                    <span class="badge bg-success fs-6 px-3 py-2">
                                        <i class="bi bi-check-circle me-1"></i>ใช้งาน
                                    </span>
                                @else
                                    <span class="badge bg-danger fs-6 px-3 py-2">
                                        <i class="bi bi-x-circle me-1"></i>ไม่ใช้งาน
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- สถิติการใช้งาน -->
            @php
                $leadCount = DB::table('leads')->where('staff_id', $staff->staff_id)->count();
                $activeLeadCount = DB::table('leads')->where('staff_id', $staff->staff_id)->whereIn('lead_status', ['new', 'contacted', 'interview', 'qualified'])->count();
            @endphp
            
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up me-2"></i>สถิติการทำงาน
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div class="border rounded p-3 bg-light">
                                <i class="bi bi-people-fill display-4 text-primary"></i>
                                <h3 class="mt-2 text-primary">{{ $leadCount }}</h3>
                                <p class="text-muted mb-0">Lead ทั้งหมด</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3 bg-light">
                                <i class="bi bi-clock-history display-4 text-warning"></i>
                                <h3 class="mt-2 text-warning">{{ $activeLeadCount }}</h3>
                                <p class="text-muted mb-0">Lead ที่ดำเนินการ</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3 bg-light">
                                <i class="bi bi-calendar-check display-4 text-success"></i>
                                <h3 class="mt-2 text-success">{{ $staff->created_at->diffForHumans() }}</h3>
                                <p class="text-muted mb-0">เข้าร่วมเมื่อ</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lead ล่าสุด -->
            @if($leadCount > 0)
                @php
                    $recentLeads = DB::table('leads')
                        ->where('staff_id', $staff->staff_id)
                        ->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get();
                @endphp
                
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-list-ul me-2"></i>Lead ล่าสุด (5 รายการ)
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>ชื่อ</th>
                                        <th>โทรศัพท์</th>
                                        <th>สถานะ</th>
                                        <th>วันที่สร้าง</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentLeads as $lead)
                                        <tr>
                                            <td>
                                                <a href="{{ route('leads.show', $lead->lead_id) }}" class="text-decoration-none">
                                                    {{ $lead->lead_firstname }} {{ $lead->lead_lastname }}
                                                </a>
                                            </td>
                                            <td>{{ $lead->lead_phone ?? '-' }}</td>
                                            <td>
                                                @switch($lead->lead_status)
                                                    @case('new')
                                                        <span class="badge bg-primary">ใหม่</span>
                                                        @break
                                                    @case('contacted')
                                                        <span class="badge bg-info">ติดต่อแล้ว</span>
                                                        @break
                                                    @case('interview')
                                                        <span class="badge bg-warning">นัดสัมภาษณ์</span>
                                                        @break
                                                    @case('qualified')
                                                        <span class="badge bg-success">ผ่านคุณสมบัติ</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">{{ $lead->lead_status }}</span>
                                                @endswitch
                                            </td>
                                            <td>{{ date('d/m/Y', strtotime($lead->created_at)) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('leads.index') }}?staff_id={{ $staff->staff_id }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-list"></i> ดู Lead ทั้งหมด
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-4">
            <!-- การจัดการ -->
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-gear me-2"></i>การจัดการ
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('staff.edit', $staff->staff_id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil-square me-2"></i>แก้ไขข้อมูล
                        </a>
                        @if($leadCount == 0)
                            <button type="button" class="btn btn-danger" 
                                    onclick="confirmDelete({{ $staff->staff_id }}, '{{ $staff->staff_name }}')">
                                <i class="bi bi-trash me-2"></i>ลบข้อมูล
                            </button>
                        @else
                            <button type="button" class="btn btn-outline-danger" disabled title="ไม่สามารถลบได้เนื่องจากมี Lead ที่ใช้งาน">
                                <i class="bi bi-shield-x me-2"></i>ไม่สามารถลบได้
                            </button>
                        @endif
                        <a href="{{ route('staff.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>กลับหน้าหลัก
                        </a>
                    </div>
                </div>
            </div>

            <!-- ข้อมูลระบบ -->
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>ข้อมูลระบบ
                    </h5>
                </div>
                <div class="card-body">
                    <small class="text-muted">
                        <div class="mb-2">
                            <strong>Staff ID:</strong> #{{ $staff->staff_id }}
                        </div>
                        <div class="mb-2">
                            <strong>สร้างเมื่อ:</strong><br>
                            {{ $staff->created_at->format('d/m/Y H:i:s') }}
                        </div>
                        <div>
                            <strong>อัปเดตล่าสุด:</strong><br>
                            {{ $staff->updated_at->format('d/m/Y H:i:s') }}
                        </div>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>ยืนยันการลบ
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>คุณแน่ใจหรือไม่ที่จะลบเจ้าหน้าที่ <strong id="deleteName"></strong>?</p>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>คำเตือน:</strong> การลบนี้ไม่สามารถย้อนกลับได้
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="bi bi-trash me-2"></i>ลบ
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let deleteId = null;
    
    function confirmDelete(id, name) {
        deleteId = id;
        document.getElementById('deleteName').textContent = name;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
    
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (deleteId) {
            fetch(`/staff/${deleteId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '{{ route("staff.index") }}';
                } else {
                    alert('เกิดข้อผิดพลาด: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('เกิดข้อผิดพลาดในการลบ');
            });
        }
        
        bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
    });
</script>
@endpush

<style>
.avatar-lg {
    width: 80px;
    height: 80px;
    font-weight: bold;
    font-size: 24px;
}
</style>
@endsection