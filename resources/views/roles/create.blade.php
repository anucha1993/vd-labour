@extends('layouts.main')

@section('content')
    <div class="card card-custom mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0"><i class="bi bi-plus-circle me-2 text-primary"></i>เพิ่ม Role ใหม่</h4>
                <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> กลับ
                </a>
            </div>

            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="role_name" class="form-label">ชื่อ Role <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('role_name') is-invalid @enderror" 
                               id="role_name" 
                               name="role_name" 
                               value="{{ old('role_name') }}" 
                               placeholder="เช่น Manager, Supervisor"
                               required>
                        @error('role_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">ชื่อ Role ต้องไม่ซ้ำกับที่มีอยู่ในระบบ</small>
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="mb-3"><i class="bi bi-key me-2"></i>กำหนด Permissions</h5>
                
                @php
                    $groupedPermissions = $permissions->groupBy(function($permission) {
                        return explode(' ', $permission->name)[1] ?? 'อื่นๆ';
                    });
                @endphp

                <div class="row">
                    @foreach($groupedPermissions as $group => $perms)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 border-primary">
                                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                    <strong><i class="bi bi-folder2-open me-2"></i>{{ ucfirst($group) }}</strong>
                                    <div class="form-check">
                                        <input type="checkbox" 
                                               class="form-check-input select-all" 
                                               id="select_all_{{ $group }}"
                                               data-group="{{ $group }}"
                                               style="cursor: pointer;">
                                        <label class="form-check-label small text-white" for="select_all_{{ $group }}" style="cursor: pointer;">
                                            เลือกทั้งหมด
                                        </label>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @foreach($perms as $permission)
                                        <div class="form-check mb-2">
                                            <input type="checkbox" 
                                                   name="permissions[]" 
                                                   value="{{ $permission->name }}" 
                                                   class="form-check-input permission-checkbox group-{{ $group }}"
                                                   id="perm_{{ $permission->id }}">
                                            <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                <i class="bi bi-check-circle me-1"></i>{{ $permission->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> ยกเลิก
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> สร้าง Role
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select All checkbox functionality
            document.querySelectorAll('.select-all').forEach(function(selectAll) {
                selectAll.addEventListener('change', function() {
                    const group = this.dataset.group;
                    const checkboxes = document.querySelectorAll('.group-' + group);
                    checkboxes.forEach(function(checkbox) {
                        checkbox.checked = selectAll.checked;
                    });
                });
            });

            // Update Select All checkbox when individual checkboxes change
            document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const group = this.classList[1].replace('group-', '');
                    const allCheckboxes = document.querySelectorAll('.group-' + group);
                    const selectAllCheckbox = document.getElementById('select_all_' + group);
                    const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);
                    selectAllCheckbox.checked = allChecked;
                });
            });
        });
    </script>
@endsection
