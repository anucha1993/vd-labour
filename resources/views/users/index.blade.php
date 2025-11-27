@extends('layouts.main')

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ $message }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ $message }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

<div class="container">
  <div class="card card-custom shadow-sm mb-4">
    <div class="card-body">
      <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="bi bi-people-fill me-2 text-primary"></i>จัดการผู้ใช้งานระบบ</h4>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle-fill"></i> เพิ่มผู้ใช้ใหม่
        </a>
      </div>
      <!-- ตารางแสดง Users ทั้งหมด -->
      <table class="table table-striped table-hover table-bordered align-middle datatable" id="users-table">
          <thead class="table-primary text-center align-middle">
              <tr>
                  <th style="width: 180px;">ชื่อผู้ใช้</th>
                  <th>อีเมล</th>
                  <th>บทบาท</th>
                  <th style="width: 100px;">สถานะ</th>
                  <th style="width: 200px;">จัดการ</th>
              </tr>
          </thead>
          <tbody>
              @foreach($users as $user)
              <tr>
                  <td>{{ $user->name }}</td>
                  <td>{{ $user->email }}</td>
                  <td>
                      @foreach($user->roles as $role)
                          <span class="badge bg-primary">{{ ucfirst($role->name) }}</span>
                      @endforeach
                  </td>
                  <td class="text-center">
                      @if($user->status == 1)
                        <span class="badge bg-success">Active</span>
                      @else
                        <span class="badge bg-secondary">Inactive</span>
                      @endif
                  </td>
                  <td class="text-center">
                      <div class="btn-group" role="group">
                          <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-warning" title="แก้ไขข้อมูล">
                              <i class="bi bi-pencil-fill"></i>
                          </a>
                          <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#resetPasswordModal-{{ $user->id }}" title="รีเซ็ตรหัสผ่าน">
                              <i class="bi bi-key-fill"></i>
                          </button>
                          <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editRoleModal-{{ $user->id }}" title="จัดการบทบาท">
                              <i class="bi bi-person-gear"></i>
                          </button>
                          @if($user->id !== auth()->id())
                          <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" 
                                onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบผู้ใช้นี้?');">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-sm btn-outline-danger" title="ลบผู้ใช้">
                                  <i class="bi bi-trash-fill"></i>
                              </button>
                          </form>
                          @endif
                      </div>
                  </td>
              </tr>

            <!-- Modal สำหรับรีเซ็ตรหัสผ่าน -->
            <div class="modal fade" id="resetPasswordModal-{{ $user->id }}" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="resetPasswordModalLabel">รีเซ็ตรหัสผ่านสำหรับ {{ $user->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('users.resetPassword', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">รหัสผ่านใหม่</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password" required minlength="8">
                                </div>
                                <div class="mb-3">
                                    <label for="new_password_confirmation" class="form-label">ยืนยันรหัสผ่านใหม่</label>
                                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required minlength="8">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                <button type="submit" class="btn btn-warning">รีเซ็ตรหัสผ่าน</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal สำหรับแก้ไข Role -->
            <div class="modal fade" id="editRoleModal-{{ $user->id }}" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editRoleModalLabel">จัดการบทบาทสำหรับ {{ $user->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('users.assignRole') }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <div class="mb-3">
                                    <label for="roles" class="form-label">เลือกบทบาท:</label>
                                    <select name="roles[]" id="roles" multiple class="form-control" style="height: 100px">
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" {{ $user->roles->contains('name', $role->name) ? 'selected' : '' }}>
                                                {{ ucfirst($role->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                         
                            <div class="mb-3">
                                <label for="status" class="form-label">สถานะ:</label>
                                <select name="status" class="form-control">
                                    <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>เปิดใช้งาน</option>
                                    <option value="2" {{ $user->status == 2 ? 'selected' : '' }}>ปิดใช้งาน</option>
                                </select>
                            </div>
                               </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
                            </div>
    <script>
      $(function() {
        $('#users-table').DataTable();
      });
    </script>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

