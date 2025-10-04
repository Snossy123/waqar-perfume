@extends('layouts.app')

@section('title', 'Trim')

@section('content')
<div class="pagetitle">
  <h1>Welcome to admin dashboard</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
      <li class="breadcrumb-item active">Add Product</li>
    </ol>
  </nav>
</div>

    <section class="section">
    <div class="row">
        <div class="productd">
            <div class="card-body">
                <h5 class="card-title">Add New Product</h5>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>There were some problems with your input:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">
                        <strong>There were some problems try again later or call support:</strong>
                        <ul class="mb-0 mt-2">
                            <li>{{ session('error') }}</li>
                        </ul>
                    </div>
                @endif
                <!-- Multi Columns Form -->
                <form class="row g-3" action="{{ route('admin.product.store') }}" method="post"  enctype="multipart/form-data">
                    @csrf
                    <!-- Accordion Start -->
                    <div class="accordion" id="accordionExample">
                        <!-- Vehicle Information Section -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Part 1
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row g-3">
                                        <!-- Category -->
                                        <div class="col-md-4">
                                            <label for="inputCategory" class="form-label">Category</label>
                                            <select class="form-select" id="inputCategory" name="category_id">
                                                <option value="" selected>Choose...</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label for="inputName" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="inputName" name="name" title="Choose your name">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="inputDescription" class="form-label">Description</label>
                                            <input type="text" class="form-control" id="inputDescription" name="description" title="Choose your description">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="inputPrice" class="form-label">Price</label>
                                            <input type="number" class="form-control" id="inputPrice" name="price" title="Choose your price">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Engine Type Section -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingEngineType">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEngineType" aria-expanded="false" aria-controls="collapseEngineType">
                                    Part 2
                                </button>
                            </h2>
                            <div id="collapseEngineType" class="accordion-collapse collapse" aria-labelledby="headingEngineType" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label for="inputDiscountedPrice" class="form-label">Discounted Price</label>
                                            <input type="number" class="form-control" id="inputDiscountedPrice" name="discounted_price" title="Choose your discounted price">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="inputStars" class="form-label">Stars</label>
                                            <input type="number" class="form-control" id="inputStars" name="stars" title="Choose your stars">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="inputOfferEndDate" class="form-label">Offer End Date</label>
                                            <input type="date" class="form-control" id="inputOfferEndDate" name="offer_end_date" title="Choose your offer end date">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="inputStock" class="form-label">Stock</label>
                                            <input type="number" class="form-control" id="inputStock" name="stock" title="Choose your stock">
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Trim Section -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTrim">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTrim" aria-expanded="false" aria-controls="collapseTrim">
                                    Part 3
                                </button>
                            </h2>
                            <div id="collapseTrim" class="accordion-collapse collapse" aria-labelledby="headingTrim" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row g-3">
                                        <!-- Images Upload -->
                                        <div class="col-md-4">
                                            <label for="formFile" class="form-label">Images Upload</label>
                                            <input class="form-control" type="file" id="formFile" name="images[]" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="reset" class="btn btn-secondary">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
