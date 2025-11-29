@extends('layouts.main')
@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ $message }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card card-custom mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <h4 class="mb-0"><i class="bi bi-briefcase-fill me-2 text-primary"></i>จัดการตำแหน่งงาน - จัดกลุ่มตามประเภทงาน</h4>
                @can('create position')
                <a href="{{ route('positions.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle-fill"></i> เพิ่มตำแหน่งงาน
                </a>
                @endcan
            </div>

            <!-- Job Groups Grid -->
            <div class="row">
                @forelse ($jobGroups as $jobGroup)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 border-primary job-group-card">
                        <div class="card-header bg-light">
                            <h6 class="mb-1 text-primary">
                                <i class="bi bi-diagram-3 me-2"></i>{{ $jobGroup->job_group_name }}
                            </h6>
                            <small class="text-muted">{{ $jobGroup->job_group_name_th ?? 'ไม่มีชื่อภาษาไทย' }}</small>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <div class="bg-primary bg-opacity-10 rounded p-3">
                                    <h4 class="mb-0 text-primary">{{ $jobGroup->positions_count }}</h4>
                                    <small class="text-muted">ตำแหน่งงาน</small>
                                </div>
                            </div>
                            
                            @if($jobGroup->job_group_detail)
                            <p class="card-text small text-muted">
                                {{ Str::limit($jobGroup->job_group_detail, 80) }}
                            </p>
                            @endif
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="d-grid">
                                <a href="{{ route('positions.by-job-group', $jobGroup->job_group_id) }}" 
                                   class="btn btn-primary">
                                    <i class="bi bi-list-ul"></i> ดูตำแหน่งงาน ({{ $jobGroup->positions_count }})
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <h4 class="text-muted mt-3">ไม่พบกลุ่มงานที่มีตำแหน่ง</h4>
                        <p class="text-muted">ยังไม่มีกลุ่มงานที่มีตำแหน่งงานในระบบ</p>
                        @can('create position')
                        <a href="{{ route('positions.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> เพิ่มตำแหน่งงานใหม่
                        </a>
                        @endcan
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

<style>
.job-group-card:hover {
    transform: translateY(-2px);
    transition: all 0.2s ease-in-out;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    cursor: pointer;
}

.job-group-card .card-body {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
}
</style>
@endsection
