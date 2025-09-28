@extends('layouts.app')

@section('title', 'Financing Requests')

@section('content')
<div class="pagetitle">
  <h1>Financing Requests</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
      <li class="breadcrumb-item active">Financing Requests</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          @if(session('success'))
            <div class="alert alert-success">
              {{ session('success') }}
            </div>
          @endif

          <div class="row mb-3 mt-4 align-items-center">
            <div class="col-md-12">
              <form action="{{ route('admin.financing-requests.index') }}" method="GET" class="row g-3">
                <div class="col-md-8">
                  <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search by name, email, status, etc..." value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">
                      <i class="bi bi-search"></i> Search
                    </button>
                    @if(request('search') || request('status') !== null)
                      <a href="{{ route('admin.financing-requests.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg"></i> Clear
                      </a>
                    @endif
                  </div>
                </div>
                <div class="col-md-3">
                  <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="all" {{ request('status') === 'all' || request('status') === null ? 'selected' : '' }}>All Statuses</option>
                    <option value="In process" {{ request('status') === 'In process' ? 'selected' : '' }}>In Process</option>
                    <option value="Accepted" {{ request('status') === 'Accepted' ? 'selected' : '' }}>Accepted</option>
                    <option value="Rejected" {{ request('status') === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="Cancelled" {{ request('status') === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                  </select>
                </div>
              </form>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-striped datatable">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>User</th>
                  <th>Email</th>
                  <th>Status</th>
                  <th>Requested At</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @forelse($requests as $request)
                  <tr>
                    <td>{{ $request->id }}</td>
                    <td>{{ $request->first_name }} {{ $request->second_name }}</td>
                    <td>{{ $request->email }}</td>
                    <td>
                      <span class="badge bg-{{ 
                        $request->status === 'In process' ? 'warning' : 
                        ($request->status === 'Rejected' ? 'danger' : 'success') 
                      }}">
                        {{ ucfirst($request->status) }}
                      </span>
                    </td>
                    <td>{{ $request->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                      <a href="{{ route('admin.financing-requests.show', $request) }}" class="btn btn-sm btn-info" title="View">
                        <i class="bi bi-eye"></i>
                      </a>
                      <form action="{{ route('admin.financing-requests.destroy', $request) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this request?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                          <i class="bi bi-trash"></i>
                        </button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center">No requests found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="d-flex justify-content-center">
            {{ $requests->links() }}
        </div>
    </div>
</div>
@endsection
