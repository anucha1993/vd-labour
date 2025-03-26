@extends('layouts.main')

@section('content')
<div class="container">
    <h2 class="my-4">Manage Users and Assign Roles</h2>

    <!-- ตารางแสดง Users ทั้งหมด -->
    <table class="table table-striped table-hover">
        <thead class="table-dark text-white">
            <tr class="text-white">
                <th class="text-white">User</th>
                <th class="text-white">Email</th>
                <th class="text-white">Roles</th>
                <th class="text-white">Assign Role</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @foreach($user->roles as $role)
                        <span class="badge bg-primary">{{ $role->name }}</span>
                    @endforeach
                </td>
                <td>
                    <!-- ปุ่มเปิด Modal เพื่อแก้ไข Role -->
                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editRoleModal-{{ $user->id }}">Edit Roles</button>
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
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

