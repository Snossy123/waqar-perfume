@extends('pages.blank')

@section('title', 'Quizzes Management')

@section('content')
    <div class="pagetitle">
        <h1>Quizzes Management</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Quizzes</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Quizzes List</h5>
                        <a href="{{ route('admin.quiz.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg"></i> Add New Quiz
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="quizzesTable" class="table table-bordered table-striped table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Question</th>
                                        <th>Attribute</th>
                                        <th>Type</th>
                                        <th>Options</th>
                                        <th>Required</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($quizzes as $quiz)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $quiz->question }}</td>
                                            <td><span class="badge bg-info">{{ $quiz->attribute }}</span></td>
                                            <td><span class="badge bg-secondary">{{ $quiz->type }}</span></td>
                                            <td>
                                                @if (is_array($quiz->options) && count($quiz->options) > 0)
                                                    <ul class="list-unstyled mb-0">
                                                        @foreach ($quiz->options as $option)
                                                            <li class="badge bg-light text-dark mb-1">{{ $option }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <span class="text-muted">No options</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($quiz->is_required)
                                                    <span class="badge bg-success">Yes</span>
                                                @else
                                                    <span class="badge bg-secondary">No</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.quiz.edit', $quiz->id) }}"
                                                        class="btn btn-sm btn-info">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('admin.quiz.destroy', $quiz->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Are you sure you want to delete this quiz?')">
                                                            <i class="bi bi-trash"></i>

                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No quizzes found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-1"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize DataTable
                const table = new DataTable('#quizzesTable', {
                    responsive: true,
                    columnDefs: [{
                            orderable: false,
                            targets: [5, 6]
                        } // Disable sorting on Actions column
                    ],
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search...",
                    },
                    dom: '<"d-flex justify-content-between align-items-center mb-3"fB>rt<"d-flex justify-content-between align-items-center"ip>',
                    buttons: [{
                            extend: 'copy',
                            text: '<i class="bi bi-clipboard me-1"></i> Copy',
                            className: 'btn btn-sm btn-outline-secondary',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'excel',
                            text: '<i class="bi bi-file-excel me-1"></i> Excel',
                            className: 'btn btn-sm btn-outline-success',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'pdf',
                            text: '<i class="bi bi-file-pdf me-1"></i> PDF',
                            className: 'btn btn-sm btn-outline-danger',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'print',
                            text: '<i class="bi bi-printer me-1"></i> Print',
                            className: 'btn btn-sm btn-outline-primary',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        }
                    ]
                });

                // Initialize tooltips
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            });
        </script>
    @endpush

@endsection
