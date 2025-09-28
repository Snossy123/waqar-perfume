@extends('layouts.app')

@section('title', 'Body Styles')

@section('content')
<div class="pagetitle">
  <h1>Welcome to admin dashboard</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
      <li class="breadcrumb-item active">Body Styles</li>
    </ol>
  </nav>
</div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title">Body Styles</h5>
                    @error('name')
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-octagon me-1"></i>
                            {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @enderror
                    @session('success')
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-1"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endsession
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#verticalycentered">
                        Add New Body Styles
                    </button>
                    <div class="modal fade" id="verticalycentered" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form action="{{ route('admin.BodyStyle.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                <h5 class="modal-title">Add New Body Styles</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="brandImage" class="form-label">Body Styles</label>
                                        <input type="file" class="form-control" id="brandImage" name="image" accept="image/*">
                                    </div>
                                    <div class="mb-3">
                                        <label for="brandName" class="form-label">Body Styles Name (Ar)</label>
                                        <input type="text" class="form-control" id="brandName" name="name_ar" required>
                                        <label for="brandName" class="form-label">Body Styles Name (En)</label>
                                        <input type="text" class="form-control" id="brandName" name="name_en" required>
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
                      <b>N</b>ame (Ar)
                    </th>
                    <th>
                        <b>N</b>ame (En)
                      </th>
                    <th data-type="date" data-format="YYYY/DD/MM">Created Date</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($data as $item)
                    <tr>
                        <td>
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" width="50">
                            @else
                                <span class="text-muted">No Image</span>
                            @endif
                        </td>
                        <td>{{ $item->getTranslation('name', 'ar') }}</td>
                        <td>{{ $item->getTranslation('name', 'en') }}</td>
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
                                        <form action="{{ route('admin.BodyStyle.edit', $item->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editBrandModalLabel{{ $item->id }}">Edit Body Styles</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="editBodyImage{{ $item->id }}" class="form-label">Update Image</label>
                                                    <input type="file" class="form-control" id="editBodyImage{{ $item->id }}" name="image" accept="image/*">
                                                </div>

                                                @if($item->image)
                                                    <div class="mb-3">
                                                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" width="70">
                                                    </div>
                                                @endif
                                                <div class="mb-3">
                                                    <label for="editBrandNameAr{{ $item->id }}" class="form-label">Body Styles Name (Ar)</label>
                                                    <input type="text" class="form-control" id="editBrandNameAr{{ $item->id }}" name="name_ar" value="{{ $item->getTranslation('name', 'ar') }}" required>
                                                    <label for="editBrandNameEn{{ $item->id }}" class="form-label">Body Styles Name (En)</label>
                                                    <input type="text" class="form-control" id="editBrandNameEn{{ $item->id }}" name="name_en" value="{{ $item->getTranslation('name', 'en') }}" required>
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
                            <form action="{{ route('admin.BodyStyle.destroy', $item->id) }}" method="POST" style="display:inline;">
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
