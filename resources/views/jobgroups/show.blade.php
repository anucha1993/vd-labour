@extends('layouts.main')
@section('content')
    <div class="card card-custom mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0"><i class="bi bi-eye-fill me-2 text-info"></i>รายละเอียดกลุ่มงาน</h4>
                <div>
                    @can('update job-group')
                    <a href="{{ route('jobgroups.edit', $jobGroup->job_group_id) }}" class="btn btn-warning me-2">
                        <i class="bi bi-pencil-fill"></i> แก้ไข
                    </a>
                    @endcan
                    <a href="{{ route('jobgroups.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> กลับ
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card card-custom mb-3">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">
                                <i class="bi bi-translate me-1"></i> ชื่อกลุ่มงาน (English)
                            </h6>
                            <p class="fs-5 mb-0">{{ $jobGroup->job_group_name }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-custom mb-3">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">
                                <i class="bi bi-chat-text me-1"></i> ชื่อกลุ่มงาน (ไทย)
                            </h6>
                            <p class="fs-5 mb-0">{{ $jobGroup->job_group_name_th ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card card-custom mb-3">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">
                                <i class="bi bi-toggle-on me-1"></i> สถานะ
                            </h6>
                            <p class="fs-5 mb-0">
                                @if ($jobGroup->job_group_status == 'active')
                                    <span class="badge bg-success fs-6"><i class="bi bi-check-circle"></i> ใช้งาน</span>
                                @else
                                    <span class="badge bg-secondary fs-6"><i class="bi bi-x-circle"></i> ไม่ใช้งาน</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @can('delete job-group')
            <div class="d-flex justify-content-end mt-3">
                <form action="{{ route('jobgroups.destroy', $jobGroup->job_group_id) }}" 
                      method="POST" 
                      onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบกลุ่มงานนี้?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash-fill"></i> ลบกลุ่มงานนี้
                    </button>
                </form>
            </div>
            @endcan
        </div>
    </div>
@endsection
