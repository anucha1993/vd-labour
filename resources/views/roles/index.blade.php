@extends('layouts.main')

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong><i class="bi bi-check-circle me-2"></i>{{ $message }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><i class="bi bi-exclamation-triangle me-2"></i>{{ $message }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card card-custom mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <h4 class="mb-0"><i class="bi bi-shield-lock-fill me-2 text-primary"></i>จัดการ Roles และ Permissions</h4>
                <div class="d-flex gap-2">
                    <a href="{{ route('permissions.index') }}" class="btn btn-warning">
                        <i class="bi bi-key-fill"></i> จัดการ Permissions
                    </a>
                    <a href="{{ route('roles.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle-fill"></i> เพิ่ม Role ใหม่
                    </a>
                </div>
            </div>

            <div class="table-responsive card card-custom p-0">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" width="80">#</th>
                            <th><i class="bi bi-shield me-1"></i> ชื่อ Role</th>
                            <th><i class="bi bi-key me-1"></i> Permissions</th>
                            <th class="text-center"><i class="bi bi-person-check me-1"></i> จำนวนผู้ใช้</th>
                            <th class="text-center" width="200"><i class="bi bi-gear me-1"></i> จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $key => $role)
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td>
                                    <strong class="text-primary">{{ ucfirst($role->name) }}</strong>
                                    @if($role->name === 'admin')
                                        <span class="badge bg-danger ms-2">Super Admin</span>
                                    @endif
                                </td>
                                <td>
                                    @if($role->permissions->count() > 0)
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($role->permissions->take(5) as $permission)
                                                <span class="badge bg-info">{{ ucfirst($permission->name) }}</span>
                                            @endforeach
                                            @if($role->permissions->count() > 5)
                                                <span class="badge bg-secondary">+{{ $role->permissions->count() - 5 }} เพิ่มเติม</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">ไม่มี Permissions</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success">{{ $role->users->count() ?? 0 }} คน</span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('roles.edit', $role->id) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="แก้ไข">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        @if($role->name !== 'admin')
                                            <form action="{{ route('roles.destroy', $role->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('คุณแน่ใจว่าต้องการลบ Role นี้?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="ลบ">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-outline-secondary" disabled title="ไม่สามารถลบ Admin ได้">
                                                <i class="bi bi-lock"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1"></i>
                                    <p class="mt-2">ไม่มีข้อมูล Roles</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Card แสดง Permissions ทั้งหมด -->
    <div class="card card-custom">
        <div class="card-body">
            <h5 class="mb-3"><i class="bi bi-key-fill me-2 text-warning"></i>Permissions ทั้งหมดในระบบ</h5>
            <div class="row">
                @php
                    $groupedPermissions = $permissions->groupBy(function($permission) {
                        return explode(' ', $permission->name)[1] ?? 'อื่นๆ';
                    });
                @endphp
                @foreach($groupedPermissions as $group => $perms)
                    <div class="col-md-3 mb-3">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-header bg-light">
                                <strong><i class="bi bi-folder2-open me-1"></i>{{ ucfirst($group) }}</strong>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-column gap-1">
                                    @foreach($perms as $perm)
                                        <span class="badge bg-secondary text-start">
                                            <i class="bi bi-check-circle me-1"></i>{{ $perm->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
