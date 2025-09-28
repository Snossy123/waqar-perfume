@extends('layouts.app')

@section('title', 'Cars')

@section('content')
<div class="pagetitle">
  <h1>Welcome to admin dashboard</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
      <li class="breadcrumb-item active">Cars</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title">All Cars</h5>
            <a href="{{ route('admin.car.add') }}" class="btn btn-primary">Add New Car</a>
          </div>

          <div class="table-responsive">
            <table class="table table-striped table-hover text-center align-middle">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Brand</th>
                  <th>Model</th>
                  <th>Year</th>
                  <th>Color</th>
                  <th>Engine</th>
                  <th>Status</th>
                  <th>Price</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @forelse($cars as $index => $car)
                  {{-- @dd($car) --}}
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $car['brand']['name'] ?? '-' }}</td>
                  <td>{{ $car['CarModel']['name'] ?? '-' }}</td>
                  <td>{{ $car['model_year'] ?? '-' }}</td>
                  <td>{{ $car['color'] ?? '-' }}</td>
                  <td>{{ $car['EngineType']['name'] ?? '-' }} ({{ $car['engine_capacity_cc'] ?? '0' }} cc)</td>
                  <td>{{ $car['VehicleStatus']['name'] ?? '-' }}</td>
                  <td>{{ $car['price'] ?? '-' }}</td>
                  <td>
                    <a href="{{ route('admin.car.show', $car['id']) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('admin.car.edit', $car['id']) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('admin.car.destroy', $car['id']) }}" method="POST" style="display:inline-block;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="9">No cars found.</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <div class="d-flex justify-content-center mt-3">
            {{ $cars->links('pagination::bootstrap-5') }}
          </div>

        </div>
      </div>

    </div>
  </div>
</section>

@endsection
