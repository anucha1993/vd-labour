@extends('layouts.main')
@section('content')
    <div class="card card-custom mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0"><i class="bi bi-pencil-square me-2 text-primary"></i>แก้ไขประเภทเอกสาร</h4>
                <a href="{{ route('file-manage.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> กลับ
                </a>
            </div>

            <form action="{{ route('file-manage.update', $fileManage->file_manage_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="file_manage_name" class="form-label">ชื่อประเภทเอกสาร <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('file_manage_name') is-invalid @enderror" 
                               id="file_manage_name" 
                               name="file_manage_name" 
                               value="{{ old('file_manage_name', $fileManage->file_manage_name) }}" 
                               required>
                        @error('file_manage_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="file_manage_status" class="form-label">สถานะ <span class="text-danger">*</span></label>
                        <select class="form-select @error('file_manage_status') is-invalid @enderror" 
                                id="file_manage_status" 
                                name="file_manage_status" 
                                required>
                            <option value="">เลือกสถานะ</option>
                            <option value="active" {{ old('file_manage_status', $fileManage->file_manage_status) == 'active' ? 'selected' : '' }}>ใช้งาน</option>
                            <option value="inactive" {{ old('file_manage_status', $fileManage->file_manage_status) == 'inactive' ? 'selected' : '' }}>ไม่ใช้งาน</option>
                        </select>
                        @error('file_manage_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('file-manage.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> ยกเลิก
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> บันทึกการแก้ไข
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
