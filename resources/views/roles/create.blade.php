@extends('layouts.main')

@section('content')
<div class="container">
    <h2 class="my-4">Add New Role</h2>

    <form action="{{ route('roles.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="role_name">Role Name</label>
            <input type="text" name="role_name" id="role_name" class="form-control" required>
        </div>
        <div class="form-group mt-3">
            <label>Assign Permissions</label>
            @foreach($permissions as $permission)
                <div class="form-check">
                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="form-check-input">
                    <label class="form-check-label">{{ ucfirst($permission->name) }}</label>
                </div>
            @endforeach
        </div>
        <button type="submit" class="btn btn-success mt-3">Create Role</button>
    </form>
</div>
@endsection
