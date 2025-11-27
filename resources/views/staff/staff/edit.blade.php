@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="bi bi-pencil-square me-2 text-warning"></i>แก้ไขเจ้าหน้าที่: {{ $staff->staff_name }}
        </h4>
        <div class="d-flex gap-2">
            <a href="{{ route('staff.show', $staff->staff_id) }}" class="btn btn-info">
                <i class="bi bi-eye"></i> ดูรายละเอียด
            </a>
            <a href="{{ route('staff.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> กลับ
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <h6><i class="bi bi-exclamation-triangle me-2"></i>กรุณาแก้ไขข้อผิดพลาดต่อไปนี้:</h6>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-form me-2"></i>แก้ไขข้อมูลเจ้าหน้าที่
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('staff.update', $staff->staff_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">
                                ชื่อเจ้าหน้าที่ <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text" 
                                       class="form-control @error('staff_name') is-invalid @enderror" 
                                       name="staff_name" 
                                       value="{{ old('staff_name', $staff->staff_name) }}" 
                                       placeholder="ชื่อ-นามสกุล เจ้าหน้าที่"
                                       required>
                                @error('staff_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">ชื่อเล่น</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-chat-square-text"></i>
                                </span>
                                <input type="text" 
                                       class="form-control @error('staff_nickname') is-invalid @enderror" 
                                       name="staff_nickname" 
                                       value="{{ old('staff_nickname', $staff->staff_nickname) }}" 
                                       placeholder="ชื่อเล่นหรือชื่อที่เรียกสั้นๆ">
                                @error('staff_nickname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">
                                สถานะ <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('staff_status') is-invalid @enderror" name="staff_status" required>
                                <option value="">-- เลือกสถานะ --</option>
                                <option value="active" {{ old('staff_status', $staff->staff_status) == 'active' ? 'selected' : '' }}>
                                    ใช้งาน
                                </option>
                                <option value="inactive" {{ old('staff_status', $staff->staff_status) == 'inactive' ? 'selected' : '' }}>
                                    ไม่ใช้งาน
                                </option>
                            </select>
                            @error('staff_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('staff.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> ยกเลิก
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-save"></i> อัปเดต
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection