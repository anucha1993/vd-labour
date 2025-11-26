@extends('layouts.main')
@section('content')
    <div class="card card-custom mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0"><i class="bi bi-eye-fill me-2 text-info"></i>รายละเอียดตำแหน่งงาน</h4>
                <div>
                    @can('update position')
                    <a href="{{ route('positions.edit', $position->position_id) }}" class="btn btn-warning me-2">
                        <i class="bi bi-pencil-fill"></i> แก้ไข
                    </a>
                    @endcan
                    <a href="{{ route('positions.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> กลับ
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card card-custom mb-3">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">
                                <i class="bi bi-translate me-1"></i> ชื่อตำแหน่ง (English)
                            </h6>
                            <p class="fs-5 mb-0">{{ $position->position_name }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-custom mb-3">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">
                                <i class="bi bi-chat-text me-1"></i> ชื่อตำแหน่ง (ไทย)
                            </h6>
                            <p class="fs-5 mb-0">{{ $position->position_name_th ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card card-custom mb-3">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">
                                <i class="bi bi-diagram-3 me-1"></i> กลุ่มงาน
                            </h6>
                            <p class="fs-5 mb-0">
                                @if($position->jobGroup)
                                    <span class="badge bg-info fs-6">{{ $position->jobGroup->job_group_name }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-custom mb-3">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">
                                <i class="bi bi-toggle-on me-1"></i> สถานะ
                            </h6>
                            <p class="fs-5 mb-0">
                                @if ($position->position_status == 'active')
                                    <span class="badge bg-success fs-6"><i class="bi bi-check-circle"></i> ใช้งาน</span>
                                @else
                                    <span class="badge bg-secondary fs-6"><i class="bi bi-x-circle"></i> ไม่ใช้งาน</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @can('delete position')
            <div class="d-flex justify-content-end mt-3">
                <form action="{{ route('positions.destroy', $position->position_id) }}" 
                      method="POST" 
                      onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบตำแหน่งนี้?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash-fill"></i> ลบตำแหน่งนี้
                    </button>
                </form>
            </div>
            @endcan
        </div>
    </div>
@endsection
