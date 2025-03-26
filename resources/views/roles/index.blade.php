@extends('layouts.main')

@section('content')
<div class="container">
    <h2 class="my-4">Manage Roles and Permissions</h2>

    <!-- ปุ่มเปิดหน้า Add New Role -->
    <a href="{{ route('roles.create') }}" class="btn btn-success mb-3">Add New Role</a>

    <!-- ตารางแสดง Roles ทั้งหมด -->
    <table class="table table-striped table-hover">
        <thead class="table-info text-white">
            <tr>
                <th>Role</th>
                <th>Permissions</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $role)
            <tr>
                <td>{{ ucfirst($role->name) }}</td>
                <td>
                    @foreach($role->permissions as $permission)
                        <span class="badge bg-secondary">{{ ucfirst($permission->name) }}</span>
                    @endforeach
                </td>
                <td>
                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
