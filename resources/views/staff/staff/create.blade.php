@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="bi bi-plus-circle me-2 text-primary"></i>เพิ่มเจ้าหน้าที่ (Staff)
        </h4>
        <a href="{{ route('staff.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> กลับ
        </a>
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
                        <i class="bi bi-form me-2"></i>ข้อมูลเจ้าหน้าที่
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('staff.store') }}" method="POST">
                        @csrf
                        
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
                                       value="{{ old('staff_name') }}" 
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
                                       value="{{ old('staff_nickname') }}" 
                                       placeholder="ชื่อเล่นหรือชื่อที่เรียกสั้นๆ">
                                @error('staff_nickname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                <i class="bi bi-person-check me-1"></i>เชื่อมโยงกับ User (บัญชีผู้ใช้)
                            </label>
                            <select class="form-select @error('user_id') is-invalid @enderror" name="user_id">
                                <option value="">-- ไม่เชื่อมโยง --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">เลือก User เพื่อให้สามารถเข้าสู่ระบบได้</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">
                                สถานะ <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('staff_status') is-invalid @enderror" name="staff_status" required>
                                <option value="">-- เลือกสถานะ --</option>
                                <option value="active" {{ old('staff_status') == 'active' ? 'selected' : '' }}>
                                    <i class="bi bi-check-circle"></i> ใช้งาน
                                </option>
                                <option value="inactive" {{ old('staff_status') == 'inactive' ? 'selected' : '' }}>
                                    <i class="bi bi-x-circle"></i> ไม่ใช้งาน
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
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> บันทึก
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection