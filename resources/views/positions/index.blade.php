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
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <h4 class="mb-0"><i class="bi bi-briefcase-fill me-2 text-primary"></i>จัดการตำแหน่งงาน</h4>
                @can('create position')
                <a href="{{ route('positions.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle-fill"></i> เพิ่มตำแหน่งงาน
                </a>
                @endcan
            </div>

            <div class="table-responsive card card-custom p-0">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" width="80">#</th>
                            <th><i class="bi bi-translate me-1"></i> ชื่อตำแหน่ง (EN)</th>
                            <th><i class="bi bi-chat-text me-1"></i> ชื่อตำแหน่ง (TH)</th>
                            <th><i class="bi bi-diagram-3 me-1"></i> กลุ่มงาน</th>
                            <th class="text-center"><i class="bi bi-toggle-on me-1"></i> สถานะ</th>
                            <th class="text-center" width="250"><i class="bi bi-gear me-1"></i> จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($positions as $key => $item)
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td><strong>{{ $item->position_name }}</strong></td>
                                <td>{{ $item->position_name_th ?? '-' }}</td>
                                <td>
                                    @if($item->jobGroup)
                                        <span class="badge bg-info">{{ $item->jobGroup->job_group_name }}</span>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($item->position_status == 'active')
                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> ใช้งาน</span>
                                    @else
                                        <span class="badge bg-secondary"><i class="bi bi-x-circle"></i> ไม่ใช้งาน</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        @can('view position')
                                        <a href="{{ route('positions.show', $item->position_id) }}" 
                                           class="btn btn-sm btn-outline-info" 
                                           title="ดูรายละเอียด">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        @endcan

                                        @can('update position')
                                        <a href="{{ route('positions.edit', $item->position_id) }}" 
                                           class="btn btn-sm btn-outline-warning" 
                                           title="แก้ไข">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        @endcan

                                        @can('delete position')
                                        <form action="{{ route('positions.destroy', $item->position_id) }}" 
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
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    ไม่มีข้อมูลตำแหน่งงาน
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
