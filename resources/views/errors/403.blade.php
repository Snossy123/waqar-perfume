@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body text-center p-5">
                    <h1 class="display-1 text-danger fw-bold">403</h1>
                    <h2 class="h3 mb-4">Access Denied</h2>
                    <p class="text-muted mb-4">
                        {{ $message ?: "You don't have permission to access this page." }}
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ url()->previous() }}" class="btn btn-primary">
                            <i class="bi bi-arrow-left me-2"></i>Go Back
                        </a>
                        <a href="{{ url('/') }}" class="btn btn-secondary">
                            <i class="bi bi-house-door me-2"></i>Home Page
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
