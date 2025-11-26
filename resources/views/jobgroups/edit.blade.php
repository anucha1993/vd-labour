@extends('layouts.main')
@section('content')
    <div class="card card-custom mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0"><i class="bi bi-pencil-fill me-2 text-warning"></i>แก้ไขกลุ่มงาน</h4>
                <a href="{{ route('jobgroups.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> กลับ
                </a>
            </div>

            <form action="{{ route('jobgroups.update', $jobGroup->job_group_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="job_group_name" class="form-label">
                                <i class="bi bi-translate me-1"></i> ชื่อกลุ่มงาน (English) <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('job_group_name') is-invalid @enderror" 
                                   id="job_group_name" 
                                   name="job_group_name" 
                                   value="{{ old('job_group_name', $jobGroup->job_group_name) }}" 
                                   placeholder="กรอกชื่อกลุ่มงานเป็นภาษาอังกฤษ"
                                   required>
                            @error('job_group_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="job_group_name_th" class="form-label">
                                <i class="bi bi-chat-text me-1"></i> ชื่อกลุ่มงาน (ไทย) <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('job_group_name_th') is-invalid @enderror" 
                                   id="job_group_name_th" 
                                   name="job_group_name_th" 
                                   value="{{ old('job_group_name_th', $jobGroup->job_group_name_th) }}" 
                                   placeholder="กรอกชื่อกลุ่มงานเป็นภาษาไทย"
                                   required>
                            @error('job_group_name_th')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="job_group_status" class="form-label">
                                <i class="bi bi-toggle-on me-1"></i> สถานะ <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('job_group_status') is-invalid @enderror" 
                                    id="job_group_status" 
                                    name="job_group_status" 
                                    required>
                                <option value="active" {{ old('job_group_status', $jobGroup->job_group_status) == 'active' ? 'selected' : '' }}>ใช้งาน</option>
                                <option value="disable" {{ old('job_group_status', $jobGroup->job_group_status) == 'disable' ? 'selected' : '' }}>ไม่ใช้งาน</option>
                            </select>
                            @error('job_group_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('jobgroups.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> ยกเลิก
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save"></i> บันทึก
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
