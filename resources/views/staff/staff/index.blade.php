@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="bi bi-person-workspace me-2 text-primary"></i>จัดการรายชื่อสรรหา (Staff)
        </h4>
        <a href="{{ route('staff.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> เพิ่มเจ้าหน้าที่
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

    <!-- Search and Filter Card -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('staff.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">ค้นหา</label>
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}" 
                           placeholder="ชื่อเจ้าหน้าที่, ชื่อเล่น">
                </div>
                <div class="col-md-3">
                    <label class="form-label">สถานะ</label>
                    <select class="form-select" name="status">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>ทั้งหมด</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>ใช้งาน</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>ไม่ใช้งาน</option>
                    </select>
                </div>
                <div class="col-md-5 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-search"></i> ค้นหา
                    </button>
                    <a href="{{ route('staff.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i> รีเซ็ต
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-table me-2"></i>รายการเจ้าหน้าที่
                <small class="text-muted ms-2">({{ $staffs->total() }} รายการ)</small>
            </h5>
        </div>
        <div class="card-body">
            @if($staffs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 80px;">#</th>
                                <th>ชื่อเจ้าหน้าที่</th>
                                <th>ชื่อเล่น</th>
                                <th style="width: 100px;">สถานะ</th>
                                <th style="width: 100px;">จำนวน Lead</th>
                                <th style="width: 150px;">การจัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($staffs as $index => $staff)
                                @php
                                    $leadCount = DB::table('leads')->where('staff_id', $staff->staff_id)->count();
                                @endphp
                                <tr>
                                    <td>{{ $staffs->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                                {{ strtoupper(substr($staff->staff_name, 0, 1)) }}
                                            </div>
                                            <strong>{{ $staff->staff_name }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        @if($staff->staff_nickname)
                                            <span class="badge bg-light text-dark">{{ $staff->staff_nickname }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($staff->staff_status == 'active')
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>ใช้งาน
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="bi bi-x-circle me-1"></i>ไม่ใช้งาน
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $leadCount }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('staff.show', $staff->staff_id) }}" 
                                               class="btn btn-outline-info btn-sm" title="ดูรายละเอียด">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('staff.edit', $staff->staff_id) }}" 
                                               class="btn btn-outline-warning btn-sm" title="แก้ไข">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            @if($leadCount == 0)
                                                <button type="button" class="btn btn-outline-danger btn-sm" 
                                                        onclick="confirmDelete({{ $staff->staff_id }}, '{{ $staff->staff_name }}')" title="ลบ">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-outline-secondary btn-sm" 
                                                        disabled title="ไม่สามารถลบได้เนื่องจากมี Lead ที่ใช้งาน">
                                                    <i class="bi bi-shield-x"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $staffs->withQueryString()->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-person-x display-1 text-muted"></i>
                    <h5 class="mt-3 text-muted">ไม่พบข้อมูล</h5>
                    <p class="text-muted">ไม่มีข้อมูลเจ้าหน้าที่ในระบบ หรือลองเปลี่ยนคำค้นหา</p>
                    <a href="{{ route('staff.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>เพิ่มเจ้าหน้าที่คนแรก
                    </a>
                </div>
            @endif
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
                <div class="alert alert-warning">
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
                    location.reload();
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
.avatar-sm {
    width: 40px;
    height: 40px;
    font-weight: bold;
    font-size: 16px;
}
</style>
@endsection