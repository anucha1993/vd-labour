@extends('layouts.main')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card card-custom mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <h4 class="mb-0"><i class="bi bi-pencil-fill me-2 text-primary"></i>แก้ไขข้อมูลผู้ใช้</h4>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> กลับ
                </a>
            </div>

            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">ชื่อผู้ใช้ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">อีเมล <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password" class="form-label">รหัสผ่านใหม่</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" minlength="8">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">ปล่อยว่างไว้หากไม่ต้องการเปลี่ยนรหัสผ่าน</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">ยืนยันรหัสผ่านใหม่</label>
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                   id="password_confirmation" name="password_confirmation" minlength="8">
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">สถานะ <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="1" {{ old('status', $user->status) == '1' ? 'selected' : '' }}>เปิดใช้งาน</option>
                                <option value="2" {{ old('status', $user->status) == '2' ? 'selected' : '' }}>ปิดใช้งาน</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="roles" class="form-label">บทบาท</label>
                            <select class="form-select @error('roles') is-invalid @enderror" id="roles" name="roles[]" multiple style="height: 120px;">
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" 
                                        {{ in_array($role->name, old('roles', $user->roles->pluck('name')->toArray())) ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('roles')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">กด Ctrl + Click เพื่อเลือกหลายบทบาท</small>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">ยกเลิก</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> บันทึกการเปลี่ยนแปลง
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- แสดงข้อมูลปัจจุบัน -->
    <div class="card card-custom mb-4">
        <div class="card-body">
            <h5 class="card-title">ข้อมูลปัจจุบัน</h5>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>ชื่อผู้ใช้:</strong> {{ $user->name }}</p>
                    <p><strong>อีเมล:</strong> {{ $user->email }}</p>
                    <p><strong>สถานะ:</strong> 
                        @if($user->status == 1)
                            <span class="badge bg-success">เปิดใช้งาน</span>
                        @else
                            <span class="badge bg-secondary">ปิดใช้งาน</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-6">
                    <p><strong>บทบาทปัจจุบัน:</strong></p>
                    @foreach($user->roles as $role)
                        <span class="badge bg-primary me-1">{{ ucfirst($role->name) }}</span>
                    @endforeach
                    @if($user->roles->isEmpty())
                        <span class="text-muted">ไม่มีบทบาท</span>
                    @endif
                    <p class="mt-2"><strong>สร้างเมื่อ:</strong> {{ $user->created_at->format('d/m/Y H:i:s') }}</p>
                    <p><strong>อัพเดทล่าสุด:</strong> {{ $user->updated_at->format('d/m/Y H:i:s') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection