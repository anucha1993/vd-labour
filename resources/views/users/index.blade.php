@extends('layouts.main')

@section('content')
<div class="container">
  <div class="card card-custom shadow-sm mb-4">
    <div class="card-body">
      <h2 class="my-4">Manage Users and Assign Roles</h2>
      <!-- ตารางแสดง Users ทั้งหมด -->
      <table class="table table-striped table-hover table-bordered align-middle datatable" id="users-table">
          <thead class="table-primary text-center align-middle">
              <tr>
                  <th style="width: 180px;">User</th>
                  <th>Email</th>
                  <th>Roles</th>
                  <th style="width: 100px;">Status</th>
                  <th style="width: 120px;">Assign Role</th>
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
                      <!-- ปุ่มเปิด Modal เพื่อแก้ไข Role -->
                      <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editRoleModal-{{ $user->id }}"><i class="bi bi-pencil-square"></i> Edit</button>
                  </td>
              </tr>

            <!-- Modal สำหรับแก้ไข Role -->
            <div class="modal fade" id="editRoleModal-{{ $user->id }}" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editRoleModalLabel">Edit Roles for {{ $user->name }}</h5>
                          
                        </div>
                        <form action="{{ route('users.assignRole') }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <div class="form-group">
                                    <label for="roles">Select Roles:</label>
                                    <select name="roles[]" id="roles" multiple class="form-control" style="height: 100px">
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" {{ $user->roles->contains('name', $role->name) ? 'selected' : '' }}>
                                                {{ ucfirst($role->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                         
                            <div class="form-group mb-3 mt-3">
                                <label for="status">Status:</label>
                                <select name="status" class="form-control">
                                    <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="2" {{ $user->status == 2 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                               </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
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

