@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="pagetitle">
  <h1>Notifications</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
      <li class="breadcrumb-item active">Notifications</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="card-title mb-0">Sent Notifications</h5>
            <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary">
              <i class="bi bi-plus-lg me-1"></i> New Notification
            </a>
          </div>
          
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Title</th>
                  <th>Type</th>
                  <th>Recipient</th>
                  <th>Sent At</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @forelse($notifications as $notification)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $notification->title }}</td>
                  <td>
                    <span class="badge bg-{{ $notification->type === 'topic' ? 'info' : 'primary' }}">
                      {{ ucfirst($notification->type) }}
                    </span>
                  </td>
                  <td>
                    @if($notification->type === 'topic')
                      {{ ucfirst($notification->topic) }}
                    @else
                      {{ $notification->user->name ?? 'N/A' }}
                    @endif
                  </td>
                  <td>{{ $notification->sent_at->format('M d, Y H:i') }}</td>
                  <td>
                    <button class="btn btn-sm btn-outline-primary" 
                            data-bs-toggle="modal" 
                            data-bs-target="#notificationModal{{ $notification->id }}">
                      <i class="bi bi-eye"></i>
                    </button>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="6" class="text-center">No notifications found.</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          
          <div class="mt-3">
            {{ $notifications->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


@foreach($notifications as $notification)
<div class="modal fade" id="notificationModal{{ $notification->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ $notification->title }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>Type:</strong> {{ ucfirst($notification->type) }}</p>
        @if($notification->type === 'topic')
        <p><strong>Topic:</strong> {{ $notification->topic }}</p>
        @else
        <p><strong>Recipient:</strong> {{ $notification->user->name ?? 'N/A' }}</p>
        @endif
        <p><strong>Sent At:</strong> {{ $notification->sent_at->format('M d, Y H:i') }}</p>
        <hr>
        <h6>Message:</h6>
        <p>{{ $notification->body }}</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endforeach

@endsection

@push('styles')
<style>
  .table th, .table td {
    vertical-align: middle;
  }
  .badge {
    font-size: 0.8rem;
    padding: 0.35em 0.65em;
  }
</style>
@endpush
