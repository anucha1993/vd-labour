@extends('layouts.main')
@section('content')
    <div class="card card-custom mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0"><i class="bi bi-plus-circle-fill me-2 text-primary"></i>เพิ่มตำแหน่งงาน</h4>
                <a href="{{ route('positions.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> กลับ
                </a>
            </div>

            <form action="{{ route('positions.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="position_name" class="form-label">
                                <i class="bi bi-translate me-1"></i> ชื่อตำแหน่ง (English) <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('position_name') is-invalid @enderror" 
                                   id="position_name" 
                                   name="position_name" 
                                   value="{{ old('position_name') }}" 
                                   placeholder="กรอกชื่อตำแหน่งเป็นภาษาอังกฤษ"
                                   required>
                            @error('position_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="position_name_th" class="form-label">
                                <i class="bi bi-chat-text me-1"></i> ชื่อตำแหน่ง (ไทย) <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('position_name_th') is-invalid @enderror" 
                                   id="position_name_th" 
                                   name="position_name_th" 
                                   value="{{ old('position_name_th') }}" 
                                   placeholder="กรอกชื่อตำแหน่งเป็นภาษาไทย"
                                   required>
                            @error('position_name_th')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="job_group_id" class="form-label">
                                <i class="bi bi-diagram-3 me-1"></i> กลุ่มงาน <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('job_group_id') is-invalid @enderror" 
                                    id="job_group_id" 
                                    name="job_group_id" 
                                    required>
                                <option value="">-- เลือกกลุ่มงาน --</option>
                                @foreach ($jobGroups as $jobGroup)
                                    <option value="{{ $jobGroup->job_group_id }}" 
                                            {{ old('job_group_id') == $jobGroup->job_group_id ? 'selected' : '' }}>
                                        {{ $jobGroup->job_group_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('job_group_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="position_status" class="form-label">
                                <i class="bi bi-toggle-on me-1"></i> สถานะ <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('position_status') is-invalid @enderror" 
                                    id="position_status" 
                                    name="position_status" 
                                    required>
                                <option value="active" {{ old('position_status', 'active') == 'active' ? 'selected' : '' }}>ใช้งาน</option>
                                <option value="disable" {{ old('position_status') == 'disable' ? 'selected' : '' }}>ไม่ใช้งาน</option>
                            </select>
                            @error('position_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('positions.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> ยกเลิก
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> บันทึก
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
