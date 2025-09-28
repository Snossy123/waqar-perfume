@extends('layouts.app')

@section('title', 'Blank Page')

@section('content')
<div class="pagetitle">
  <h1>Welcome to admin dashboard</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
      <li class="breadcrumb-item active">Model</li>
    </ol>
  </nav>
</div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title">Model</h5>
                    @error('name_ar')
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-octagon me-1"></i>
                            {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @enderror
                    @error('name_en')
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
                        Add New model
                    </button>
                    <div class="modal fade" id="verticalycentered" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form action="{{ route('admin.CarModel.store') }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                <h5 class="modal-title">Add New Model</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="name_ar" class="form-label">Arabic Name</label>
                                        <input type="text" class="form-control" id="name_ar" name="name_ar" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="name_en" class="form-label">English Name</label>
                                        <input type="text" class="form-control" id="name_en" name="name_en" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="brand_id" class="form-label">Brand</label>
                                    <select class="form-control" id="brand_id" name="brand_id" required>
                                        <option value="">-- Select Brand --</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Add</button>
                                </div>
                            </form>
                        </div>
                        </div>
                    </div><!-- End Vertically centered model-->
                </div>
              <!-- Table with stripped rows -->
              <div class="table-responsive">
            <table class="table table-striped table-hover align-middle text-center">
                <thead>
                  <tr>
                    <th>Arabic Name</th>
                    <th>English Name</th>
                    <th data-type="date" data-format="YYYY/DD/MM">Created Date</th>
                    <th>Brand</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($CarModels as $model)
                    <tr>
                        <td>{{ $model->getTranslation('name','ar') }}</td>
                        <td>{{ $model->getTranslation('name','en') }}</td>
                        <td>{{ $model->created_at->format('Y/m/d') }}</td>
                        <td>{{ $model->brand->name ?? '-' }}</td>
                        <td>
                            <!-- Edit Button triggers model -->
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editmodalmodel{{ $model->id }}">
                                Edit
                            </button>

                            <!-- Edit model model -->
                            <div class="modal fade" id="editmodalmodel{{ $model->id }}" tabindex="-1" aria-labelledby="editmodelmodalLabel{{ $model->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.CarModel.edit', $model->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editmodalmodelLabel{{ $model->id }}">Edit model</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="editModelName_ar{{ $model->id }}" class="form-label text-start w-100">Arabic Name</label>
                                                    <input type="text" class="form-control" id="editModelName_ar{{ $model->id }}" name="name_ar" value="{{ $model->getTranslation('name','ar') }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="editModelName_en{{ $model->id }}" class="form-label text-start w-100">English Name</label>
                                                    <input type="text" class="form-control" id="editModelName_en{{ $model->id }}" name="name_en" value="{{ $model->getTranslation('name','en') }}" required>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="editBrandId{{ $model->id }}" class="form-label text-start w-100">Brand</label>
                                                <select class="form-control" id="editBrandId{{ $model->id }}" name="brand_id" required>
                                                    <option value="">-- Select Brand --</option>
                                                    @foreach($brands as $brand)
                                                        <option value="{{ $brand->id }}" {{ $model->brand_id == $brand->id ? 'selected' : '' }}>
                                                            {{ $brand->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <form action="{{ route('admin.CarModel.destroy', $model->id) }}" method="POST" style="display:inline;">
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
                    {{ $CarModels->links('pagination::bootstrap-5') }}
            </div>
            </div>
              <!-- End Table with stripped rows -->

            </div>
          </div>

        </div>
      </div>
    </section>

@endsection
