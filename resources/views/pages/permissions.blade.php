@extends('layouts.app')

@section('title', 'Permissions Management')

@section('content')
<div class="pagetitle">
  <h1>Permissions Management</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
      <li class="breadcrumb-item active">Permissions</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title">Permissions List</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPermissionModal">
              <i class="bi bi-plus-circle"></i> Add New Permission
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
                  <th>Name</th>
                  <th>Guard</th>
                  <th>Created At</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @forelse($permissions as $index => $permission)
                <tr>
                  <td>{{ $permissions->firstItem() + $index }}</td>
                  <td>
                    <span class="badge bg-primary">{{ $permission->name }}</span>
                  </td>
                  <td>{{ $permission->guard_name }}</td>
                  <td>{{ $permission->created_at->format('Y-m-d') }}</td>
                  <td>
                    <div class="btn-group btn-group-sm" role="group">
                      <button type="button" class="btn btn-outline-primary" 
                              data-bs-toggle="modal" 
                              data-bs-target="#editPermissionModal"
                              data-id="{{ $permission->id }}"
                              data-name="{{ $permission->name }}"
                              title="Edit Permission">
                        <i class="bi bi-pencil"></i>
                      </button>
                      <form action="{{ route('admin.Permission.destroy', $permission->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger" 
                                onclick="return confirm('Are you sure you want to delete this permission?')"
                                title="Delete Permission">
                          <i class="bi bi-trash"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="5" class="text-center">No permissions found.</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          
          @if($permissions->hasPages())
          <div class="d-flex justify-content-center mt-3">
            {{ $permissions->links() }}
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</section>


<div class="modal fade" id="addPermissionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Permission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.Permission.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="permissionName" class="form-label">Permission Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="permissionName" name="name" required
                               placeholder="e.g., create-post">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Permission</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editPermissionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Permission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editPermissionForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editPermissionName" class="form-label">Permission Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="editPermissionName" name="name" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Permission</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const editModal = document.getElementById('editPermissionModal');
    if (editModal) {
        editModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            
            const form = editModal.querySelector('#editPermissionForm');
            form.action = `/admin/Permission/${id}`;
            
            const nameInput = editModal.querySelector('#editPermissionName');
            nameInput.value = name;
        });
    }
});
</script>
@endpush

@endsection