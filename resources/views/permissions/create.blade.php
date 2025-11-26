@extends('layouts.main')

@section('content')
    <div class="card card-custom mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0"><i class="bi bi-plus-circle me-2 text-primary"></i>เพิ่ม Permission ใหม่</h4>
                <a href="{{ route('permissions.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> กลับ
                </a>
            </div>

            <form action="{{ route('permissions.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label for="permission_name" class="form-label">ชื่อ Permission <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('permission_name') is-invalid @enderror" 
                               id="permission_name" 
                               name="permission_name" 
                               value="{{ old('permission_name') }}" 
                               placeholder="เช่น view labour, create labour, update labour"
                               required>
                        @error('permission_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">รูปแบบ: [action] [module] เช่น view labour, create customer</small>
                    </div>
                </div>

                <div class="alert alert-info">
                    <h6><i class="bi bi-info-circle me-2"></i>คำแนะนำการตั้งชื่อ Permission</h6>
                    <ul class="mb-0">
                        <li><strong>view</strong> - สำหรับดูข้อมูล</li>
                        <li><strong>create</strong> - สำหรับสร้างข้อมูลใหม่</li>
                        <li><strong>update</strong> - สำหรับแก้ไขข้อมูล</li>
                        <li><strong>delete</strong> - สำหรับลบข้อมูล</li>
                        <li>ตามด้วยชื่อโมดูล เช่น labour, customer, role, user</li>
                    </ul>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('permissions.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> ยกเลิก
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> สร้าง Permission
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
