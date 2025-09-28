@extends('layouts.app')

@section('title', 'Trim')

@section('content')
<div class="pagetitle">
  <h1>Welcome to admin dashboard</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
      <li class="breadcrumb-item active">Add Car</li>
    </ol>
  </nav>
</div>

    <section class="section">
    <div class="row">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Add New Car</h5>
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
                <form class="row g-3" action="{{ route('admin.car.store') }}" method="post"  enctype="multipart/form-data">
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
                                        <!-- Brand -->
                                        <div class="col-md-4">
                                            <label for="inputBrand" class="form-label">Brand</label>
                                            <select class="form-select" id="inputBrand" name="brand">
                                                <option value="" selected>Choose...</option>
                                                @foreach ($brands as $brand)
                                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('brand')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Model -->
                                        <div class="col-md-4">
                                            <label for="inputModel" class="form-label">Model</label>
                                            <select class="form-select" id="inputModel" name="model">
                                                <option value="" selected>Choose...</option>
                                                @foreach ($carModels as $model)
                                                    <option value="{{ $model->id }}">{{ $model->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Model Year -->
                                        <div class="col-md-4">
                                            <label for="inputDate" class="form-label">Model Year</label>
                                            <input type="number" class="form-control" id="inputDate" name="model_year" placeholder="year">
                                        </div>
                                    </div>

                                    <div class="row g-3">
                                        <!-- License Expiry Date -->
                                        <div class="col-md-4">
                                            <label for="inputLicenseDate" class="form-label">License Expiry Date</label>
                                            <input type="date" class="form-control" id="inputLicenseDate" name="license_expire_date">
                                        </div>

                                        <!-- Body Style -->
                                        <div class="col-md-4">
                                            <label for="inputBodyStyle" class="form-label">Body Style</label>
                                            <select class="form-select" id="inputBodyStyle" name="body_style">
                                                <option value="" selected>Choose...</option>
                                                @foreach ($bodyStyles as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Type -->
                                        <div class="col-md-4">
                                            <label for="inputType" class="form-label">Type</label>
                                            <select class="form-select" id="inputType" name="type">
                                                <option value="" selected>Choose...</option>
                                                @foreach ($types as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row g-3">
                                        <!-- Min Fuel Economy -->
                                        <div class="col-md-4">
                                            <label for="minRange" class="form-label">Min Fuel Economy</label>
                                            <input type="number" class="form-control" id="minRange" name="min_fuel_economy" placeholder="Min Fuel Economy">
                                        </div>

                                        <!-- Max Fuel Economy -->
                                        <div class="col-md-4">
                                            <label for="maxRange" class="form-label">Max Fuel Economy</label>
                                            <input type="number" class="form-control" id="maxRange" name="max_fuel_economy" placeholder="Max Fuel Economy">
                                        </div>

                                        <!-- Color -->
                                        <div class="col-md-4">
                                            <label for="inputColorAR" class="form-label">Color (AR)</label>
                                            <input type="text" class="form-control" id="exampleColorInputAR" name="color_ar" title="Choose your color ar">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="inputColorEN" class="form-label">Color (EN)</label>
                                            <input type="text" class="form-control" id="exampleColorInputEN" name="color_en" title="Choose your color en">
                                        </div>
                                    </div>

                                    <div class="row g-3">
                                        <!-- Length -->
                                        <div class="col-md-4">
                                            <label for="inputLength" class="form-label">Length</label>
                                            <input type="number" class="form-control" id="inputLength" name="length">
                                        </div>

                                        <!-- Width -->
                                        <div class="col-md-4">
                                            <label for="inputWidth" class="form-label">Width</label>
                                            <input type="number" class="form-control" id="inputWidth" name="width">
                                        </div>

                                        <!-- Height -->
                                        <div class="col-md-4">
                                            <label for="inputHeight" class="form-label">Height</label>
                                            <input type="number" class="form-control" id="inputHeight" name="height">
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
                                        <!-- Engine Type -->
                                        <div class="col-md-4">
                                            <label for="inputEngineType" class="form-label">Engine Type</label>
                                            <select class="form-select" id="inputEngineType" name="engine_type">
                                                <option value="" selected>Choose...</option>
                                                @foreach ($engineTypes as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Mileage -->
                                        <div class="col-md-4">
                                            <label for="inputMileage" class="form-label">Mileage</label>
                                            <input type="number" class="form-control" id="inputMileage" name="mileage">
                                        </div>

                                        <!-- Vehicle Status -->
                                        <div class="col-md-4">
                                            <label for="inputStatus" class="form-label">Vehicle Status</label>
                                            <select class="form-select" id="inputStatus" name="vehicle_status">
                                                <option value="" selected>Choose...</option>
                                                @foreach ($vehicleStatuses as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row g-3">
                                        <!-- Refurbishment Status -->
                                        <div class="col-md-4">
                                            <label for="inputRefurbishment" class="form-label">Refurbishment Status</label>
                                            <select class="form-select" id="inputRefurbishment" name="refurbishment_status">
                                                <option value="" selected>Choose...</option>
                                                @foreach ($refurbishmentStatuses as $item)
                                                    <option value="{{ $item->value }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Transmission Type -->
                                        <div class="col-md-4">
                                            <label for="inputTransmission" class="form-label">Transmission Type</label>
                                            <select class="form-select" id="inputTransmission" name="transmission_type">
                                                <option value="" selected>Choose...</option>
                                                @foreach ($transmissionTypes as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Drive Type -->
                                        <div class="col-md-4">
                                            <label for="inputDrive" class="form-label">Drive Type</label>
                                            <select class="form-select" id="inputDrive" name="drive_type">
                                                <option value="" selected>Choose...</option>
                                                @foreach ($driveTypes as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Engine Capacity -->
                                        <div class="col-md-4">
                                            <label for="inputEngineCapacity" class="form-label">Engine Capacity (cc)</label>
                                            <input type="number" class="form-control" id="inputEngineCapacity" name="engine_capacity">
                                        </div>

                                        <!-- Min Horse Power -->
                                        <div class="col-md-4">
                                            <label for="minRangePower" class="form-label">Min Horse Power</label>
                                            <input type="number" class="form-control" id="minRangePower" name="min_horse_power" placeholder="Min Horse Power">
                                        </div>

                                        <!-- Max Horse Power -->
                                        <div class="col-md-4">
                                            <label for="maxRangePower" class="form-label">Max Horse Power</label>
                                            <input type="number" class="form-control" id="maxRangePower" name="max_horse_power" placeholder="Max Horse Power">
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
                                        <!-- Price -->
                                        <div class="col-md-4">
                                            <label for="inputPrice" class="form-label">Price</label>
                                            <input type="number" class="form-control" id="inputPrice" name="price">
                                        </div>

                                        <!-- Discount -->
                                        <div class="col-md-4">
                                            <label for="inputDiscount" class="form-label">Discount</label>
                                            <input type="number" class="form-control" id="inputDiscount" name="discount">
                                        </div>

                                        <!-- Monthly Installment -->
                                        <div class="col-md-4">
                                            <label for="inputInstallment" class="form-label">Monthly Installment</label>
                                            <input type="number" class="form-control" id="inputInstallment" name="monthly_installment">
                                        </div>

                                        <!-- Down Payment -->
                                        <div class="col-md-4">
                                            <label for="inputDownpayment" class="form-label">Down Payment</label>
                                            <input type="number" class="form-control" id="inputDownpayment" name="down_payment">
                                        </div>

                                        <!-- Trim -->
                                        <div class="col-md-4">
                                            <label for="inputTrim" class="form-label">Trim</label>
                                            <select id="inputTrim" class="form-select" name="trim">
                                                <option value="" selected>Choose...</option>
                                                @foreach ($trim as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Images Upload -->
                                        <div class="col-md-4">
                                            <label for="formFile" class="form-label">Images Upload</label>
                                            <input class="form-control" type="file" id="formFile" name="images[]" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Flags and Features Section -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingSeven">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                    Part 4
                                </button>
                            </h2>
                            <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#accordionExample">
                                <div class="accordion-body">

                                    <!-- Flags Section -->
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label for="inputFlags" class="form-label">Flags</label>
                                            <div id="flagContainer">
                                                <div class="flagInput row g-2 align-items-center">
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control" name="flags[0][name_ar]" placeholder="Flag Name (Ar)">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control" name="flags[0][name_en]" placeholder="Flag Name (En)">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="file" class="form-control" name="flags[0][image]" accept="image/*">
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-link" onclick="addFlagInput()">
                                                <i class="bi bi-plus-circle"></i> Add Flag
                                            </button>
                                        </div>
                                    </div>

                                    {{-- features --}}
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div id="featureBlockContainer">
                                                <!-- First Block of Fields -->
                                                <div class="feature-block mb-3">
                                                    <!-- Feature Selection -->
                                                    <div class="col-12 mb-3">
                                                        <label class="form-label">Feature</label>
                                                        <select class="form-select" name="features[0][name]">
                                                            <option value="" selected>Choose...</option>
                                                            @foreach($features as $feature)
                                                                <option value="{{ $feature->value }}">
                                                                    {{ $feature->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <!-- Label (English) -->
                                                    <div class="col-12 mb-3">
                                                        <label class="form-label">Label (English)</label>
                                                        <input type="text" class="form-control" name="features[0][label][en]" placeholder="Enter label in English">
                                                    </div>

                                                    <!-- Label (Arabic) -->
                                                    <div class="col-12 mb-3">
                                                        <label class="form-label">Label (Arabic)</label>
                                                        <input type="text" class="form-control" name="features[0][label][ar]" placeholder="Enter label in Arabic" dir="rtl">
                                                    </div>

                                                    <!-- Value (English) -->
                                                    <div class="col-12 mb-3">
                                                        <label class="form-label">Value (English)</label>
                                                        <input type="text" class="form-control" name="features[0][value][en]" placeholder="Enter value in English">
                                                    </div>

                                                    <!-- Value (Arabic) -->
                                                    <div class="col-12 mb-3">
                                                        <label class="form-label">Value (Arabic)</label>
                                                        <input type="text" class="form-control" name="features[0][value][ar]" placeholder="Enter value in Arabic" dir="rtl">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Add More Button -->
                                            <button type="button" class="btn btn-link" onclick="addFeatureBlock()">
                                                <i class="bi bi-plus-circle"></i> Add More
                                            </button>
                                        </div>
                                    </div>



                                    <!-- Conditions Section -->
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div id="conditionBlockContainer">
                                                <!-- First Block of Fields -->
                                                <div class="condition-block mb-3">
                                                    <div class="col-12">
                                                        <label for="inputConditions" class="form-label">Conditions</label>
                                                        <select class="form-select" name="conditions[0][name]">
                                                            <option value="" selected>Choose...</option>
                                                            @foreach ($conditions as $item)
                                                                <option value="{{ $item->value }}">
                                                                    {{ $item->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-12">
                                                        <label for="inputPart" class="form-label">Part</label>
                                                        <input type="text" class="form-control" name="conditions[0][part]">
                                                    </div>

                                                    <div class="col-12">
                                                        <label for="inputDescription" class="form-label">Description</label>
                                                        <textarea class="form-control" name="conditions[0][description]" rows="3"></textarea>
                                                    </div>

                                                    <div class="col-12">
                                                        <label for="inputConditionImage" class="form-label">Image</label>
                                                        <input type="file" class="form-control" name="conditions[0][image]" accept="image/*">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Add More Button -->
                                            <button type="button" class="btn btn-link" onclick="addConditionBlock()">
                                                <i class="bi bi-plus-circle"></i> Add More
                                            </button>
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

<script>
let flagIndex = 1;
function addFlagInput() {
    const flagContainer = document.getElementById('flagContainer');
    const newFlagInput = document.createElement('div');
    newFlagInput.classList.add('flagInput', 'row', 'g-2', 'align-items-center', 'mt-2');

    // Name input
    const nameCol = document.createElement('div');
    nameCol.classList.add('col-md-5');
    const nameInput = document.createElement('input');
    nameInput.type = 'text';
    nameInput.classList.add('form-control');
    nameInput.name = `flags[${flagIndex}][name]`;
    nameInput.placeholder = 'Flag Name';
    nameCol.appendChild(nameInput);

    // Image input (single file only)
    const imageCol = document.createElement('div');
    imageCol.classList.add('col-md-5');
    const imageInput = document.createElement('input');
    imageInput.type = 'file';
    imageInput.classList.add('form-control');
    imageInput.name = `flags[${flagIndex}][image]`;
    imageInput.accept = 'image/*';
    imageCol.appendChild(imageInput);

    // Remove button
    const removeCol = document.createElement('div');
    removeCol.classList.add('col-md-2', 'd-flex', 'align-items-center');
    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.classList.add('btn', 'btn-link', 'text-danger', 'p-0', 'ms-2');
    removeBtn.title = 'Remove';
    removeBtn.innerHTML = '<i class="bi bi-x-circle" style="font-size: 1.5rem;"></i>';
    removeBtn.onclick = function() {
        flagContainer.removeChild(newFlagInput);
    };
    removeCol.appendChild(removeBtn);

    newFlagInput.appendChild(nameCol);
    newFlagInput.appendChild(imageCol);
    newFlagInput.appendChild(removeCol);

    flagContainer.appendChild(newFlagInput);
    flagIndex++;
}
</script>
<script>
let featureIndex = 1; // Start from 1 since we have the initial block at index 0

function addFeatureBlock() {
    const container = document.getElementById('featureBlockContainer');
    const newBlock = document.createElement('div');
    newBlock.className = 'feature-block mb-3 border p-3';
    
    // Get the current index and increment for next time
    const currentIndex = featureIndex++;
    
    // Feature Selection
    const featureSelectGroup = document.createElement('div');
    featureSelectGroup.className = 'col-12 mb-3';
    featureSelectGroup.innerHTML = `
        <label class="form-label">Feature</label>
        <select class="form-select" name="features[${currentIndex}][name]">
            <option value="" selected>Choose...</option>
            @foreach($features as $feature)
                <option value="{{ $feature->value }}">
                    {{ $feature->name }}
                </option>
            @endforeach
        </select>
    `;
    
    // Label (English)
    const labelEnGroup = document.createElement('div');
    labelEnGroup.className = 'col-12 mb-3';
    labelEnGroup.innerHTML = `
        <label class="form-label">Label (English)</label>
        <input type="text" class="form-control" name="features[${currentIndex}][label][en]" placeholder="Enter label in English">
    `;
    
    // Label (Arabic)
    const labelArGroup = document.createElement('div');
    labelArGroup.className = 'col-12 mb-3';
    labelArGroup.innerHTML = `
        <label class="form-label">Label (Arabic)</label>
        <input type="text" class="form-control" name="features[${currentIndex}][label][ar]" placeholder="Enter label in Arabic" dir="rtl">
    `;
    
    // Value (English)
    const valueEnGroup = document.createElement('div');
    valueEnGroup.className = 'col-12 mb-3';
    valueEnGroup.innerHTML = `
        <label class="form-label">Value (English)</label>
        <input type="text" class="form-control" name="features[${currentIndex}][value][en]" placeholder="Enter value in English">
    `;
    
    // Value (Arabic)
    const valueArGroup = document.createElement('div');
    valueArGroup.className = 'col-12 mb-3';
    valueArGroup.innerHTML = `
        <label class="form-label">Value (Arabic)</label>
        <input type="text" class="form-control" name="features[${currentIndex}][value][ar]" placeholder="Enter value in Arabic" dir="rtl">
    `;
    
    // Remove button
    const removeButton = document.createElement('button');
    removeButton.type = 'button';
    removeButton.className = 'btn btn-sm btn-danger mt-2';
    removeButton.innerHTML = '<i class="bi bi-trash"></i> Remove';
    removeButton.onclick = function() {
        container.removeChild(newBlock);
        // Re-index remaining feature blocks
        reindexFeatureBlocks();
    };
    
    // Append all elements to the new block
    newBlock.appendChild(featureSelectGroup);
    newBlock.appendChild(labelEnGroup);
    newBlock.appendChild(labelArGroup);
    newBlock.appendChild(valueEnGroup);
    newBlock.appendChild(valueArGroup);
    newBlock.appendChild(removeButton);
    
    // Add the new block to the container
    container.appendChild(newBlock);
}

function reindexFeatureBlocks() {
    const blocks = document.querySelectorAll('.feature-block');
    blocks.forEach((block, index) => {
        // Update all input/select names with the new index
        const inputs = block.querySelectorAll('input, select');
        inputs.forEach(input => {
            const name = input.name;
            // Match the index in features[0][...]
            const newName = name.replace(/features\[(\d+)\]/, `features[${index}]`);
            input.name = newName;
        });
    });
    // Update the featureIndex to be one more than the last index
    featureIndex = blocks.length;
}
</script>

<script>
    let conditionIndex = 1;
    function addConditionBlock() {
        const container = document.getElementById('conditionBlockContainer');
        const newBlock = document.createElement('div');
        newBlock.classList.add('condition-block', 'mb-3'); // Add margin-bottom to create space between blocks

        // Create the "Conditions" dropdown and label
        const conditionsLabel = document.createElement('label');
        conditionsLabel.classList.add('form-label');
        conditionsLabel.setAttribute('for', 'inputConditions');
        conditionsLabel.innerText = 'Conditions';

        const select = document.createElement('select');
        select.classList.add('form-select');
        select.name = `conditions[${conditionIndex}][name]`;
        select.innerHTML = `
            <option value='' selected>Choose...</option>
            @foreach ($conditions as $item)
                <option value="{{ $item->value }}">
                    {{ $item->name }}
                </option>
            @endforeach
        `;

        // Create the "Part" input and label
        const partLabel = document.createElement('label');
        partLabel.classList.add('form-label');
        partLabel.setAttribute('for', 'inputPart');
        partLabel.innerText = 'Part';

        const partInput = document.createElement('input');
        partInput.type = 'text';
        partInput.classList.add('form-control');
        partInput.name = `conditions[${conditionIndex}][part]`;

        // Create the "Description" input and label
        const descriptionLabel = document.createElement('label');
        descriptionLabel.classList.add('form-label');
        descriptionLabel.setAttribute('for', 'inputDescription');
        descriptionLabel.innerText = 'Description';

        const descriptionInput = document.createElement('textarea');
        descriptionInput.classList.add('form-control');
        descriptionInput.name = `conditions[${conditionIndex}][description]`;
        descriptionInput.rows = 3;

        // Create the "Image" input and label
        const imageLabel = document.createElement('label');
        imageLabel.classList.add('form-label');
        imageLabel.setAttribute('for', 'inputConditionImage');
        imageLabel.innerText = 'Image';

        const imageInput = document.createElement('input');
        imageInput.type = 'file';
        imageInput.classList.add('form-control');
        imageInput.name = `conditions[${conditionIndex}][image]`;
        imageInput.accept = 'image/*';

        // Append the new elements to the new block
        const col1 = document.createElement('div');
        col1.classList.add('col-md-3');
        col1.appendChild(conditionsLabel); // Add label before the dropdown
        col1.appendChild(select);

        const col2 = document.createElement('div');
        col2.classList.add('col-md-3');
        col2.appendChild(partLabel); // Add label before the input
        col2.appendChild(partInput);

        const col3 = document.createElement('div');
        col3.classList.add('col-md-3');
        col3.appendChild(descriptionLabel); // Add label before the textarea
        col3.appendChild(descriptionInput);

        const col4 = document.createElement('div');
        col4.classList.add('col-md-3');
        col4.appendChild(imageLabel); // Add label before the input
        col4.appendChild(imageInput);

        newBlock.appendChild(col1);
        newBlock.appendChild(col2);
        newBlock.appendChild(col3);
        newBlock.appendChild(col4);

        // Append the new block to the container
        container.appendChild(newBlock);

        conditionIndex++;
    }
</script>

@endsection
