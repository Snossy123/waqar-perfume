@extends('layouts.app')

@section('title', 'Banners')

@section('content')
<div class="pagetitle">
  <h1>Welcome to admin dashboard</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
      <li class="breadcrumb-item active">Banners</li>
    </ol>
  </nav>
</div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title">Banners</h5>
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
                        Add New Banner
                    </button>
                    <div class="modal fade" id="verticalycentered" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form action="{{ route('admin.Banner.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                <h5 class="modal-title">Add New Banner</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="bannerImage" class="form-label">Banner Image</label>
                                        <input type="file" class="form-control" id="bannerImage" name="image" accept="image/*">
                                    </div>
                                    <div class="mb-3">
                                        <label for="bannerTitle" class="form-label">Banner Title</label>
                                        <input type="text" class="form-control" id="bannerTitle" name="title" required>

                                    </div>

                                    <div class="mb-3">
                                        <label for="bannerDesc" class="form-label">Banner Description</label>
                                        <input type="text" class="form-control" id="bannerDesc" name="description">

                                    </div>

                                    <div class="mb-3">
                                        <label for="bannerLink" class="form-label">Banner link</label>
                                        <input type="text" class="form-control" id="bannerLink" name="link">

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
                    <th>Image</th>
                    <th>
                      <b>T</b>itle
                    </th>
                    <th>Description</th>
                    <th>Link</th>
                    <th data-type="date" data-format="YYYY/DD/MM">Created Date</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($data as $item)
                    <tr>
                        <td>
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" width="50">
                            @else
                                <span class="text-muted">No Image</span>
                            @endif
                        </td>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->description }}</td>
                        <td>
                            @empty($item->link)
                                <span class="text-muted">No Link</span>
                            @else
                                <a href="{{ $item->link }}" target="_blank" class="btn btn-sm btn-primary">
                                    View
                                </a>
                            @endempty
                        </td>
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
                                        <form action="{{ route('admin.Banner.edit', $item->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editBrandModalLabel{{ $item->id }}">Edit Banner</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="editBodyImage{{ $item->id }}" class="form-label">Update Image</label>
                                                    <input type="file" class="form-control" id="editBodyImage{{ $item->id }}" name="image" accept="image/*">
                                                </div>

                                                @if($item->image)
                                                    <div class="mb-3">
                                                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" width="70">
                                                    </div>
                                                @endif
                                                <div class="mb-3">
                                                    <label for="editBrandName{{ $item->id }}" class="form-label">Banner Title</label>
                                                    <input type="text" class="form-control" id="editBrandName{{ $item->id }}" name="title" value="{{ $item->title }}" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="editBrandName{{ $item->id }}" class="form-label">Banner Description</label>
                                                    <input type="text" class="form-control" id="editBrandName{{ $item->id }}" name="description" value="{{ $item->description }}">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="editBrandName{{ $item->id }}" class="form-label">Banner Link</label>
                                                    <input type="text" class="form-control" id="editBrandName{{ $item->id }}" name="link" value="{{ $item->link }}">
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
                            <form action="{{ route('admin.Banner.destroy', $item->id) }}" method="POST" style="display:inline;">
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
