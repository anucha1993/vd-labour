@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="bi bi-eye me-2 text-info"></i>รายละเอียดสายหางาน: {{ $staffSub->staff_sub_name }}
        </h4>
        <div class="d-flex gap-2">
            <a href="{{ route('staff-sub.edit', $staffSub->staff_sub_id) }}" class="btn btn-warning">
                <i class="bi bi-pencil-square"></i> แก้ไข
            </a>
            <a href="{{ route('staff-sub.index') }}" class="btn btn-secondary">
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
                        <i class="bi bi-person-badge me-2"></i>ข้อมูลสายหางาน
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label text-muted small">ชื่อสายหางาน</label>
                            <div class="fw-bold fs-5 text-primary">
                                <i class="bi bi-person-lines-fill me-2"></i>{{ $staffSub->staff_sub_name }}
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label text-muted small">เบอร์โทรศัพท์</label>
                            <div class="fw-bold">
                                @if($staffSub->staff_sub_phone)
                                    <i class="bi bi-telephone me-2 text-success"></i>
                                    <a href="tel:{{ $staffSub->staff_sub_phone }}" class="text-decoration-none">
                                        {{ $staffSub->staff_sub_phone }}
                                    </a>
                                @else
                                    <span class="text-muted">
                                        <i class="bi bi-dash-circle me-2"></i>ไม่ระบุ
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label text-muted small">ชื่อเจ้าหน้าที่</label>
                            <div class="fw-bold">
                                @if($staffSub->staff_sub_staff)
                                    <i class="bi bi-person me-2 text-info"></i>{{ $staffSub->staff_sub_staff }}
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
                                @if($staffSub->staff_sub_status == 'active')
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

            <!-- ประวัติการใช้งาน -->
            @php
                $leadCount = DB::table('leads')->where('lead_recommender_staff_sub_id', $staffSub->staff_sub_id)->count();
            @endphp
            
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up me-2"></i>สถิติการใช้งาน
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-6">
                            <div class="border rounded p-3 bg-light">
                                <i class="bi bi-people-fill display-4 text-primary"></i>
                                <h3 class="mt-2 text-primary">{{ $leadCount }}</h3>
                                <p class="text-muted mb-0">Lead ที่แนะนำ</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 bg-light">
                                <i class="bi bi-calendar-check display-4 text-success"></i>
                                <h3 class="mt-2 text-success">{{ $staffSub->created_at->diffForHumans() }}</h3>
                                <p class="text-muted mb-0">สร้างเมื่อ</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                        <a href="{{ route('staff-sub.edit', $staffSub->staff_sub_id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil-square me-2"></i>แก้ไขข้อมูล
                        </a>
                        @if($leadCount == 0)
                            <button type="button" class="btn btn-danger" 
                                    onclick="confirmDelete({{ $staffSub->staff_sub_id }}, '{{ $staffSub->staff_sub_name }}')">
                                <i class="bi bi-trash me-2"></i>ลบข้อมูล
                            </button>
                        @else
                            <button type="button" class="btn btn-outline-danger" disabled title="ไม่สามารถลบได้เนื่องจากมี Lead ที่ใช้งาน">
                                <i class="bi bi-shield-x me-2"></i>ไม่สามารถลบได้
                            </button>
                        @endif
                        <a href="{{ route('staff-sub.index') }}" class="btn btn-secondary">
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
                            <strong>ID:</strong> #{{ $staffSub->staff_sub_id }}
                        </div>
                        <div class="mb-2">
                            <strong>สร้างเมื่อ:</strong><br>
                            {{ $staffSub->created_at->format('d/m/Y H:i:s') }}
                        </div>
                        <div>
                            <strong>อัปเดตล่าสุด:</strong><br>
                            {{ $staffSub->updated_at->format('d/m/Y H:i:s') }}
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
                <p>คุณแน่ใจหรือไม่ที่จะลบสายหางาน <strong id="deleteName"></strong>?</p>
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
            fetch(`/staff-sub/${deleteId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '{{ route("staff-sub.index") }}';
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
@endsection