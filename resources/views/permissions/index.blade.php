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
                <h4 class="mb-0"><i class="bi bi-key-fill me-2 text-warning"></i>จัดการ Permissions</h4>
                <div class="d-flex gap-2">
                    <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> กลับไป Roles
                    </a>
                    <a href="{{ route('permissions.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle-fill"></i> เพิ่ม Permission ใหม่
                    </a>
                </div>
            </div>

            @php
                $groupedPermissions = $permissions->groupBy(function($permission) {
                    return explode(' ', $permission->name)[1] ?? 'อื่นๆ';
                });
            @endphp

            <div class="row">
                @foreach($groupedPermissions as $group => $perms)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 border-primary">
                            <div class="card-header bg-primary text-white">
                                <strong><i class="bi bi-folder2-open me-2"></i>{{ ucfirst($group) }}</strong>
                                <span class="badge bg-light text-dark float-end">{{ $perms->count() }}</span>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    @foreach($perms as $permission)
                                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                            <span>
                                                <i class="bi bi-check-circle text-success me-2"></i>
                                                {{ $permission->name }}
                                            </span>
                                            <div class="btn-group btn-group-sm">
                                                <span class="badge bg-info me-2">
                                                    {{ $permission->roles()->count() }} roles
                                                </span>
                                                <form action="{{ route('permissions.destroy', $permission->id) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('คุณแน่ใจว่าต้องการลบ Permission นี้?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="ลบ">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- สถิติ Permissions -->
    <div class="card card-custom">
        <div class="card-body">
            <h5 class="mb-3"><i class="bi bi-bar-chart-fill me-2"></i>สถิติ Permissions</h5>
            <div class="row">
                <div class="col-md-4">
                    <div class="card text-center border-info">
                        <div class="card-body">
                            <i class="bi bi-key-fill text-info fs-1"></i>
                            <h3 class="mt-2 text-info">{{ $permissions->count() }}</h3>
                            <p class="text-muted mb-0">Permissions ทั้งหมด</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center border-success">
                        <div class="card-body">
                            <i class="bi bi-folder2 text-success fs-1"></i>
                            <h3 class="mt-2 text-success">{{ $groupedPermissions->count() }}</h3>
                            <p class="text-muted mb-0">กลุ่ม Permissions</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center border-primary">
                        <div class="card-body">
                            <i class="bi bi-shield-check text-primary fs-1"></i>
                            <h3 class="mt-2 text-primary">{{ \Spatie\Permission\Models\Role::count() }}</h3>
                            <p class="text-muted mb-0">Roles ทั้งหมด</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
