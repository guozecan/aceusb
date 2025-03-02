@extends('customer.layouts')

@section('page-title', "Product")

@push('css')
    <link rel="stylesheet" href="{{asset_url('customer/styles/dist/product.min.css?v=2024090217')}}"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        /* Custom Styles */
        .product-hero-section {
            background: linear-gradient(135deg, #4e54c8, #8f94fb);
            color: white;
            padding: 4rem 0;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
        }
        
        .form-section {
            background-color: #f8f9fa;
            border-radius: 0.75rem;
            padding: 2rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 2rem;
        }
        
        .form-section h3 {
            color: #4e54c8;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 0.75rem;
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            font-weight: 600;
        }
        
        .required {
            color: #dc3545;
            margin-left: 0.25rem;
        }
        
        .thumbnail {
            cursor: pointer;
            border: 2px solid transparent;
            border-radius: 0.25rem;
            transition: all 0.2s ease;
        }
        
        .thumbnail.active {
            border-color: #4e54c8;
        }
        
        .main-image {
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        
        .thumbnails-wrapper {
            display: flex;
            gap: 0.5rem;
            overflow-x: auto;
            padding: 0.5rem 0;
        }
        
        .upload-dropzone {
            border: 2px dashed #ced4da;
            border-radius: 0.5rem;
            padding: 2rem;
            text-align: center;
            transition: all 0.2s ease;
            background-color: #f8f9fa;
        }
        
        .upload-dropzone:hover {
            border-color: #4e54c8;
            background-color: #f1f3fa;
        }
        
        .progress-indicator {
            position: fixed;
            top: 0;
            left: 0;
            height: 5px;
            background: linear-gradient(90deg, #4e54c8, #8f94fb);
            z-index: 1100;
            transition: width 0.3s ease;
        }
        
        /* Tab styling */
        .form-tabs .nav-link {
            color: #495057;
            border: none;
            padding: 1rem 1.5rem;
            border-radius: 0.5rem 0.5rem 0 0;
            font-weight: 600;
        }
        
        .form-tabs .nav-link.active {
            background-color: #f8f9fa;
            color: #4e54c8;
            border-bottom: 3px solid #4e54c8;
        }
        
        .form-tabs .nav-link i {
            margin-right: 0.5rem;
        }
        
        /* Product gallery improvements */
        .product-gallery {
            position: sticky;
            top: 1rem;
        }
        
        /* Form validation styling */
        .was-validated .form-control:invalid,
        .form-control.is-invalid {
            border-color: #dc3545;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
            padding-right: calc(1.5em + 0.75rem);
        }
        
        /* Tooltip styling */
        .custom-tooltip {
            position: relative;
            display: inline-block;
            margin-left: 0.25rem;
            cursor: help;
        }
        
        .custom-tooltip i {
            color: #6c757d;
        }
        
        .custom-tooltip .tooltip-text {
            visibility: hidden;
            width: 200px;
            background-color: #343a40;
            color: #fff;
            text-align: center;
            border-radius: 0.25rem;
            padding: 0.5rem;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -100px;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 0.875rem;
        }
        
        .custom-tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }
        
        /* Accordion for form sections */
        .form-accordion .accordion-button:not(.collapsed) {
            background-color: #e7f1ff;
            color: #4e54c8;
        }
        
        .form-accordion .accordion-button:focus {
            box-shadow: none;
            border-color: rgba(78, 84, 200, 0.25);
        }
        
        /* Improved shopping cart button */
        .btn-add-to-cart {
            background: linear-gradient(135deg, #4e54c8, #8f94fb);
            border: none;
            padding: 1rem 2rem;
            font-size: 1.25rem;
            font-weight: 600;
            border-radius: 2rem;
            transition: all 0.3s ease;
        }
        
        .btn-add-to-cart:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            background: linear-gradient(135deg, #3941a0, #7a80e3);
        }
        
        /* Image preview styling */
        .image-preview {
            max-height: 200px;
            border-radius: 0.5rem;
            margin-top: 1rem;
        }
        
        /* Stepper UI for form progress */
        .form-stepper {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
        }
        
        .step {
            flex: 1;
            text-align: center;
            position: relative;
        }
        
        .step:not(:last-child):after {
            content: '';
            position: absolute;
            top: 1rem;
            width: 100%;
            height: 2px;
            background-color: #e9ecef;
            left: 50%;
        }
        
        .step.active:not(:last-child):after,
        .step.completed:not(:last-child):after {
            background-color: #4e54c8;
        }
        
        .step-number {
            background-color: #e9ecef;
            color: #6c757d;
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.5rem;
            font-weight: 600;
            position: relative;
            z-index: 1;
        }
        
        .step.active .step-number,
        .step.completed .step-number {
            background-color: #4e54c8;
            color: white;
        }
        
        .step-title {
            font-size: 0.875rem;
            color: #6c757d;
        }
        
        .step.active .step-title,
        .step.completed .step-title {
            color: #212529;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')
    <!-- Progress indicator -->
    <div class="progress-indicator" id="formProgress" style="width: 0%"></div>

    <div class="product-show">
        <div class="product-show-id">
            <!-- Hero Section -->
            <section class="product-hero-section py-5 mb-4">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-7">
                            <h1 class="display-4 fw-bold mb-3">{{$product->name}}</h1>
                            <p class="lead mb-4">{{$product->meta_title}} - Premium quality ID with excellent design and features.</p>
                            <div class="d-flex align-items-center mb-4">
                                <h2 class="h3 text-white mb-0">{{single_price($product->price)}}</h2>
                                <span class="ms-2 text-white-50">per item</span>
                            </div>
                            <a href="#purchase-form" class="btn btn-light btn-lg px-4 py-3 rounded-pill">
                                <i class="bi bi-cart-plus me-2"></i>Order Now
                            </a>
                        </div>
                        <div class="col-lg-5 d-none d-lg-block">
                            <img src="{{image_url($product->gallery[0])}}" alt="{{$product->name}}" class="img-fluid rounded shadow-lg">
                        </div>
                    </div>
                </div>
            </section>

            <!-- Product Information Section -->
            <section class="container mb-5">
                <div class="row g-4">
                    <div class="col-lg-5">
                        <div class="product-gallery">
                            <div class="main-image mb-3">
                                <img src="{{image_url($product->gallery[0])}}" id="showGallery" alt="{{$product->name}}" class="img-fluid rounded">
                            </div>
                            <div class="thumbnail-gallery">
                                <div class="thumbnails-wrapper">
                                    @foreach($product->gallery as $key => $photo)
                                        <img src="{{image_url($photo)}}" alt="Thumbnail {{$key+1}}" class="thumbnail {{$key == 0 ? 'active' : ''}}" data-src="{{image_url($photo)}}" width="80">
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="product-details p-4 bg-white rounded shadow-sm">
                            <h2 class="h3 mb-4 border-bottom pb-3">Product Details</h2>
                            <div class="product-description mb-4">
                                {!! $product->description !!}
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div>
                                    <span class="h4 mb-0">{{single_price($product->price)}}</span>
                                    <span class="text-muted ms-1">per item</span>
                                </div>
                                <a href="#purchase-form" class="btn btn-primary btn-lg">
                                    <i class="bi bi-arrow-down-circle me-2"></i>Order Now
                                </a>
                            </div>
                            <div class="d-flex align-items-center text-muted mt-3">
                                <i class="bi bi-shield-check me-2 fs-5"></i>
                                <span>Secure checkout</span>
                                <i class="bi bi-truck ms-4 me-2 fs-5"></i>
                                <span>Fast delivery</span>
                                <i class="bi bi-stars ms-4 me-2 fs-5"></i>
                                <span>Premium quality</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Form Stepper -->
            <div class="container mb-4" id="purchase-form">
                <div class="form-stepper">
                    <div class="step active" id="step1">
                        <div class="step-number">1</div>
                        <div class="step-title">Personal Info</div>
                    </div>
                    <div class="step" id="step2">
                        <div class="step-number">2</div>
                        <div class="step-title">Additional Details</div>
                    </div>
                    <div class="step" id="step3">
                        <div class="step-number">3</div>
                        <div class="step-title">Upload Photos</div>
                    </div>
                    <div class="step" id="step4">
                        <div class="step-number">4</div>
                        <div class="step-title">Review & Order</div>
                    </div>
                </div>
            </div>

            <!-- Form Tabs and Content -->
            <div class="container mb-5">
                <div class="card shadow-sm">
                    <div class="card-header bg-white p-0">
                        <ul class="nav nav-tabs form-tabs" id="formTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" 
                                        type="button" role="tab" aria-selected="true">
                                    <i class="bi bi-person"></i>Personal Information
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="additional-tab" data-bs-toggle="tab" data-bs-target="#additional" 
                                        type="button" role="tab" aria-selected="false">
                                    <i class="bi bi-card-list"></i>Additional Details
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="uploads-tab" data-bs-toggle="tab" data-bs-target="#uploads" 
                                        type="button" role="tab" aria-selected="false">
                                    <i class="bi bi-upload"></i>Photo Uploads
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="review-tab" data-bs-toggle="tab" data-bs-target="#review" 
                                        type="button" role="tab" aria-selected="false">
                                    <i class="bi bi-check-circle"></i>Review & Submit
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body p-4">
                        @include('customer.shared._errors')
                        <form action="{{route('order-detail.store')}}" method="post" enctype="multipart/form-data" id="myForm" class="needs-validation" novalidate>
                            @csrf
                            <input type="hidden" name="product" value="{{$product->id}}"/>
                            
                            <div class="tab-content" id="formTabsContent">
                                <!-- Personal Information Tab -->
                                <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                                    <h3 class="h5 mb-4"><i class="bi bi-person-badge me-2"></i>Personal Information</h3>
                                    
                                    <div class="row mb-4">
                                        <div class="col-md-4">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" name="firstname" id="firstname-field" 
                                                       value="{{ old('firstname') }}" required placeholder="First Name">
                                                <label for="firstname-field">First Name<span class="required">*</span></label>
                                                <div class="invalid-feedback">Please enter your first name</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" name="middlename" id="middlename-field" 
                                                       value="{{ old('middlename') }}" placeholder="Middle Name">
                                                <label for="middlename-field">Middle Name</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" name="lastname" id="lastname-field" 
                                                       value="{{ old('lastname') }}" required placeholder="Last Name">
                                                <label for="lastname-field">Last Name<span class="required">*</span></label>
                                                <div class="invalid-feedback">Please enter your last name</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-4">
                                        <div class="col-md-4">
                                            <div class="form-floating mb-3">
                                                <select class="form-select" name="gender" id="gender-field" required>
                                                    <option value="" disabled selected>Select gender</option>
                                                    <option value="MALE" {{old('gender') == 'MALE' ? 'selected' : ''}}>MALE</option>
                                                    <option value="FEMALE" {{old('gender') == 'FEMALE' ? 'selected' : ''}}>FEMALE</option>
                                                    <option value="X" {{old('gender') == 'X' ? 'selected' : ''}}>X</option>
                                                </select>
                                                <label for="gender-field">Gender<span class="required">*</span></label>
                                                <div class="invalid-feedback">Please select your gender</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating mb-3">
                                                <select class="form-select" name="eye_color" id="eye_color-field" required>
                                                    <option value="" disabled selected>Select eye color</option>
                                                    @foreach($eye_color as $k=> $v)
                                                        <option value="{{$k}}" {{old('eye_color') == $k ? 'selected' : ''}}>{{$v}}</option>
                                                    @endforeach
                                                </select>
                                                <label for="eye_color-field">Eye Color<span class="required">*</span></label>
                                                <div class="invalid-feedback">Please select your eye color</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating mb-3">
                                                <select class="form-select" name="hair_color" id="hair_color-field" required>
                                                    <option value="" disabled selected>Select hair color</option>
                                                    @foreach($hair_color as $k=> $v)
                                                        <option value="{{$k}}" {{old('hair_color') == $k ? 'selected' : ''}}>{{$v}}</option>
                                                    @endforeach
                                                </select>
                                                <label for="hair_color-field">Hair Color<span class="required">*</span></label>
                                                <div class="invalid-feedback">Please select your hair color</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-4">
                                        <div class="col-md-4">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" name="dob" id="dob-field" 
                                                       value="{{ old('dob') }}" required placeholder="mm/dd/yyyy">
                                                <label for="dob-field">Birthday<span class="required">*</span></label>
                                                <div class="invalid-feedback">Please enter a valid birth date</div>
                                                <small id="dob-error" class="text-danger" style="display: none;">
                                                    Please enter a valid date in the format mm/dd/yyyy.
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating mb-3">
                                                <select class="form-select" name="height" id="height-field" required>
                                                    <option value="" disabled selected>Select height</option>
                                                    @foreach($height as $k=> $v)
                                                        <option value="{{$k}}" {{old('height') == $k ? 'selected' : ''}}>{{$v}}</option>
                                                    @endforeach
                                                </select>
                                                <label for="height-field">Height<span class="required">*</span></label>
                                                <div class="invalid-feedback">Please select your height</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" name="weight" id="weight-field" 
                                                       value="{{ old('weight') }}" required placeholder="Weight">
                                                <label for="weight-field">Weight<span class="required">*</span></label>
                                                <div class="invalid-feedback">Please enter your weight</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="button" class="btn btn-outline-secondary" disabled>
                                            <i class="bi bi-arrow-left me-2"></i>Previous
                                        </button>
                                        <button type="button" class="btn btn-primary next-tab" data-tab="additional-tab">
                                            Next<i class="bi bi-arrow-right ms-2"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Additional Information Tab -->
                                <div class="tab-pane fade" id="additional" role="tabpanel" aria-labelledby="additional-tab">
                                    <h3 class="h5 mb-4"><i class="bi bi-card-list me-2"></i>Additional Information</h3>
                                    
                                    <div class="card mb-4 border-0 bg-light">
                                        <div class="card-body">
                                            <h4 class="h6 mb-3"><i class="bi bi-geo-alt me-2"></i>Address Information</h4>
                                            
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" name="address" id="address-field" 
                                                       value="{{ old('address') }}" required placeholder="Address">
                                                <label for="address-field">Street Address (On ID)<span class="required">*</span></label>
                                                <div class="invalid-feedback">Please enter your street address</div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" name="city" id="city-field" 
                                                               value="{{ old('city') }}" placeholder="City">
                                                        <label for="city-field">City</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" name="zip" id="zip-field" 
                                                               value="{{ old('zip') }}" placeholder="Zip Code">
                                                        <label for="zip-field">Zip Code</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="card mb-4 border-0 bg-light">
                                        <div class="card-body">
                                            <h4 class="h6 mb-3"><i class="bi bi-card-checklist me-2"></i>ID Details</h4>
                                            
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" name="release_date" id="release_date-field" 
                                                               value="{{ old('release_date') }}" placeholder="mm/dd/yyyy">
                                                        <label for="release_date-field">ISS Date</label>
                                                        <small id="release_date-error" class="text-danger" style="display: none;">
                                                            Please enter a valid date in the format mm/dd/yyyy.
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <select class="form-select" name="restrictions" id="restrictions-field">
                                                            <option value="" selected>Please Select</option>
                                                            <option value="NO" {{ old('restrictions') == 'NO' ? 'selected' : '' }}>NO</option>
                                                            <option value="YES" {{ old('restrictions') == 'YES' ? 'selected' : '' }}>YES</option>
                                                        </select>
                                                        <label for="restrictions-field">Restrictions (Corrective Lenses)</label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <select class="form-select" name="organ_donor" id="organ_donor-field">
                                                            <option value="" selected>Please Select</option>
                                                            <option value="NO" {{ old('organ_donor') == 'NO' ? 'selected' : '' }}>NO</option>
                                                            <option value="YES" {{ old('organ_donor') == 'YES' ? 'selected' : '' }}>YES</option>
                                                        </select>
                                                        <label for="organ_donor-field">Organ Donor</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" name="exp_date" id="exp_date-field" 
                                                               value="{{ old('exp_date') }}" placeholder="mm/dd/yyyy">
                                                        <label for="exp_date-field">EXP Date</label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" name="dl" id="dl-field" 
                                                               value="{{ old('dl') }}" placeholder="DL#">
                                                        <label for="dl-field">DL# (Number)</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" name="additional" id="additional-field" 
                                                               value="{{ old('additional') }}" 
                                                               placeholder="Additional Information">
                                                        <label for="additional-field">Additional Information</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="button" class="btn btn-outline-secondary prev-tab" data-tab="personal-tab">
                                            <i class="bi bi-arrow-left me-2"></i>Previous
                                        </button>
                                        <button type="button" class="btn btn-primary next-tab" data-tab="uploads-tab">
                                            Next<i class="bi bi-arrow-right ms-2"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Photo Uploads Tab -->
                                <div class="tab-pane fade" id="uploads" role="tabpanel" aria-labelledby="uploads-tab">
                                    <h3 class="h5 mb-4"><i class="bi bi-upload me-2"></i>Image/Signature Uploads</h3>
                                    
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <div class="card h-100 border-0 shadow-sm">
                                                <div class="card-header bg-primary text-white">
                                                    <h5 class="mb-0"><i class="bi bi-person-square me-2"></i>Photo ID Upload</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="upload-dropzone" id="photo-dropzone">
                                                        <input type="file" class="fileinput d-none" accept="image/*"<input type="file" class="fileinput d-none" accept="image/*" 
                                                               id="photo-input" name="photo" required>
                                                        <label for="photo-input" class="mb-0">
                                                            <div class="text-center">
                                                                <i class="bi bi-cloud-arrow-up-fill text-primary fs-1 mb-3"></i>
                                                                <h5>Upload Your Photo</h5>
                                                                <p class="text-muted mb-2">Drag and drop files here or click to browse</p>
                                                                <small class="d-block text-muted">Accepts: JPG, PNG, GIF (Max: 5MB)</small>
                                                                <button type="button" class="btn btn-outline-primary mt-3">
                                                                    <i class="bi bi-upload me-2"></i>Select File
                                                                </button>
                                                            </div>
                                                        </label>
                                                        <div class="invalid-feedback">Please upload a photo ID</div>
                                                    </div>
                                                    <div class="mt-3 d-none" id="photo-preview-container">
                                                        <div class="position-relative">
                                                            <img src="#" alt="Photo preview" id="photo-preview" class="img-fluid rounded image-preview">
                                                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" id="remove-photo">
                                                                <i class="bi bi-x-lg"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="mt-3">
                                                        <p class="mb-2 fw-semibold">Photo Requirements:</p>
                                                        <ul class="small text-muted ps-3 mb-0">
                                                            <li>Clear, recent headshot with neutral expression</li>
                                                            <li>Plain background (white or light color preferred)</li>
                                                            <li>Good lighting with no shadows on face</li>
                                                            <li>No hats, sunglasses, or heavy makeup</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="card h-100 border-0 shadow-sm">
                                                <div class="card-header bg-primary text-white">
                                                    <h5 class="mb-0"><i class="bi bi-pen me-2"></i>Signature Upload</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="upload-dropzone" id="signature-dropzone">
                                                        <input type="file" class="fileinput d-none" accept="image/*" 
                                                               id="signature-input" name="signature" required>
                                                        <label for="signature-input" class="mb-0">
                                                            <div class="text-center">
                                                                <i class="bi bi-file-earmark-text text-primary fs-1 mb-3"></i>
                                                                <h5>Upload Your Signature</h5>
                                                                <p class="text-muted mb-2">Drag and drop files here or click to browse</p>
                                                                <small class="d-block text-muted">Accepts: JPG, PNG, GIF (Max: 5MB)</small>
                                                                <button type="button" class="btn btn-outline-primary mt-3">
                                                                    <i class="bi bi-upload me-2"></i>Select File
                                                                </button>
                                                            </div>
                                                        </label>
                                                        <div class="invalid-feedback">Please upload your signature</div>
                                                    </div>
                                                    <div class="mt-3 d-none" id="signature-preview-container">
                                                        <div class="position-relative">
                                                            <img src="#" alt="Signature preview" id="signature-preview" class="img-fluid rounded image-preview">
                                                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" id="remove-signature">
                                                                <i class="bi bi-x-lg"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="mt-3 text-center">
                                                        <p class="mb-1">Or</p>
                                                        <button type="button" class="btn btn-outline-secondary" id="draw-signature-btn">
                                                            <i class="bi bi-pencil me-2"></i>Draw Signature
                                                        </button>
                                                    </div>
                                                    
                                                    <div class="mt-3">
                                                        <p class="mb-2 fw-semibold">Signature Requirements:</p>
                                                        <ul class="small text-muted ps-3 mb-0">
                                                            <li>Clear, legible signature on white background</li>
                                                            <li>Dark ink preferred (black or blue)</li>
                                                            <li>Complete signature as it appears on official documents</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="button" class="btn btn-outline-secondary prev-tab" data-tab="additional-tab">
                                            <i class="bi bi-arrow-left me-2"></i>Previous
                                        </button>
                                        <button type="button" class="btn btn-primary next-tab" data-tab="review-tab">
                                            Next<i class="bi bi-arrow-right ms-2"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Review & Submit Tab -->
                                <div class="tab-pane fade" id="review" role="tabpanel" aria-labelledby="review-tab">
                                    <h3 class="h5 mb-4"><i class="bi bi-check-circle me-2"></i>Review Your Order</h3>
                                    
                                    <div class="alert alert-info mb-4">
                                        <div class="d-flex">
                                            <i class="bi bi-info-circle-fill me-3 fs-4"></i>
                                            <div>
                                                <p class="mb-0">Please review all information below carefully before submitting your order. You can go back to any section to make changes if needed.</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion form-accordion mb-4" id="reviewAccordion">
                                        <!-- Personal Information Review -->
                                        <div class="accordion-item border-0 shadow-sm mb-3">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#personalReview">
                                                    <i class="bi bi-person me-2"></i>Personal Information
                                                </button>
                                            </h2>
                                            <div id="personalReview" class="accordion-collapse collapse show" data-bs-parent="#reviewAccordion">
                                                <div class="accordion-body">
                                                    <div class="row g-3">
                                                        <div class="col-md-4">
                                                            <p class="mb-1 fw-semibold">Name:</p>
                                                            <p id="review-name" class="mb-0">-</p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p class="mb-1 fw-semibold">Gender:</p>
                                                            <p id="review-gender" class="mb-0">-</p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p class="mb-1 fw-semibold">Date of Birth:</p>
                                                            <p id="review-dob" class="mb-0">-</p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p class="mb-1 fw-semibold">Eye Color:</p>
                                                            <p id="review-eye_color" class="mb-0">-</p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p class="mb-1 fw-semibold">Hair Color:</p>
                                                            <p id="review-hair_color" class="mb-0">-</p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p class="mb-1 fw-semibold">Height/Weight:</p>
                                                            <p id="review-height-weight" class="mb-0">-</p>
                                                        </div>
                                                    </div>
                                                    <div class="text-end mt-3">
                                                        <button type="button" class="btn btn-sm btn-primary edit-section" data-tab="personal-tab">
                                                            <i class="bi bi-pencil me-1"></i>Edit
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Additional Information Review -->
                                        <div class="accordion-item border-0 shadow-sm mb-3">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#additionalReview">
                                                    <i class="bi bi-card-list me-2"></i>Additional Information
                                                </button>
                                            </h2>
                                            <div id="additionalReview" class="accordion-collapse collapse" data-bs-parent="#reviewAccordion">
                                                <div class="accordion-body">
                                                    <div class="row g-3">
                                                        <div class="col-md-12">
                                                            <p class="mb-1 fw-semibold">Address:</p>
                                                            <p id="review-address" class="mb-0">-</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="mb-1 fw-semibold">City:</p>
                                                            <p id="review-city" class="mb-0">-</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="mb-1 fw-semibold">Zip Code:</p>
                                                            <p id="review-zip" class="mb-0">-</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="mb-1 fw-semibold">Issue Date:</p>
                                                            <p id="review-release_date" class="mb-0">-</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="mb-1 fw-semibold">Expiration Date:</p>
                                                            <p id="review-exp_date" class="mb-0">-</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="mb-1 fw-semibold">DL Number:</p>
                                                            <p id="review-dl" class="mb-0">-</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="mb-1 fw-semibold">Restrictions:</p>
                                                            <p id="review-restrictions" class="mb-0">-</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="mb-1 fw-semibold">Organ Donor:</p>
                                                            <p id="review-organ_donor" class="mb-0">-</p>
                                                        </div>
                                                    </div>
                                                    <div class="text-end mt-3">
                                                        <button type="button" class="btn btn-sm btn-primary edit-section" data-tab="additional-tab">
                                                            <i class="bi bi-pencil me-1"></i>Edit
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Uploads Review -->
                                        <div class="accordion-item border-0 shadow-sm mb-3">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#uploadsReview">
                                                    <i class="bi bi-file-earmark-image me-2"></i>Uploaded Files
                                                </button>
                                            </h2>
                                            <div id="uploadsReview" class="accordion-collapse collapse" data-bs-parent="#reviewAccordion">
                                                <div class="accordion-body">
                                                    <div class="row g-4">
                                                        <div class="col-md-6">
                                                            <p class="fw-semibold">Photo ID:</p>
                                                            <div class="card">
                                                                <div class="card-body text-center p-3" id="review-photo-container">
                                                                    <div class="text-muted py-3">
                                                                        <i class="bi bi-image fs-2 d-block mb-2"></i>
                                                                        No photo uploaded
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="fw-semibold">Signature:</p>
                                                            <div class="card">
                                                                <div class="card-body text-center p-3" id="review-signature-container">
                                                                    <div class="text-muted py-3">
                                                                        <i class="bi bi-pen fs-2 d-block mb-2"></i>
                                                                        No signature uploaded
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-end mt-3">
                                                        <button type="button" class="btn btn-sm btn-primary edit-section" data-tab="uploads-tab">
                                                            <i class="bi bi-pencil me-1"></i>Edit
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Product Details -->
                                        <div class="accordion-item border-0 shadow-sm mb-3">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#productReview">
                                                    <i class="bi bi-bag me-2"></i>Product Details
                                                </button>
                                            </h2>
                                            <div id="productReview" class="accordion-collapse collapse" data-bs-parent="#reviewAccordion">
                                                <div class="accordion-body">
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0">
                                                            <img src="{{image_url($product->gallery[0])}}" alt="{{$product->name}}" class="img-thumbnail" style="width: 100px;">
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <h5 class="mb-1">{{$product->name}}</h5>
                                                            <p class="text-muted mb-2">{{$product->meta_title}}</p>
                                                            <p class="fw-bold mb-0">{{single_price($product->price)}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="card border-0 shadow-sm mb-4">
                                        <div class="card-body">
                                            <h5 class="mb-3"><i class="bi bi-credit-card me-2"></i>Order Summary</h5>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Item cost:</span>
                                                <span>{{single_price($product->price)}}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Shipping & handling:</span>
                                                <span>{{single_price(0)}}</span>
                                            </div>
                                            <hr>
                                            <div class="d-flex justify-content-between fw-bold">
                                                <span>Total:</span>
                                                <span class="text-primary">{{single_price($product->price)}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-check mb-4">
                                        <input class="form-check-input" type="checkbox" id="terms-check" required>
                                        <label class="form-check-label" for="terms-check">
                                            I confirm that all information provided is accurate and complete.
                                        </label>
                                        <div class="invalid-feedback">
                                            You must agree before submitting.
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-outline-secondary prev-tab" data-tab="uploads-tab">
                                            <i class="bi bi-arrow-left me-2"></i>Previous
                                        </button>
                                        <button type="submit" class="btn btn-add-to-cart">
                                            <i class="bi bi-cart-check me-2"></i>Place Order
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Signature Drawing Modal -->
            <div class="modal fade" id="signatureModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="bi bi-pen me-2"></i>Draw Your Signature</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="signature-pad-container border rounded mb-3">
                                <canvas id="signature-pad" class="signature-pad" width="765" height="300"></canvas>
                            </div>
                            <div class="d-flex justify-content-center gap-2">
                                <button type="button" class="btn btn-outline-secondary" id="clear-signature">
                                    <i class="bi bi-x-lg me-1"></i>Clear
                                </button>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="save-signature">
                                <i class="bi bi-check-lg me-1"></i>Save Signature
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize form validation
            const form = document.getElementById('myForm');
            
            // Date picker initialization
            flatpickr("#dob-field", {
                dateFormat: "m/d/Y",
                maxDate: new Date(),
                onChange: function(selectedDates, dateStr) {
                    validateDateFormat(dateStr, 'dob-error');
                }
            });
            
            flatpickr("#release_date-field", {
                dateFormat: "m/d/Y",
                onChange: function(selectedDates, dateStr) {
                    validateDateFormat(dateStr, 'release_date-error');
                }
            });
            
            flatpickr("#exp_date-field", {
                dateFormat: "m/d/Y"
            });
            
            // Validate date format
            function validateDateFormat(dateStr, errorElementId) {
                const errorElement = document.getElementById(errorElementId);
                const datePattern = /^(0?[1-9]|1[0-2])\/(0?[1-9]|[12][0-9]|3[01])\/\d{4}$/;
                
                if (dateStr && !datePattern.test(dateStr)) {
                    errorElement.style.display = 'block';
                    return false;
                } else {
                    errorElement.style.display = 'none';
                    return true;
                }
            }
            
            // Form tabs navigation
            const formTabs = document.querySelectorAll('.nav-link');
            const nextButtons = document.querySelectorAll('.next-tab');
            const prevButtons = document.querySelectorAll('.prev-tab');
            const editButtons = document.querySelectorAll('.edit-section');
            const steps = document.querySelectorAll('.step');
            
            // Progress indicator update
            function updateProgressIndicator(activeTabIndex) {
                const progress = ((activeTabIndex + 1) / formTabs.length) * 100;
                document.getElementById('formProgress').style.width = progress + '%';
                
                // Update stepper UI
                steps.forEach((step, index) => {
                    if (index < activeTabIndex + 1) {
                        step.classList.add('completed');
                    } else {
                        step.classList.remove('completed');
                    }
                    
                    if (index === activeTabIndex) {
                        step.classList.add('active');
                    } else {
                        step.classList.remove('active');
                    }
                });
            }
            
            nextButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetTabId = this.getAttribute('data-tab');
                    const targetTab = document.getElementById(targetTabId);
                    
                    // Validate current tab before proceeding
                    const currentTabId = document.querySelector('.tab-pane.active').id;
                    if (validateTabInputs(currentTabId)) {
                        // Show the target tab
                        const tabTrigger = new bootstrap.Tab(targetTab);
                        tabTrigger.show();
                        
                        // Update progress
                        const activeTabIndex = Array.from(formTabs).findIndex(tab => tab.id === targetTabId);
                        updateProgressIndicator(activeTabIndex);
                        
                        // Update review data
                        updateReviewData();
                    }
                });
            });
            
            prevButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetTabId = this.getAttribute('data-tab');
                    const targetTab = document.getElementById(targetTabId);
                    
                    // Show the target tab
                    const tabTrigger = new bootstrap.Tab(targetTab);
                    tabTrigger.show();
                    
                    // Update progress
                    const activeTabIndex = Array.from(formTabs).findIndex(tab => tab.id === targetTabId);
                    updateProgressIndicator(activeTabIndex);
                });
            });
            
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetTabId = this.getAttribute('data-tab');
                    const targetTab = document.getElementById(targetTabId);
                    
                    // Show the target tab
                    const tabTrigger = new bootstrap.Tab(targetTab);
                    tabTrigger.show();
                    
                    // Update progress
                    const activeTabIndex = Array.from(formTabs).findIndex(tab => tab.id === targetTabId);
                    updateProgressIndicator(activeTabIndex);
                });
            });
            
            // Tab change event
            formTabs.forEach((tab, index) => {
                tab.addEventListener('shown.bs.tab', function() {
                    updateProgressIndicator(index);
                });
            });
            
            // Validate tab fields
            function validateTabInputs(tabId) {
                const tabPane = document.getElementById(tabId);
                const requiredInputs = tabPane.querySelectorAll('input[required], select[required]');
                let isValid = true;
                
                requiredInputs.forEach(input => {
                    if (!input.value.trim()) {
                        input.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        input.classList.remove('is-invalid');
                    }
                });
                
                return isValid;
            }
            
            // Update review data
            function updateReviewData() {
                // Personal information
                const firstName = document.getElementById('firstname-field').value || '-';
                const middleName = document.getElementById('middlename-field').value || '';
                const lastName = document.getElementById('lastname-field').value || '-';
                const fullName = [firstName, middleName, lastName].filter(Boolean).join(' ');
                document.getElementById('review-name').textContent = fullName;
                
                const gender = document.getElementById('gender-field');
                document.getElementById('review-gender').textContent = gender.options[gender.selectedIndex]?.text || '-';
                
                document.getElementById('review-dob').textContent = document.getElementById('dob-field').value || '-';
                
                const eyeColor = document.getElementById('eye_color-field');
                document.getElementById('review-eye_color').textContent = eyeColor.options[eyeColor.selectedIndex]?.text || '-';
                
                const hairColor = document.getElementById('hair_color-field');
                document.getElementById('review-hair_color').textContent = hairColor.options[hairColor.selectedIndex]?.text || '-';
                
                const height = document.getElementById('height-field');
                const heightText = height.options[height.selectedIndex]?.text || '-';
                const weightText = document.getElementById('weight-field').value || '-';
                document.getElementById('review-height-weight').textContent = `${heightText} / ${weightText}`;
                
                // Additional information
                const address = document.getElementById('address-field').value || '-';
                const city = document.getElementById('city-field').value || '-';
                const zip = document.getElementById('zip-field').value || '-';
                document.getElementById('review-address').textContent = address;
                document.getElementById('review-city').textContent = city;
                document.getElementById('review-zip').textContent = zip;
                
                document.getElementById('review-release_date').textContent = document.getElementById('release_date-field').value || '-';
                document.getElementById('review-exp_date').textContent = document.getElementById('exp_date-field').value || '-';
                document.getElementById('review-dl').textContent = document.getElementById('dl-field').value || '-';
                
                const restrictions = document.getElementById('restrictions-field');
                document.getElementById('review-restrictions').textContent = restrictions.options[restrictions.selectedIndex]?.text || '-';
                
                const organDonor = document.getElementById('organ_donor-field');
                document.getElementById('review-organ_donor').textContent = organDonor.options[organDonor.selectedIndex]?.text || '-';
                
                // Photo and signature previews
                const photoPreview = document.getElementById('photo-preview');
                const reviewPhotoContainer = document.getElementById('review-photo-container');
                if (photoPreview.src && photoPreview.src !== '#' && photoPreview.src !== window.location.href) {
                    reviewPhotoContainer.innerHTML = `<img src="${photoPreview.src}" alt="Photo ID" class="img-fluid" style="max-height: 150px;">`;
                }
                
                const signaturePreview = document.getElementById('signature-preview');
                const reviewSignatureContainer = document.getElementById('review-signature-container');
                if (signaturePreview.src && signaturePreview.src !== '#' && signaturePreview.src !== window.location.href) {
                    reviewSignatureContainer.innerHTML = `<img src="${signaturePreview.src}" alt="Signature" class="img-fluid" style="max-height: 100px;">`;
                }
            }
            
            // File upload preview handlers
            const photoInput = document.getElementById('photo-input');
            const photoPreview = document.getElementById('photo-preview');
            const photoPreviewContainer = document.getElementById('photo-preview-container');
            const removePhotoBtn = document.getElementById('remove-photo');
            
            photoInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        photoPreview.src = e.target.result;
                        photoPreviewContainer.classList.remove('d-none');
                        photoInput.classList.remove('is-invalid');
                    };
                    reader.readAsDataURL(file);
                }
            });
            
            removePhotoBtn.addEventListener('click', function() {
                photoInput.value = '';
                photoPreviewContainer.classList.add('d-none');
                photoPreview.src = '#';
            });
            
            const signatureInput = document.getElementById('signature-input');
            const signaturePreview = document.getElementById('signature-preview');
            const signaturePreviewContainer = document.getElementById('signature-preview-container');
            const removeSignatureBtn = document.getElementById('remove-signature');
            
            signatureInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        signaturePreview.src = e.target.result;
                        signaturePreviewContainer.classList.remove('d-none');
                        signatureInput.classList.remove('is
