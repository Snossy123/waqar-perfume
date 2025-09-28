@extends('layouts.app')

@section('title', 'Request #' . $financingRequest->id)

@section('content')
<div class="pagetitle">
  <h1>Request #{{ $financingRequest->id }}</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
      <li class="breadcrumb-item"><a href="{{ route('admin.financing-requests.index') }}">Financing Requests</a></li>
      <li class="breadcrumb-item active">Request #{{ $financingRequest->id }}</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0">Request Details</h5>
          <a href="{{ route('admin.financing-requests.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to List
          </a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Personal Information</h5>
                    <p><strong>Name:</strong> {{ $financingRequest->first_name }} {{ $financingRequest->second_name }}</p>
                    <p><strong>Email:</strong> {{ $financingRequest->email }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-{{ 
                            $financingRequest->status === 'In process' ? 'warning' : 
                            ($financingRequest->status === 'Rejected' ? 'danger' : 'success') 
                        }}">
                            {{ ucfirst($financingRequest->status) }}
                        </span>
                    </p>
                </div>
                <div class="col-md-6">
                    <h5>Address Information</h5>
                    <p><strong>Governorate:</strong> {{ $financingRequest->governorate->name ?? 'N/A' }}</p>
                    <p><strong>Area:</strong> {{ $financingRequest->area->name ?? 'N/A' }}</p>
                    <p><strong>Street:</strong> {{ $financingRequest->street }}</p>
                    <p><strong>Building:</strong> {{ $financingRequest->building_number }}</p>
                    <p><strong>Floor:</strong> {{ $financingRequest->floor_number }}</p>
                </div>
            </div>

            <hr>

            <div class="row mt-4">
                <div class="col-md-6">
                    <h5>Car Information</h5>
                    <p><strong>Car Type:</strong> {{ $financingRequest->car_type }}</p>
                    <p><strong>Brand:</strong> {{ $financingRequest->brand->name ?? 'N/A' }}</p>
                    <p><strong>Model:</strong> {{ $financingRequest->car_model }}</p>
                    <p><strong>Year:</strong> {{ $financingRequest->manufacture_year }}</p>
                    <p><strong>Total Price:</strong> ${{ number_format($financingRequest->total_price, 2) }}</p>
                    <p><strong>Down Payment:</strong> ${{ number_format($financingRequest->down_payment, 2) }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Financial Information</h5>
                    <p><strong>Applicant Type:</strong> {{ ucfirst($financingRequest->applicant_type) }}</p>
                    @if($financingRequest->applicant_type === 'student')
                        <p><strong>University:</strong> {{ $financingRequest->university }}</p>
                        <p><strong>Faculty:</strong> {{ $financingRequest->faculty }}</p>
                    @else
                        <p><strong>Company Name:</strong> {{ $financingRequest->company_name }}</p>
                        <p><strong>Position:</strong> {{ $financingRequest->eco_position }}</p>
                        <p><strong>Monthly Salary:</strong> ${{ number_format($financingRequest->mid_salary, 2) }}</p>
                    @endif
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <h5>Documents</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <p><strong>ID Card (Front):</strong></p>
                            @if($financingRequest->card_front)
                                <a href="{{ asset('storage/' . $financingRequest->card_front) }}" target="_blank" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            @else
                                <span class="text-muted">Not provided</span>
                            @endif
                        </div>
                        <div class="col-md-4 mb-3">
                            <p><strong>ID Card (Back):</strong></p>
                            @if($financingRequest->card_back)
                                <a href="{{ asset('storage/' . $financingRequest->card_back) }}" target="_blank" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            @else
                                <span class="text-muted">Not provided</span>
                            @endif
                        </div>
                        @if($financingRequest->applicant_type === 'employee')
                            <div class="col-md-4 mb-3">
                                <p><strong>Salary Certificate:</strong></p>
                                @if($financingRequest->salary_certificate)
                                    <a href="{{ asset('storage/' . $financingRequest->salary_certificate) }}" target="_blank" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                @else
                                    <span class="text-muted">Not provided</span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <form action="{{ route('admin.financing-requests.update-status', ['financingRequest' => $financingRequest]) }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <div class="input-group" style="max-width: 300px;">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="In process" {{ $financingRequest->status === 'In process' ? 'selected' : '' }}>In process</option>
                        <option value="Accepted" {{ $financingRequest->status === 'Accepted' ? 'selected' : '' }}>Accepted</option>
                        <option value="Rejected" {{ $financingRequest->status === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="Cancelled" {{ $financingRequest->status === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>

                     
                    </select>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
            
            <form action="{{ route('admin.financing-requests.destroy', $financingRequest) }}" method="POST" class="d-inline float-end">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" 
                    onclick="return confirm('Are you sure you want to delete this request?')">
                    <i class="fas fa-trash"></i> Delete Request
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
