@extends('layouts.main')
@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ $message }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- ข้อมูลประเภทเอกสาร -->
    <div class="card card-custom mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0"><i class="bi bi-folder me-2 text-primary"></i>{{ $fileManage->file_manage_name }}</h4>
                <div class="d-flex gap-2">
                    @can('update file-manage')
                    <a href="{{ route('file-manage.edit', $fileManage->file_manage_id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil-square"></i> แก้ไข
                    </a>
                    @endcan
                    <a href="{{ route('file-manage.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> กลับ
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <p><strong>ชื่อประเภท:</strong> {{ $fileManage->file_manage_name }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>สถานะ:</strong> 
                        @if ($fileManage->file_manage_status === 'active')
                            <span class="badge bg-success">ใช้งาน</span>
                        @else
                            <span class="badge bg-secondary">ไม่ใช้งาน</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- รายการไฟล์ -->
    <div class="card card-custom mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0"><i class="bi bi-files me-2"></i>รายการไฟล์ในประเภทนี้</h5>
                @can('create file-manage')
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addFileModal">
                    <i class="bi bi-plus-circle"></i> เพิ่มรายการไฟล์
                </button>
                @endcan
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" width="80">#</th>
                            <th><i class="bi bi-file-earmark-text me-1"></i> ชื่อไฟล์</th>
                            <th><i class="bi bi-chat-left-text me-1"></i> หมายเหตุ</th>
                            <th class="text-center"><i class="bi bi-toggle-on me-1"></i> สถานะ</th>
                            <th class="text-center" width="150"><i class="bi bi-gear me-1"></i> จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($fileManage->listFiles as $key => $file)
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td><strong>{{ $file->list_file_name }}</strong></td>
                                <td>{{ $file->list_file_note ?? '-' }}</td>
                                <td class="text-center">
                                    @if ($file->list_file_status === 'active')
                                        <span class="badge bg-success">ใช้งาน</span>
                                    @else
                                        <span class="badge bg-secondary">ไม่ใช้งาน</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        @can('update file-manage')
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-primary edit-file-btn"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editFileModal"
                                                data-id="{{ $file->list_file_id }}"
                                                data-name="{{ $file->list_file_name }}"
                                                data-note="{{ $file->list_file_note }}"
                                                data-status="{{ $file->list_file_status }}"
                                                title="แก้ไข">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        @endcan
                                        @can('delete file-manage')
                                        <form action="{{ route('file-manage.destroy-list-file', [$fileManage->file_manage_id, $file->list_file_id]) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('คุณแน่ใจว่าต้องการลบรายการไฟล์นี้?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="ลบ">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1"></i>
                                    <p class="mt-2">ยังไม่มีรายการไฟล์</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal เพิ่มรายการไฟล์ -->
    <div class="modal fade" id="addFileModal" tabindex="-1" aria-labelledby="addFileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFileModalLabel">
                        <i class="bi bi-plus-circle me-2"></i>เพิ่มรายการไฟล์
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('file-manage.add-list-file', $fileManage->file_manage_id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="list_file_name" class="form-label">ชื่อไฟล์ <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="list_file_name" 
                                   name="list_file_name" 
                                   placeholder="เช่น passport, visa"
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="list_file_note" class="form-label">หมายเหตุ</label>
                            <textarea class="form-control" 
                                      id="list_file_note" 
                                      name="list_file_note" 
                                      rows="2"
                                      placeholder="คำอธิบายเพิ่มเติม"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="list_file_status" class="form-label">สถานะ <span class="text-danger">*</span></label>
                            <select class="form-select" id="list_file_status" name="list_file_status" required>
                                <option value="">เลือกสถานะ</option>
                                <option value="active" selected>ใช้งาน</option>
                                <option value="inactive">ไม่ใช้งาน</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i> ยกเลิก
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> บันทึก
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal แก้ไขรายการไฟล์ -->
    <div class="modal fade" id="editFileModal" tabindex="-1" aria-labelledby="editFileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFileModalLabel">
                        <i class="bi bi-pencil-square me-2"></i>แก้ไขรายการไฟล์
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editFileForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_list_file_name" class="form-label">ชื่อไฟล์ <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="edit_list_file_name" 
                                   name="list_file_name" 
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_list_file_note" class="form-label">หมายเหตุ</label>
                            <textarea class="form-control" 
                                      id="edit_list_file_note" 
                                      name="list_file_note" 
                                      rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_list_file_status" class="form-label">สถานะ <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_list_file_status" name="list_file_status" required>
                                <option value="">เลือกสถานะ</option>
                                <option value="active">ใช้งาน</option>
                                <option value="inactive">ไม่ใช้งาน</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i> ยกเลิก
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> บันทึกการแก้ไข
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // จัดการปุ่มแก้ไข
            const editButtons = document.querySelectorAll('.edit-file-btn');
            const editForm = document.getElementById('editFileForm');
            
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const fileId = this.dataset.id;
                    const fileName = this.dataset.name;
                    const fileNote = this.dataset.note;
                    const fileStatus = this.dataset.status;
                    
                    // ตั้งค่า action ของ form
                    editForm.action = "{{ route('file-manage.update-list-file', [$fileManage->file_manage_id, ':id']) }}".replace(':id', fileId);
                    
                    // ใส่ข้อมูลในฟอร์ม
                    document.getElementById('edit_list_file_name').value = fileName;
                    document.getElementById('edit_list_file_note').value = fileNote;
                    document.getElementById('edit_list_file_status').value = fileStatus;
                });
            });
        });
    </script>
@endsection
