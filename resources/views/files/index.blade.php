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
                <h4 class="mb-0"><i class="bi bi-folder-fill me-2 text-primary"></i>จัดการประเภทเอกสาร</h4>
                @can('create file-manage')
                <a href="{{ route('file-manage.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle-fill"></i> เพิ่มประเภทเอกสาร
                </a>
                @endcan
            </div>

            <div class="table-responsive card card-custom p-0">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" width="80">#</th>
                            <th><i class="bi bi-folder me-1"></i> ชื่อประเภทเอกสาร</th>
                            <th class="text-center"><i class="bi bi-file-earmark me-1"></i> จำนวนรายการไฟล์</th>
                            <th class="text-center"><i class="bi bi-toggle-on me-1"></i> สถานะ</th>
                            <th class="text-center" width="250"><i class="bi bi-gear me-1"></i> จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($fileManages as $key => $item)
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td><strong>{{ $item->file_manage_name }}</strong></td>
                                <td class="text-center">
                                    <span class="badge bg-info">{{ $item->list_files_count }} รายการ</span>
                                </td>
                                <td class="text-center">
                                    @if ($item->file_manage_status === 'active')
                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> ใช้งาน</span>
                                    @else
                                        <span class="badge bg-secondary"><i class="bi bi-x-circle"></i> ไม่ใช้งาน</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        @can('view file-manage')
                                        <a href="{{ route('file-manage.show', $item->file_manage_id) }}" 
                                           class="btn btn-sm btn-outline-info" 
                                           title="ดูรายการไฟล์">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @endcan
                                        @can('update file-manage')
                                        <a href="{{ route('file-manage.edit', $item->file_manage_id) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="แก้ไข">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        @endcan
                                        @can('delete file-manage')
                                        <form action="{{ route('file-manage.destroy', $item->file_manage_id) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('คุณแน่ใจว่าต้องการลบประเภทเอกสารนี้?');">
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
                                    <p class="mt-2">ไม่มีข้อมูลประเภทเอกสาร</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
