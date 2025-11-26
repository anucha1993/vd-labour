@extends('layouts.main')

@section('content')
    <div class="card card-custom mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">
                    <i class="bi bi-pencil-square me-2 text-primary"></i>แก้ไข Role: 
                    <strong class="text-primary">{{ ucfirst($role->name) }}</strong>
                </h4>
                <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> กลับ
                </a>
            </div>

            <form action="{{ route('roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="role_name" class="form-label">ชื่อ Role <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('role_name') is-invalid @enderror" 
                               id="role_name" 
                               name="role_name" 
                               value="{{ old('role_name', $role->name) }}" 
                               required
                               {{ $role->name === 'admin' ? 'readonly' : '' }}>
                        @error('role_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($role->name === 'admin')
                            <small class="text-danger">ไม่สามารถแก้ไขชื่อ Admin Role ได้</small>
                        @endif
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">สถิติ</label>
                        <div class="card border-info">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="bi bi-person-check-fill text-success fs-4"></i>
                                        <span class="ms-2">จำนวนผู้ใช้:</span>
                                    </div>
                                    <strong class="text-success">{{ $role->users->count() ?? 0 }} คน</strong>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <div>
                                        <i class="bi bi-key-fill text-primary fs-4"></i>
                                        <span class="ms-2">Permissions:</span>
                                    </div>
                                    <strong class="text-primary">{{ $role->permissions->count() }} รายการ</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0"><i class="bi bi-key me-2"></i>กำหนด Permissions</h5>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="select_all_permissions">
                        <label class="form-check-label" for="select_all_permissions">
                            <strong>เลือกทั้งหมด</strong>
                        </label>
                    </div>
                </div>
                
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
                                               class="form-check-input select-all-group" 
                                               id="select_all_{{ $group }}"
                                               data-group="{{ $group }}"
                                               style="cursor: pointer;">
                                        <label class="form-check-label small text-white" for="select_all_{{ $group }}" style="cursor: pointer;">
                                            ทั้งหมด
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
                                                   id="perm_{{ $permission->id }}"
                                                   {{ $role->permissions->contains($permission) ? 'checked' : '' }}>
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
                        <i class="bi bi-save"></i> บันทึกการแก้ไข
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select All Permissions
            document.getElementById('select_all_permissions').addEventListener('change', function() {
                document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
                    checkbox.checked = this.checked;
                }, this);
                document.querySelectorAll('.select-all-group').forEach(function(checkbox) {
                    checkbox.checked = this.checked;
                }, this);
            });

            // Select All by Group
            document.querySelectorAll('.select-all-group').forEach(function(selectAll) {
                selectAll.addEventListener('change', function() {
                    const group = this.dataset.group;
                    const checkboxes = document.querySelectorAll('.group-' + group);
                    checkboxes.forEach(function(checkbox) {
                        checkbox.checked = selectAll.checked;
                    });
                    updateSelectAllMain();
                });
            });

            // Update Select All when individual checkboxes change
            document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const group = this.classList[1].replace('group-', '');
                    const allCheckboxes = document.querySelectorAll('.group-' + group);
                    const selectAllCheckbox = document.getElementById('select_all_' + group);
                    const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);
                    selectAllCheckbox.checked = allChecked;
                    updateSelectAllMain();
                });
            });

            // Update main Select All checkbox
            function updateSelectAllMain() {
                const allCheckboxes = document.querySelectorAll('.permission-checkbox');
                const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);
                document.getElementById('select_all_permissions').checked = allChecked;
            }

            // Initialize Select All checkboxes
            @foreach($groupedPermissions as $group => $perms)
                const group_{{ str_replace(' ', '_', $group) }}_checkboxes = document.querySelectorAll('.group-{{ $group }}');
                const group_{{ str_replace(' ', '_', $group) }}_allChecked = Array.from(group_{{ str_replace(' ', '_', $group) }}_checkboxes).every(cb => cb.checked);
                document.getElementById('select_all_{{ $group }}').checked = group_{{ str_replace(' ', '_', $group) }}_allChecked;
            @endforeach
            
            updateSelectAllMain();
        });
    </script>
@endsection
