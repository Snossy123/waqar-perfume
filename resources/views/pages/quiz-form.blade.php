@extends('pages.blank')

@section('title', isset($quiz) ? 'Edit Quiz' : 'Create Quiz')
@vite('resources/js/quize-form.js')
@section('content')
<div class="pagetitle">
    <h1>{{ isset($quiz) ? 'Edit' : 'Create' }} Quiz</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.Quizzes') }}">Quizzes</a></li>
            <li class="breadcrumb-item active">{{ isset($quiz) ? 'Edit' : 'Create' }}</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Quiz Details</h5>
                <div class="card-body">
                    <form action="{{ isset($quiz) ? route('admin.quiz.update', $quiz->id) : route('admin.quiz.store') }}" method="POST">
                        @csrf
                        @if(isset($quiz))
                            @method('PUT')
                        @endif

                        <div class="mb-3">
                            <label for="question" class="form-label">Question</label>
                            <input type="text" class="form-control @error('question') is-invalid @enderror" 
                                   id="question" name="question" 
                                   value="{{ old('question', $quiz->question ?? '') }}" required>
                            @error('question')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="attribute" class="form-label">Attribute</label>
                            <input type="text" class="form-control @error('attribute') is-invalid @enderror" 
                                   id="attribute" name="attribute" 
                                   value="{{ old('attribute', $quiz->attribute ?? '') }}" required>
                            @error('attribute')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Question Type</label>
                            <select class="form-select @error('type') is-invalid @enderror" 
                                    id="type" name="type" required>
                                <option value="" disabled {{ !isset($quiz) ? 'selected' : '' }}>Select Type</option>
                                <option value="text" {{ (old('type', $quiz->type ?? '') == 'text') ? 'selected' : '' }}>Text Input</option>
                                <option value="select" {{ (old('type', $quiz->type ?? '') == 'select') ? 'selected' : '' }}>Dropdown Select</option>
                                <option value="radio" {{ (old('type', $quiz->type ?? '') == 'radio') ? 'selected' : '' }}>Radio Buttons</option>
                                <option value="checkbox" {{ (old('type', $quiz->type ?? '') == 'checkbox') ? 'selected' : '' }}>Checkboxes</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="options-container" 
                             style="display: {{ in_array(old('type', $quiz->type ?? ''), ['select', 'radio', 'checkbox']) ? 'block' : 'none' }}">
                            <label class="form-label">Options</label>
                            <div id="options-list">
                                @php
                                    $options = old('options', $quiz->options ?? []);
                                    if (is_string($options) && !empty($options)) {
                                        $options = json_decode($options, true) ?? [];
                                    }
                                @endphp
                                @if(!empty($options))
                                    @foreach($options as $index => $option)
                                        <div class="input-group mb-2 option-row">
                                            <input type="text" class="form-control" name="options[]" 
                                                   value="{{ $option }}" required>
                                            <button type="button" class="btn btn-outline-danger remove-option" >
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="input-group mb-2 option-row">
                                        <input type="text" class="form-control" name="options[]" required>
                                        <button type="button" class="btn btn-outline-danger remove-option">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <button type="button" id="add-option" class="btn btn-sm btn-outline-secondary mt-2">
                                <i class="fas fa-plus"></i> Add Option
                            </button>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_required" 
                                   name="is_required" value="1" 
                                   {{ old('is_required', isset($quiz) && $quiz->is_required ? 'checked' : '') }}>
                            <label class="form-check-label" for="is_required">Required Question</label>
                        </div>

                        <div class="text-center mt-4">
                            <a href="{{ route('admin.Quizzes') }}" class="btn btn-secondary me-2">
                                <i class="bi bi-arrow-left"></i> Back to List
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> {{ isset($quiz) ? 'Update' : 'Create' }} Quiz
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
