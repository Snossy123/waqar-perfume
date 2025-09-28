@extends('layouts.app')

@section('title', 'Send Notification')

@section('content')
<div class="pagetitle">
  <h1>Send Notification</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
      <li class="breadcrumb-item"><a href="{{ route('admin.notifications.index') }}">Notifications</a></li>
      <li class="breadcrumb-item active">Send</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">New Notification</h5>
          
          <ul class="nav nav-tabs" id="notificationTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="topic-tab" data-bs-toggle="tab" data-bs-target="#topic-tab-pane" type="button" role="tab" aria-controls="topic-tab-pane" aria-selected="true">
                Send to all
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="users-tab" data-bs-toggle="tab" data-bs-target="#users-tab-pane" type="button" role="tab" aria-controls="users-tab-pane" aria-selected="false">
                Send to Users
              </button>
            </li>
          </ul>

          <div class="tab-content pt-2" id="notificationTabsContent">
            <div class="tab-pane fade show active" id="topic-tab-pane" role="tabpanel" aria-labelledby="topic-tab" tabindex="0">
              <form id="topicForm" action="{{ route('admin.notifications.send') }}" method="POST">
                @csrf
                <input type="hidden" name="send_type" value="topic">
                <input type="hidden" name="topic" value="all">
                

                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Title</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="title" required maxlength="255">
                  </div>
                </div>

                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Message</label>
                  <div class="col-sm-10">
                    <textarea class="form-control" name="body" rows="5" required maxlength="1000"></textarea>
                  </div>
                </div>

                <div class="text-center">
                  <button type="submit" class="btn btn-primary">Send to all</button>
                </div>
              </form>
            </div>

            <!-- Users Tab -->
            <div class="tab-pane fade" id="users-tab-pane" role="tabpanel" aria-labelledby="users-tab" tabindex="0">
              <form id="usersForm" action="{{ route('admin.notifications.send') }}" method="POST">
                @csrf
                <input type="hidden" name="send_type" value="users">
                
                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Select Users</label>
                  <div class="col-sm-10">
                    <select class="form-select select2" name="user_ids[]" multiple="multiple" required data-placeholder="Select users...">
                      @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Title</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="title" required maxlength="255">
                  </div>
                </div>

                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Message</label>
                  <div class="col-sm-10">
                    <textarea class="form-control" name="body" rows="5" required maxlength="1000"></textarea>
                  </div>
                </div>

                <div class="text-center">
                  <button type="submit" class="btn btn-primary">Send to Selected Users</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


@endsection


