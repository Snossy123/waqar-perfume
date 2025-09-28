@extends('layouts.app')

@section('title', 'Users')

@section('content')
<div class="pagetitle">
  <h1>Welcome to admin dashboard</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
      <li class="breadcrumb-item active">Users</li>
    </ol>
  </nav>
</div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title">Users</h5>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>There were some problems with your input:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">
                            <strong>There were some problems try again later or call support:</strong>
                            <ul class="mb-0 mt-2">
                                <li>{{ session('error') }}</li>
                            </ul>
                        </div>
                    @endif
                    @session('success')
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-1"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endsession
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#verticalycentered">
                        Add New User
                    </button>
                    <div class="modal fade" id="verticalycentered" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form action="{{ route('admin.User.store') }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                <h5 class="modal-title">Add New User</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="userName" class="form-label">Full Name (Ar)</label>
                                        <input type="text" class="form-control" id="userName" name="name_ar" required>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="userName" class="form-label">Full Name (En)</label>
                                        <input type="text" class="form-control" id="userName" name="name_en" required>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="userEmail" class="form-label">Email</label>
                                        <input type="text" class="form-control" id="userEmail" name="email" required>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="userPhone" class="form-label">Phone Number</label>
                                        <input type="text" class="form-control" id="userPhone" name="phone">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Add</button>
                                </div>
                            </form>
                        </div>
                        </div>
                    </div><!-- End Vertically centered Modal-->
                </div>
              <!-- Table with stripped rows -->
              <div class="table-responsive">
            <table class="table table-striped table-hover align-middle text-center">
                <thead>
                  <tr>
                    <th>
                      <b>Name</b> (Ar)
                    </th>
                    <th>Name (En)</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th data-type="date" data-format="YYYY/DD/MM">Created Date</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($data as $item)
                    <tr>
                        <td>{{ $item->name_ar }}</td>
                        <td>{{ $item->name_en }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->phone ?? '-' }}</td>
                        <td>{{ $item->created_at->format('Y/m/d') }}</td>
                        <td>
                            <!-- Edit Button triggers modal -->
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editBrandModal{{ $item->id }}">
                                Edit
                            </button>

                            <!-- Edit Brand Modal -->
                            <div class="modal fade" id="editBrandModal{{ $item->id }}" tabindex="-1" aria-labelledby="editBrandModalLabel{{ $item->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.User.edit', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editBrandModalLabel{{ $item->id }}">Edit Type</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="editUserName{{ $item->id }}" class="form-label">User Name (Ar)</label>
                                                    <input type="text" class="form-control" id="editUserName{{ $item->id }}" name="name_ar" value="{{ $item->name_ar }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="editUserName{{ $item->id }}" class="form-label">User Name (En)</label>
                                                    <input type="text" class="form-control" id="editUserName{{ $item->id }}" name="name_en" value="{{ $item->name_en }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="editUserEmail{{ $item->id }}" class="form-label">Email</label>
                                                    <input type="text" class="form-control" id="editUserEmail{{ $item->id }}" name="email" value="{{ $item->email }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="editUserPhone{{ $item->id }}" class="form-label">Phone</label>
                                                    <input type="text" class="form-control" id="editUserPhone{{ $item->id }}" name="phone" value="{{ $item->phone }}">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <form action="{{ route('admin.User.destroy', $item->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                            </td>
                    </tr>
                    @endforeach

                </tbody>

            </table>
             <div class="d-flex justify-content-center mt-3">
                    {{ $data->links('pagination::bootstrap-5') }}
            </div>
            </div>
              <!-- End Table with stripped rows -->

            </div>
          </div>

        </div>
      </div>
    </section>

@endsection
