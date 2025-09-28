@extends('layouts.app')

@section('title', 'Blank Page')

@section('content')
<div class="pagetitle">
  <h1>Welcome to admin dashboard</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
      <li class="breadcrumb-item active">Brands</li>
    </ol>
  </nav>
</div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title">Brands</h5>
                    @error('name_ar')
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-octagon me-1"></i>
                            Arabic Name: {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @enderror
                    @error('name_en')
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-octagon me-1"></i>
                            English Name: {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @enderror
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
                        Add New Brand
                    </button>
                    <div class="modal fade" id="verticalycentered" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                <h5 class="modal-title">Add New Brand</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="brandImage" class="form-label">Brand Image</label>
                                        <input type="file" class="form-control" id="brandImage" name="image" accept="image/*">
                                    </div>
                                    <div class="form-group">
                                        <label for="brandNameEn" class="form-label">Name (English)</label>
                                        <input type="text" name="name_en" class="form-control" 
                                               value="{{ old('name_en') }}" required>
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="brandNameAr" class="form-label">Name (العربية)</label>
                                        <input type="text" name="name_ar" class="form-control" dir="rtl"
                                               value="{{ old('name_ar') }}" required>
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
                      <th>Name (Ar)</th>
                      <th>Name (En)</th>
                      <th>Created Date</th>
                      <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($brands as $brand)
                    <tr>
                        <td>
                            @if($brand->image)
                                <img src="{{ asset('storage/' . $brand->image) }}" alt="{{ $brand->getTranslation('name', 'en') }}" width="50">
                            @else
                                <span class="text-muted">No Image</span>
                            @endif
                        </td>
                        <td>
                            <div>{{ $brand->getTranslation('name', 'ar') }}</div>
                        </td>
                        <td>
                            <div>{{ $brand->getTranslation('name', 'en') }}</div>
                        </td>
                        <td>{{ $brand->created_at->format('Y/m/d') }}</td>
                        <td>
                            <!-- Edit Button triggers modal -->
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editBrandModal{{ $brand->id }}">
                                Edit
                            </button>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editBrandModal{{ $brand->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.brands.edit', $brand->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Brand</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                @if($brand->image)
                                                    <div class="mb-3">
                                                        <img src="{{ asset('storage/' . $brand->image) }}" alt="{{ $brand->getTranslation('name', 'en') }}" width="70">
                                                    </div>
                                                @endif
                                                <div class="mb-3">
                                                    <label for="editBrandImage{{ $brand->id }}" class="form-label">Change Image</label>
                                                    <input type="file" class="form-control" id="editBrandImage{{ $brand->id }}" name="image" accept="image/*">
                                                </div>
                                                <div class="form-group">
                                                    <label for="editBrandNameEn{{ $brand->id }}" class="form-label">Name (English)</label>
                                                    <input type="text" name="name_en" class="form-control" 
                                                           value="{{ old('name_en', $brand->getTranslation('name', 'en') ?? '') }}" required>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <label for="editBrandNameAr{{ $brand->id }}" class="form-label">Name (العربية)</label>
                                                    <input type="text" name="name_ar" class="form-control" dir="rtl"
                                                           value="{{ old('name_ar', $brand->getTranslation('name', 'ar') ?? '') }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div><!-- End Edit Modal -->
                            <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" style="display:inline;">
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
                    {{ $brands->links('pagination::bootstrap-5') }}
            </div>
            </div>
              <!-- End Table with stripped rows -->

            </div>
          </div>

        </div>
      </div>
    </section>

@endsection
