@extends('layouts.main')
@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ $message }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Job Group Header -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0"><i class="bi bi-diagram-3 me-2"></i>{{ $jobGroup->job_group_name }}</h4>
                    <small>{{ $jobGroup->job_group_name_th ?? 'ไม่มีชื่อภาษาไทย' }}</small>
                </div>
                <a href="{{ route('positions.index') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left"></i> กลับไปเลือกกลุ่มงาน
                </a>
            </div>
        </div>
        @if($jobGroup->job_group_detail)
        <div class="card-body">
            <p class="mb-0 text-muted">{{ $jobGroup->job_group_detail }}</p>
        </div>
        @endif
    </div>

    <!-- Positions Management -->
    <div class="card card-custom">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <h5 class="mb-0"><i class="bi bi-briefcase-fill me-2 text-primary"></i>ตำแหน่งงานในกลุ่ม: {{ $jobGroup->job_group_name }}</h5>
                @can('create position')
                <a href="{{ route('positions.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle-fill"></i> เพิ่มตำแหน่งงาน
                </a>
                @endcan
            </div>

            @if($positions->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" width="80">#</th>
                            <th><i class="bi bi-translate me-1"></i> ชื่อตำแหน่ง (EN)</th>
                            <th><i class="bi bi-chat-text me-1"></i> ชื่อตำแหน่ง (TH)</th>
                            <th class="text-center"><i class="bi bi-toggle-on me-1"></i> สถานะ</th>
                            <th class="text-center" width="250"><i class="bi bi-gear me-1"></i> จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($positions as $key => $position)
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td><strong>{{ $position->position_name }}</strong></td>
                                <td>{{ $position->position_name_th ?? '-' }}</td>
                                <td class="text-center">
                                    @if ($position->position_status == 'active')
                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> ใช้งาน</span>
                                    @else
                                        <span class="badge bg-secondary"><i class="bi bi-x-circle"></i> ไม่ใช้งาน</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        @can('view position')
                                        <a href="{{ route('positions.show', $position->position_id) }}" 
                                           class="btn btn-sm btn-outline-info" 
                                           title="ดูรายละเอียด">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        @endcan

                                        @can('update position')
                                        <a href="{{ route('positions.edit', $position->position_id) }}" 
                                           class="btn btn-sm btn-outline-warning" 
                                           title="แก้ไข">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        @endcan

                                        @can('delete position')
                                        <form action="{{ route('positions.destroy', $position->position_id) }}" 
                                              method="POST" 
                                              class="d-inline" 
                                              onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบตำแหน่งนี้?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="ลบ">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <!-- No Positions -->
            <div class="text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted"></i>
                <h4 class="text-muted mt-3">ไม่มีตำแหน่งงานในกลุ่มนี้</h4>
                <p class="text-muted">ยังไม่มีตำแหน่งงานในกลุ่ม "{{ $jobGroup->job_group_name }}"</p>
                @can('create position')
                <a href="{{ route('positions.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> เพิ่มตำแหน่งงานใหม่
                </a>
                @endcan
            </div>
            @endif
        </div>
    </div>

<style>
.job-group-card:hover {
    transform: translateY(-2px);
    transition: all 0.2s ease-in-out;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
</style>
@endsection