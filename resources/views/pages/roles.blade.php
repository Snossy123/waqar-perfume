@extends('layouts.app')

@section('title', 'Roles Management')

@section('content')
<div class="pagetitle">
  <h1>Roles Management</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
      <li class="breadcrumb-item active">Roles</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title">Roles List</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal">
              <i class="bi bi-plus-circle"></i> Add New Role
            </button>
          </div>
          
          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif
          @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
          @endif
          
          <div class="table-responsive">
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Role Name</th>
                  <th>Guard</th>
                  <th>Permissions</th>
                  <th>Created At</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @forelse($roles as $index => $role)
                <tr>
                  <td>{{ $roles->firstItem() + $index }}</td>
                  <td>{{ $role->name }}</td>
                  <td><span class="badge bg-primary">{{ $role->guard_name }}</span></td>
                  <td>
                    @foreach($role->permissions->take(3) as $permission)
                      <span class="badge bg-info text-dark me-1 mb-1">{{ $permission->name }}</span>
                    @endforeach
                    @if($role->permissions->count() > 3)
                      <span class="badge bg-secondary">+{{ $role->permissions->count() - 3 }} more</span>
                    @endif
                  </td>
                  <td>{{ $role->created_at->format('Y-m-d') }}</td>
                  <td>
                    <div class="btn-group btn-group-sm" role="group">
                      <button type="button" class="btn btn-outline-primary" 
                              data-bs-toggle="modal" 
                              data-bs-target="#editRoleModal{{ $role->id }}"
                              title="Edit Role">
                        <i class="bi bi-pencil"></i>
                      </button>
                      <button type="button" class="btn btn-outline-info" 
                              data-bs-toggle="modal" 
                              data-bs-target="#permissionModal{{ $role->id }}"
                              title="Manage Permissions">
                        <i class="bi bi-shield-lock"></i>
                      </button>
                      <form action="{{ route('admin.Role.destroy', $role->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger" 
                                onclick="return confirm('Are you sure you want to delete this role?')"
                                title="Delete Role">
                          <i class="bi bi-trash"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="6" class="text-center">No roles found. Create your first role!</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          
          @if($roles->hasPages())
          <div class="d-flex justify-content-center mt-3">
            {{ $roles->links() }}
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</section>


<div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.Role.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="roleName" class="form-label">Role Name</label>
                        <input type="text" class="form-control" id="roleName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Permissions</label>
                        <div class="row">
                            @foreach($permissions as $permission)
                            <div class="col-md-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="perm{{ $permission->id }}">
                                    <label class="form-check-label" for="perm{{ $permission->id }}">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Role</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($roles as $role)
<div class="modal fade" id="editRoleModal{{ $role->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="edit-form-{{ $role->id }}" action="{{ route('admin.Role.edit', $role->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editRoleName{{ $role->id }}" class="form-label">Role Name</label>
                        <input type="text" class="form-control" id="editRoleName{{ $role->id }}" name="name" value="{{ $role->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Permissions</label>
                        <div class="row">
                            @foreach($permissions as $permission)
                            <div class="col-md-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input permission-checkbox" type="checkbox" 
                                        name="permissions[]" 
                                        value="{{ $permission->id }}" 
                                        id="editPerm{{ $role->id }}_{{ $permission->id }}"
                                        {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="editPerm{{ $role->id }}_{{ $permission->id }}">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Role</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endforeach


@endsection