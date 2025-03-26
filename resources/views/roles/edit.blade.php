@extends('layouts.main')

@section('content')
<div class="container">
    <h2 class="my-4">Edit Role</h2>

    <form action="{{ route('roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="role_name">Role Name</label>
            <input type="text" name="role_name" id="role_name" class="form-control" value="{{ $role->name }}" required>
        </div>
        <div class="form-group mt-3">
            <label>Assign Permissions</label>
            @foreach($permissions as $permission)
                <div class="form-check">
                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="form-check-input"
                        {{ $role->permissions->contains($permission) ? 'checked' : '' }}>
                    <label class="form-check-label">{{ ucfirst($permission->name) }}</label>
                </div>
            @endforeach
        </div>
        <button type="submit" class="btn btn-primary mt-3">Update Role</button>
    </form>
</div>
@endsection
