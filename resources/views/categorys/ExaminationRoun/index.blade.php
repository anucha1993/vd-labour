@extends('layouts.main')
@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ $message }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ $message }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="mb-3">
                        <i class="bi bi-calendar-check me-2"></i>ข้อมูลรอบสอบ
                        @can('create examination-round')
                        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#createModal">
                            <i class="bi bi-plus-circle me-1"></i>เพิ่มรอบสอบ
                        </button>
                        @endcan
                    </h4>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered align-middle datatable" id="examination-round-table">
                        <thead class="table-primary text-center align-middle">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>วันที่สอบ</th>
                                <th>สถานที่สอบ</th>
                                <th>สถานะ</th>
                                <th>วันที่สร้าง</th>
                                <th style="width: 180px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ExaminationRoun as $key => $item)
                                <tr>
                                    <td class="text-center">{{ $key + 1 }}</td>
                                    <td>{{ date('d-m-Y', strtotime($item->examination_round_name)) }}</td>
                                    <td>{{ $item->examination_round_note ?? '-' }}</td>
                                    <td class="text-center">
                                        @if($item->examination_round_status === 'active')
                                            <span class="badge bg-success">เปิดใช้งาน</span>
                                        @elseif($item->examination_round_status === 'disable')
                                            <span class="badge bg-secondary">ปิดใช้งาน</span>
                                        @else
                                            <span class="badge bg-danger">ยกเลิก</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ date('d-m-Y H:i', strtotime($item->created_at)) }}</td>
                                    <td class="text-center">
                                        @can('update examination-round')
                                        <a href="{{ route('category.examination.edit', $item->examination_round_id) }}" 
                                           class="btn btn-warning btn-sm" title="แก้ไข">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        @endcan

                                        @can('delete examination-round')
                                        <form action="{{ route('category.examination.destroy', $item->examination_round_id) }}" 
                                              method="POST" style="display: inline-block;"
                                              onsubmit="return confirm('ยืนยันการลบรอบสอบนี้?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="ลบ">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">ไม่มีข้อมูล</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createModalLabel">
                        <i class="bi bi-plus-circle me-2"></i>เพิ่มรอบสอบ
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('category.examination.store') }}" method="POST" id="createForm">
                        @csrf
                        <div class="mb-3">
                            <label for="examination_round_name" class="form-label">วันที่สอบ <span class="text-danger">*</span></label>
                            <input type="date" name="examination_round_name" class="form-control" id="examination_round_name" required>
                        </div>

                        <div class="mb-3">
                            <label for="examination_round_note" class="form-label">สถานที่สอบ</label>
                            <input type="text" class="form-control" name="examination_round_note" id="examination_round_note" placeholder="ระบุสถานที่สอบ">
                        </div>

                        <div class="mb-3">
                            <label for="examination_round_status" class="form-label">สถานะ <span class="text-danger">*</span></label>
                            <select name="examination_round_status" class="form-select" id="examination_round_status" required>
                                <option value="active" selected>เปิดใช้งาน</option>
                                <option value="disable">ปิดใช้งาน</option>
                            </select>
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-1"></i>ยกเลิก
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>บันทึก
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    @if(isset($editItem))
    <div class="modal fade show" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="editModalLabel">
                        <i class="bi bi-pencil-square me-2"></i>แก้ไขรอบสอบ
                    </h5>
                    <a href="{{ route('category.examination') }}" class="btn-close"></a>
                </div>
                <div class="modal-body">
                    <form action="{{ route('category.examination.update', $editItem->examination_round_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="edit_examination_round_name" class="form-label">วันที่สอบ <span class="text-danger">*</span></label>
                            <input type="date" name="examination_round_name" class="form-control" id="edit_examination_round_name" 
                                   value="{{ $editItem->examination_round_name }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit_examination_round_note" class="form-label">สถานที่สอบ</label>
                            <input type="text" class="form-control" name="examination_round_note" id="edit_examination_round_note" 
                                   value="{{ $editItem->examination_round_note }}" placeholder="ระบุสถานที่สอบ">
                        </div>

                        <div class="mb-3">
                            <label for="edit_examination_round_status" class="form-label">สถานะ <span class="text-danger">*</span></label>
                            <select name="examination_round_status" class="form-select" id="edit_examination_round_status" required>
                                <option value="active" {{ $editItem->examination_round_status === 'active' ? 'selected' : '' }}>เปิดใช้งาน</option>
                                <option value="disable" {{ $editItem->examination_round_status === 'disable' ? 'selected' : '' }}>ปิดใช้งาน</option>
                            </select>
                        </div>

                        <div class="text-end">
                            <a href="{{ route('category.examination') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i>ยกเลิก
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-save me-1"></i>บันทึกการแก้ไข
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif

    <script>
        $(function() {
            $('#examination-round-table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/th.json'
                },
                order: [[4, 'desc']]
            });
        });
    </script>
@endsection
