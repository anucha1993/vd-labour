@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="bi bi-plus-circle me-2 text-primary"></i>เพิ่มสายหางาน (Staff Sub)
        </h4>
        <a href="{{ route('staff-sub.index') }}" class="btn btn-secondary">
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
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-form me-2"></i>ข้อมูลสายหางาน
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('staff-sub.store') }}" method="POST">
                        @csrf
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">
                                    ชื่อสายหางาน <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('staff_sub_name') is-invalid @enderror" 
                                       name="staff_sub_name" 
                                       value="{{ old('staff_sub_name') }}" 
                                       placeholder="ชื่อสายหางาน"
                                       required>
                                @error('staff_sub_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">เบอร์โทรศัพท์</label>
                                <input type="text" 
                                       class="form-control @error('staff_sub_phone') is-invalid @enderror" 
                                       name="staff_sub_phone" 
                                       value="{{ old('staff_sub_phone') }}" 
                                       placeholder="เบอร์โทรศัพท์">
                                @error('staff_sub_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">
                                    สถานะ <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('staff_sub_status') is-invalid @enderror" name="staff_sub_status" required>
                                    <option value="">-- เลือกสถานะ --</option>
                                    <option value="active" {{ old('staff_sub_status') == 'active' ? 'selected' : '' }}>
                                        <i class="bi bi-check-circle"></i> ใช้งาน
                                    </option>
                                    <option value="inactive" {{ old('staff_sub_status') == 'inactive' ? 'selected' : '' }}>
                                        <i class="bi bi-x-circle"></i> ไม่ใช้งาน
                                    </option>
                                </select>
                                @error('staff_sub_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('staff-sub.index') }}" class="btn btn-secondary">
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