@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Page Header -->
            <div class="page-header mb-4">
                <div class="d-flex align-items-center">
                    <a href="{{ route('requests.index') }}" class="btn-back me-3">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h2 class="page-title">
                            <i class="fas fa-plus-circle me-2" style="color: var(--orange-primary);"></i>
                            New Service Request
                        </h2>
                        <p class="page-subtitle">Submit a new request for public service assistance</p>
                    </div>
                </div>
            </div>

            <!-- Progress Steps -->
            <div class="progress-steps mb-4">
                <div class="step active">
                    <div class="step-number">1</div>
                    <div class="step-label">Details</div>
                </div>
                <div class="step-line"></div>
                <div class="step">
                    <div class="step-number">2</div>
                    <div class="step-label">Location</div>
                </div>
                <div class="step-line"></div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-label">Image</div>
                </div>
                <div class="step-line"></div>
                <div class="step">
                    <div class="step-number">4</div>
                    <div class="step-label">Review</div>
                </div>
            </div>

            <!-- Main Form Card -->
            <div class="form-card">
                <div class="form-card-header">
                    <div class="d-flex align-items-center">
                        <div class="header-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">Request Information</h5>
                            <small class="text-secondary">Please fill in all required fields</small>
                        </div>
                    </div>
                    <div class="header-badge">
                        <i class="fas fa-info-circle me-1"></i>
                        All fields marked with * are required
                    </div>
                </div>

                <div class="form-card-body">
                    <form method="POST" action="{{ route('requests.store') }}" enctype="multipart/form-data" id="requestForm">
                        @csrf

                        <!-- Step 1: Basic Details -->
                        <div class="form-step" id="step1">
                            <!-- Title -->
                            <div class="form-group mb-4">
                                <label for="title" class="form-label">
                                    <i class="fas fa-heading me-2"></i>
                                    Request Title
                                    <span class="required">*</span>
                                </label>
                                <div class="input-wrapper">
                                    <i class="fas fa-heading input-icon"></i>
                                    <input type="text"
                                           class="form-control @error('title') is-invalid @enderror"
                                           id="title"
                                           name="title"
                                           value="{{ old('title') }}"
                                           placeholder="e.g., Pothole on Main Street"
                                           required
                                           autofocus>
                                </div>
                                @error('title')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-secondary">
                                    Choose a clear and descriptive title for your request
                                </small>
                            </div>

                            <!-- Category -->
                            <div class="form-group mb-4">
                                <label for="category_id" class="form-label">
                                    <i class="fas fa-tag me-2"></i>
                                    Category
                                    <span class="required">*</span>
                                </label>
                                <div class="select-wrapper">
                                    <i class="fas fa-chevron-down select-icon"></i>
                                    <select class="form-select @error('category_id') is-invalid @enderror"
                                            id="category_id"
                                            name="category_id"
                                            required>
                                        <option value="" disabled selected>Select a category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('category_id')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="form-group mb-4">
                                <label for="description" class="form-label">
                                    <i class="fas fa-align-left me-2"></i>
                                    Description
                                    <span class="required">*</span>
                                </label>
                                <div class="textarea-wrapper">
                                    <i class="fas fa-align-left textarea-icon"></i>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description"
                                              name="description"
                                              rows="5"
                                              placeholder="Please provide detailed information about your request..."
                                              required>{{ old('description') }}</textarea>
                                </div>
                                @error('description')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="d-flex justify-content-between mt-2">
                                    <small class="form-text text-secondary">
                                        Include as much detail as possible to help us understand your request
                                    </small>
                                    <small class="character-count" id="descCount">0/500</small>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Location -->
                        <div class="form-step" id="step2" style="display: none;">
                            <div class="form-group mb-4">
                                <label for="location" class="form-label">
                                    <i class="fas fa-map-marker-alt me-2"></i>
                                    Location
                                    <span class="required">*</span>
                                </label>
                                <div class="input-wrapper">
                                    <i class="fas fa-map-marker-alt input-icon"></i>
                                    <input type="text"
                                           class="form-control @error('location') is-invalid @enderror"
                                           id="location"
                                           name="location"
                                           value="{{ old('location') }}"
                                           placeholder="e.g., 123 Main Street, City, State"
                                           required>
                                </div>
                                @error('location')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-secondary">
                                    Provide the exact location where this issue needs attention
                                </small>

                                <!-- Hidden lat/lng fields -->
                                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">

                                <!-- Google Map Picker -->
                                <div class="mt-3">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <small style="color: var(--text-secondary); font-weight: 600;">
                                            <i class="fas fa-map-marked-alt me-1" style="color: #f97316;"></i>
                                            Or pin your location on the map
                                        </small>
                                        <button type="button" id="useMyLocationBtn"
                                            style="background: rgba(249,115,22,0.1); border: 1px solid rgba(249,115,22,0.3);
                                                   color: #f97316; border-radius: 8px; padding: 0.3rem 0.75rem;
                                                   font-size: 0.8rem; cursor: pointer; transition: all 0.2s;">
                                            <i class="fas fa-crosshairs me-1"></i> Use My Location
                                        </button>
                                    </div>
                                    <div id="mapPicker" style="width:100%; height:320px; border-radius:12px;
                                         border: 1px solid var(--border-dark, #2d3748); overflow:hidden;
                                         background: #1a1e24;"></div>
                                    <small id="mapHint" style="color: var(--text-muted, #6b7280); font-size:0.75rem; margin-top:0.3rem; display:block;">
                                        <i class="fas fa-info-circle me-1"></i>Click anywhere on the map to set your location. The address will fill in automatically.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Image Upload -->
                        <div class="form-step" id="step3" style="display: none;">
                            <div class="form-group mb-4">
                                <label for="image" class="form-label">
                                    <i class="fas fa-image me-2"></i>
                                    Upload Image
                                    <span class="optional-badge">Optional</span>
                                </label>

                                <div class="upload-area" id="uploadArea">
                                    <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                    <h6>Drag & Drop or Click to Upload</h6>
                                    <p class="text-secondary">Supported formats: JPEG, PNG, JPG, GIF (Max 2MB)</p>
                                    <input type="file"
                                           class="file-input"
                                           id="image"
                                           name="image"
                                           accept="image/*"
                                           style="display: none;">
                                    <button type="button" class="btn-upload" id="uploadBtn">
                                        <i class="fas fa-upload me-2"></i>
                                        Choose Image
                                    </button>
                                </div>

                                <!-- Image Preview -->
                                <div class="image-preview" id="imagePreview" style="display: none;">
                                    <img src="" alt="Preview" id="previewImg">
                                    <button type="button" class="btn-remove-image" id="removeImage">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>

                                @error('image')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="upload-tips">
                                <h6><i class="fas fa-lightbulb me-2"></i>Tips for good photos:</h6>
                                <ul>
                                    <li><i class="fas fa-check-circle me-2"></i>Ensure good lighting</li>
                                    <li><i class="fas fa-check-circle me-2"></i>Show the problem clearly</li>
                                    <li><i class="fas fa-check-circle me-2"></i>Include landmarks for location reference</li>
                                    <li><i class="fas fa-check-circle me-2"></i>Avoid blurry or dark images</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Step 4: Review -->
                        <div class="form-step" id="step4" style="display: none;">
                            <div class="review-card">
                                <h6 class="review-title">Review Your Request</h6>
                                <p class="review-subtitle">Please verify all information before submitting</p>

                                <div class="review-item">
                                    <div class="review-label">Title:</div>
                                    <div class="review-value" id="reviewTitle">-</div>
                                </div>

                                <div class="review-item">
                                    <div class="review-label">Category:</div>
                                    <div class="review-value" id="reviewCategory">-</div>
                                </div>

                                <div class="review-item">
                                    <div class="review-label">Description:</div>
                                    <div class="review-value" id="reviewDescription">-</div>
                                </div>

                                <div class="review-item">
                                    <div class="review-label">Location:</div>
                                    <div class="review-value" id="reviewLocation">-</div>
                                </div>

                                <div class="review-item">
                                    <div class="review-label">Image:</div>
                                    <div class="review-value" id="reviewImage">No image uploaded</div>
                                </div>

                                <div class="review-notice">
                                    <i class="fas fa-info-circle me-2"></i>
                                    By submitting this request, you confirm that all information provided is accurate.
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="form-navigation">
                            <button type="button" class="btn-prev" id="prevBtn" onclick="prevStep()" disabled>
                                <i class="fas fa-arrow-left me-2"></i>
                                Previous
                            </button>

                            <button type="button" class="btn-next" id="nextBtn" onclick="nextStep()">
                                Next Step
                                <i class="fas fa-arrow-right ms-2"></i>
                            </button>

                            <button type="submit" class="btn-submit" id="submitBtn" style="display: none;">
                                <i class="fas fa-paper-plane me-2"></i>
                                Submit Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help Card -->
            <div class="help-card mt-4">
                <div class="help-icon">
                    <i class="fas fa-question-circle"></i>
                </div>
                <div class="help-content">
                    <h6>Need Help?</h6>
                    <p>If you're unsure about any information, check our FAQ or contact support.</p>
                    <div class="help-links">
                        <a href="{{ route('faqs.index') }}" class="help-link">
                            <i class="fas fa-question-circle me-1"></i>
                            View FAQ
                        </a>
                        <a href="{{ route('messages.index') }}" class="help-link">
                            <i class="fas fa-envelope me-1"></i>
                            Contact Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* =============================================
       TEXT VISIBILITY FIX
       ============================================= */
    .form-control,
    .form-select {
        color: #f1f5f9 !important;
        background-color: #1e2435 !important;
        -webkit-text-fill-color: #f1f5f9 !important;
    }

    .form-control::placeholder {
        color: rgba(148, 163, 184, 0.6) !important;
        -webkit-text-fill-color: rgba(148, 163, 184, 0.6) !important;
    }

    .form-control:focus,
    .form-select:focus {
        color: #f1f5f9 !important;
        background-color: #1e2435 !important;
        -webkit-text-fill-color: #f1f5f9 !important;
    }

    /* Fix autofill background overriding text color */
    .form-control:-webkit-autofill,
    .form-control:-webkit-autofill:hover,
    .form-control:-webkit-autofill:focus {
        -webkit-text-fill-color: #f1f5f9 !important;
        -webkit-box-shadow: 0 0 0px 1000px #1e2435 inset !important;
        transition: background-color 5000s ease-in-out 0s;
    }

    .form-select option {
        background-color: #1e2435;
        color: #f1f5f9;
    }

    textarea.form-control {
        color: #f1f5f9 !important;
        -webkit-text-fill-color: #f1f5f9 !important;
    }

    /* =============================================
       PAGE HEADER
       ============================================= */
    .page-header {
        background: linear-gradient(135deg, var(--dark-card) 0%, var(--dark-secondary) 100%);
        border: 1px solid var(--border-dark);
        border-radius: 20px;
        padding: 1.5rem 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .btn-back {
        width: 45px;
        height: 45px;
        background: rgba(249, 115, 22, 0.1);
        border: 1px solid rgba(249, 115, 22, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--orange-primary);
        font-size: 1.2rem;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-back:hover {
        background: var(--orange-primary);
        color: white;
        transform: translateX(-5px);
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .page-subtitle {
        color: var(--text-secondary);
        font-size: 1rem;
        margin-bottom: 0;
    }

    /* =============================================
       PROGRESS STEPS
       ============================================= */
    .progress-steps {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: var(--dark-card);
        border: 1px solid var(--border-dark);
        border-radius: 15px;
        padding: 1.5rem;
    }

    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        flex: 1;
    }

    .step-number {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.05);
        border: 2px solid var(--border-dark);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
        transition: all 0.3s ease;
    }

    .step.active .step-number {
        background: linear-gradient(135deg, var(--orange-primary), #f97316);
        border-color: var(--orange-primary);
        color: white;
        box-shadow: 0 0 20px rgba(249, 115, 22, 0.3);
    }

    .step-label {
        color: var(--text-secondary);
        font-size: 0.85rem;
        font-weight: 500;
    }

    .step.active .step-label {
        color: var(--orange-primary);
    }

    .step-line {
        flex: 0.5;
        height: 2px;
        background: var(--border-dark);
        margin: 0 10px;
    }

    /* =============================================
       FORM CARD
       ============================================= */
    .form-card {
        background: var(--dark-card);
        border: 1px solid var(--border-dark);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .form-card-header {
        padding: 1.5rem;
        background: rgba(0, 0, 0, 0.2);
        border-bottom: 1px solid var(--border-dark);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }

    .header-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--orange-primary), #f97316);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        margin-right: 1rem;
    }

    .header-badge {
        background: rgba(249, 115, 22, 0.1);
        color: var(--orange-primary);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        border: 1px solid rgba(249, 115, 22, 0.2);
    }

    .form-card-body {
        padding: 2rem;
    }

    /* =============================================
       FORM ELEMENTS
       ============================================= */
    .form-group {
        position: relative;
    }

    .form-label {
        color: var(--text-primary);
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
    }

    .form-label i {
        color: var(--orange-primary);
    }

    .required {
        color: #ef4444;
        margin-left: 0.25rem;
        font-size: 1.1rem;
    }

    .optional-badge {
        background: rgba(107, 114, 128, 0.2);
        color: var(--text-secondary);
        padding: 0.2rem 0.5rem;
        border-radius: 12px;
        font-size: 0.7rem;
        margin-left: 0.5rem;
        font-weight: normal;
    }

    .input-wrapper, .textarea-wrapper, .select-wrapper {
        position: relative;
    }

    .input-icon, .textarea-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        transition: all 0.3s ease;
        z-index: 1;
    }

    .textarea-icon {
        top: 1.25rem;
        transform: none;
    }

    .select-icon {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--orange-primary);
        pointer-events: none;
        z-index: 1;
    }

    .form-control, .form-select {
        width: 100%;
        padding: 0.875rem 1rem 0.875rem 2.75rem;
        border: 2px solid var(--input-border);
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-select {
        appearance: none;
        padding-right: 2.5rem;
    }

    textarea.form-control {
        padding-top: 1rem;
        min-height: 120px;
    }

    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: var(--orange-primary);
        box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.1);
    }

    .form-control:focus + .input-icon,
    .form-control:focus ~ .textarea-icon {
        color: var(--orange-primary);
    }

    .character-count {
        color: var(--text-secondary);
        font-size: 0.85rem;
        background: rgba(255, 255, 255, 0.05);
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
    }

    /* =============================================
       UPLOAD AREA
       ============================================= */
    .upload-area {
        background: rgba(249, 115, 22, 0.05);
        border: 2px dashed var(--border-dark);
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .upload-area:hover {
        border-color: var(--orange-primary);
        background: rgba(249, 115, 22, 0.1);
    }

    .upload-area.dragover {
        border-color: var(--orange-primary);
        background: rgba(249, 115, 22, 0.15);
        transform: scale(1.02);
    }

    .upload-icon {
        font-size: 3rem;
        color: var(--orange-primary);
        margin-bottom: 1rem;
    }

    .upload-area h6 {
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .btn-upload {
        padding: 0.6rem 1.5rem;
        background: linear-gradient(135deg, var(--orange-primary), #f97316);
        border: none;
        border-radius: 10px;
        color: white;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 1rem;
    }

    .btn-upload:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(249, 115, 22, 0.3);
    }

    /* =============================================
       IMAGE PREVIEW
       ============================================= */
    .image-preview {
        position: relative;
        margin-top: 1rem;
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid var(--orange-primary);
    }

    .image-preview img {
        width: 100%;
        max-height: 300px;
        object-fit: cover;
    }

    .btn-remove-image {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 35px;
        height: 35px;
        background: rgba(239, 68, 68, 0.9);
        border: none;
        border-radius: 50%;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-remove-image:hover {
        background: #ef4444;
        transform: scale(1.1);
    }

    /* =============================================
       UPLOAD TIPS
       ============================================= */
    .upload-tips {
        background: rgba(249, 115, 22, 0.05);
        border: 1px solid rgba(249, 115, 22, 0.1);
        border-radius: 12px;
        padding: 1rem;
    }

    .upload-tips h6 {
        color: var(--orange-primary);
        margin-bottom: 0.75rem;
    }

    .upload-tips ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .upload-tips li {
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
    }

    .upload-tips li i {
        color: #10b981;
    }

    /* =============================================
       REVIEW CARD
       ============================================= */
    .review-card {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid var(--border-dark);
        border-radius: 15px;
        padding: 1.5rem;
    }

    .review-title {
        color: var(--text-primary);
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .review-subtitle {
        color: var(--text-secondary);
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
    }

    .review-item {
        display: flex;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-dark);
    }

    .review-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .review-label {
        width: 120px;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .review-value {
        flex: 1;
        color: var(--text-primary);
    }

    .review-notice {
        margin-top: 1.5rem;
        padding: 1rem;
        background: rgba(249, 115, 22, 0.1);
        border: 1px solid rgba(249, 115, 22, 0.2);
        border-radius: 10px;
        color: var(--orange-primary);
        font-size: 0.9rem;
        display: flex;
        align-items: center;
    }

    /* =============================================
       FORM NAVIGATION
       ============================================= */
    .form-navigation {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid var(--border-dark);
    }

    .btn-prev, .btn-next, .btn-submit {
        padding: 0.875rem 1.5rem;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-prev {
        flex: 1;
        background: transparent;
        border: 2px solid var(--border-dark);
        color: var(--text-secondary);
    }

    .btn-prev:hover:not(:disabled) {
        border-color: var(--orange-primary);
        color: var(--orange-primary);
        transform: translateX(-5px);
    }

    .btn-prev:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .btn-next {
        flex: 2;
        background: linear-gradient(135deg, var(--orange-primary), #f97316);
        color: white;
        box-shadow: 0 4px 6px rgba(249, 115, 22, 0.3);
    }

    .btn-next:hover:not(:disabled) {
        transform: translateX(5px);
        box-shadow: 0 8px 15px rgba(249, 115, 22, 0.4);
    }

    .btn-submit {
        flex: 2;
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        box-shadow: 0 4px 6px rgba(16, 185, 129, 0.3);
    }

    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(16, 185, 129, 0.4);
    }

    /* =============================================
       ERROR MESSAGE
       ============================================= */
    .error-message {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid #ef4444;
        color: #f87171;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        margin-top: 0.5rem;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        animation: shake 0.5s ease;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }

    /* =============================================
       HELP CARD
       ============================================= */
    .help-card {
        background: var(--dark-card);
        border: 1px solid var(--border-dark);
        border-radius: 15px;
        padding: 1.5rem;
        display: flex;
        gap: 1.5rem;
        align-items: start;
        transition: all 0.3s ease;
    }

    .help-card:hover {
        border-color: var(--orange-primary);
        transform: translateY(-2px);
    }

    .help-icon {
        width: 50px;
        height: 50px;
        background: rgba(249, 115, 22, 0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--orange-primary);
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .help-content h6 {
        color: var(--text-primary);
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .help-content p {
        color: var(--text-secondary);
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }

    .help-links {
        display: flex;
        gap: 1rem;
    }

    .help-link {
        color: var(--orange-primary);
        text-decoration: none;
        font-size: 0.9rem;
        padding: 0.25rem 1rem;
        background: rgba(249, 115, 22, 0.1);
        border: 1px solid rgba(249, 115, 22, 0.2);
        border-radius: 20px;
        transition: all 0.3s ease;
    }

    .help-link:hover {
        background: var(--orange-primary);
        color: white;
    }

    /* =============================================
       MAP PREVIEW
       ============================================= */
    .map-preview {
        border: 1px solid var(--border-dark);
        border-radius: 12px;
        overflow: hidden;
    }

    .map-placeholder {
        height: 200px;
        background: var(--input-bg);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: var(--text-secondary);
    }

    .map-placeholder i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: var(--orange-primary);
    }

    /* =============================================
       AUTO SAVE NOTIFICATION
       ============================================= */
    .auto-save-notification {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        background: rgba(16, 185, 129, 0.9);
        color: white;
        padding: 0.75rem 1.25rem;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        animation: slideInUp 0.3s ease;
        z-index: 9999;
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }

    @keyframes slideInUp {
        from { transform: translateY(20px); opacity: 0; }
        to   { transform: translateY(0);   opacity: 1; }
    }

    /* =============================================
       RESPONSIVE
       ============================================= */
    @media (max-width: 768px) {
        .progress-steps {
            flex-direction: column;
            gap: 1rem;
        }

        .step {
            flex-direction: row;
            width: 100%;
            gap: 1rem;
        }

        .step-line {
            display: none;
        }

        .form-card-header {
            flex-direction: column;
            align-items: start;
            gap: 1rem;
        }

        .header-badge {
            width: 100%;
        }

        .form-navigation {
            flex-direction: column;
        }

        .help-card {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .help-links {
            justify-content: center;
        }

        .review-item {
            flex-direction: column;
            gap: 0.5rem;
        }

        .review-label {
            width: auto;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    let currentStep = 1;
    const totalSteps = 4;

    // Initialize form
    function initForm() {
        updateSteps();
        updateReview();
    }

    // Update step indicators
    function updateSteps() {
        for (let i = 1; i <= totalSteps; i++) {
            const step = document.querySelector(`.step:nth-child(${i * 2 - 1})`);
            if (i <= currentStep) {
                step.classList.add('active');
            } else {
                step.classList.remove('active');
            }

            const stepContent = document.getElementById(`step${i}`);
            if (stepContent) {
                stepContent.style.display = i === currentStep ? 'block' : 'none';
            }
        }

        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const submitBtn = document.getElementById('submitBtn');

        prevBtn.disabled = currentStep === 1;

        if (currentStep === totalSteps) {
            nextBtn.style.display = 'none';
            submitBtn.style.display = 'flex';
        } else {
            nextBtn.style.display = 'flex';
            submitBtn.style.display = 'none';
        }

        updateReview();
    }

    // Next step
    function nextStep() {
        if (currentStep < totalSteps) {
            if (validateStep(currentStep)) {
                currentStep++;
                updateSteps();
            }
        }
    }

    // Previous step
    function prevStep() {
        if (currentStep > 1) {
            currentStep--;
            updateSteps();
        }
    }

    // Validate current step
    function validateStep(step) {
        switch(step) {
            case 1:
                const title = document.getElementById('title').value.trim();
                const category = document.getElementById('category_id').value;
                const description = document.getElementById('description').value.trim();

                if (!title || !category || !description) {
                    alert('Please fill in all required fields in Step 1');
                    return false;
                }
                break;

            case 2:
                const location = document.getElementById('location').value.trim();
                if (!location) {
                    alert('Please enter a location in Step 2');
                    return false;
                }
                break;
        }
        return true;
    }

    // Update review section
    function updateReview() {
        document.getElementById('reviewTitle').textContent =
            document.getElementById('title').value || '-';

        const categorySelect = document.getElementById('category_id');
        const categoryText = categorySelect.options[categorySelect.selectedIndex]?.text || '-';
        document.getElementById('reviewCategory').textContent =
            categorySelect.value ? categoryText : '-';

        document.getElementById('reviewDescription').textContent =
            document.getElementById('description').value || '-';

        document.getElementById('reviewLocation').textContent =
            document.getElementById('location').value || '-';
    }

    // Character counter
    const descriptionEl = document.getElementById('description');
    const descCount = document.getElementById('descCount');

    descriptionEl.addEventListener('input', function() {
        const length = this.value.length;
        descCount.textContent = `${length}/500`;

        if (length > 500) {
            this.value = this.value.substring(0, 500);
        }

        updateReview();
    });

    // Image upload handling
    const uploadArea    = document.getElementById('uploadArea');
    const fileInput     = document.getElementById('image');
    const uploadBtn     = document.getElementById('uploadBtn');
    const imagePreview  = document.getElementById('imagePreview');
    const previewImg    = document.getElementById('previewImg');
    const removeImageBtn = document.getElementById('removeImage');

    uploadBtn.addEventListener('click', () => fileInput.click());

    uploadArea.addEventListener('click', (e) => {
        if (e.target !== uploadBtn) fileInput.click();
    });

    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleImageUpload(files[0]);
        }
    });

    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            handleImageUpload(this.files[0]);
        }
    });

    function handleImageUpload(file) {
        if (!file.type.match('image.*')) {
            alert('Please upload an image file');
            return;
        }
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB');
            return;
        }
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            uploadArea.style.display = 'none';
            imagePreview.style.display = 'block';
            document.getElementById('reviewImage').textContent = file.name;
        };
        reader.readAsDataURL(file);
    }

    removeImageBtn.addEventListener('click', function() {
        fileInput.value = '';
        uploadArea.style.display = 'block';
        imagePreview.style.display = 'none';
        document.getElementById('reviewImage').textContent = 'No image uploaded';
    });

    // Auto-save draft notification
    let autoSaveTimer;
    const formInputs = document.querySelectorAll('#requestForm input, #requestForm select, #requestForm textarea');

    formInputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(function() {
                const notification = document.createElement('div');
                notification.className = 'auto-save-notification';
                notification.innerHTML = '<i class="fas fa-check-circle"></i> Draft saved';
                document.body.appendChild(notification);
                setTimeout(() => notification.remove(), 2000);
            }, 2000);
        });
    });

    // Warn before leaving with unsaved changes
    let formChanged = false;
    formInputs.forEach(input => {
        input.addEventListener('input', () => { formChanged = true; });
    });

    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

    document.getElementById('requestForm').addEventListener('submit', function() {
        formChanged = false;
    });

    // Update review on any input change
    formInputs.forEach(input => {
        input.addEventListener('input', updateReview);
        input.addEventListener('change', updateReview);
    });

    // Initialize on load
    initForm();
</script>

<!-- Google Maps -->
<script>
    let map, marker, geocoder, geocodeTimer;

    function initMap() {
        const defaultCenter = { lat: 14.5995, lng: 120.9842 }; // Manila, Philippines

        map = new google.maps.Map(document.getElementById('mapPicker'), {
            center: defaultCenter,
            zoom: 13,
            styles: [
                { elementType: 'geometry', stylers: [{ color: '#1a1e24' }] },
                { elementType: 'labels.text.stroke', stylers: [{ color: '#0a0c0f' }] },
                { elementType: 'labels.text.fill', stylers: [{ color: '#9ca3af' }] },
                { featureType: 'road', elementType: 'geometry', stylers: [{ color: '#2d3748' }] },
                { featureType: 'road', elementType: 'labels.text.fill', stylers: [{ color: '#9ca3af' }] },
                { featureType: 'water', elementType: 'geometry', stylers: [{ color: '#0a0c0f' }] },
                { featureType: 'poi', elementType: 'geometry', stylers: [{ color: '#1e2329' }] },
                { featureType: 'transit', elementType: 'geometry', stylers: [{ color: '#2d3748' }] },
            ],
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: true,
        });

        geocoder = new google.maps.Geocoder();

        map.addListener('click', function(e) {
            clearTimeout(geocodeTimer); // cancel any pending text-input geocode
            placeMarker(e.latLng);
        });

        // If old value exists, geocode it to center map
        const existingLocation = document.getElementById('location').value;
        const existingLat = document.getElementById('latitude').value;
        const existingLng = document.getElementById('longitude').value;
        if (existingLat && existingLng) {
            const pos = { lat: parseFloat(existingLat), lng: parseFloat(existingLng) };
            placeMarkerSilent(pos);
            map.setCenter(pos);
            map.setZoom(16);
        }

        // Use My Location button
        document.getElementById('useMyLocationBtn').addEventListener('click', function() {
            if (!navigator.geolocation) {
                alert('Geolocation is not supported by your browser.');
                return;
            }
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Locating...';
            const btn = this;
            navigator.geolocation.getCurrentPosition(function(pos) {
                const latLng = new google.maps.LatLng(pos.coords.latitude, pos.coords.longitude);
                map.setCenter(latLng);
                map.setZoom(17);
                placeMarker(latLng);
                btn.innerHTML = '<i class="fas fa-crosshairs me-1"></i> Use My Location';
            }, function() {
                alert('Unable to get your location. Please allow location access.');
                btn.innerHTML = '<i class="fas fa-crosshairs me-1"></i> Use My Location';
            });
        });

        // Sync manual text input → map (debounced)
        document.getElementById('location').addEventListener('input', function() {
            const query = this.value.trim();
            clearTimeout(geocodeTimer);
            if (!query) return;
            geocodeTimer = setTimeout(function() {
                geocoder.geocode({ address: query }, function(results, status) {
                    if (status === 'OK' && results[0]) {
                        const latLng = results[0].geometry.location;
                        map.setCenter(latLng);
                        map.setZoom(16);
                        placeMarkerSilent(latLng);
                        document.getElementById('latitude').value = latLng.lat();
                        document.getElementById('longitude').value = latLng.lng();
                    }
                });
            }, 800);
        });
    }

    function placeMarker(latLng) {
        if (marker) marker.setMap(null);
        marker = new google.maps.Marker({
            position: latLng,
            map: map,
            draggable: true,
            animation: google.maps.Animation.DROP,
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 10,
                fillColor: '#f97316',
                fillOpacity: 1,
                strokeColor: '#fff',
                strokeWeight: 2,
            }
        });

        document.getElementById('latitude').value = latLng.lat();
        document.getElementById('longitude').value = latLng.lng();

        // Show loading state in the input
        const locationInput = document.getElementById('location');
        locationInput.placeholder = 'Getting address...';
        locationInput.style.opacity = '0.6';

        geocoder.geocode({ location: latLng }, function(results, status) {
            locationInput.style.opacity = '1';
            locationInput.placeholder = 'e.g., 123 Main Street, Barangay, City';

            if (status === 'OK' && results && results.length > 0) {
                // Skip Plus Code results, prefer readable street/place addresses
                const preferred = results.find(function(r) {
                    return !r.types.includes('plus_code') &&
                           (r.types.includes('street_address') ||
                            r.types.includes('premise') ||
                            r.types.includes('route') ||
                            r.types.includes('neighborhood') ||
                            r.types.includes('sublocality') ||
                            r.types.includes('locality'));
                });
                const best = preferred
                    || results.find(function(r) { return !r.types.includes('plus_code'); })
                    || results[0];

                locationInput.value = best.formatted_address;
                document.getElementById('reviewLocation').textContent = best.formatted_address;
            } else {
                locationInput.value = '';
                locationInput.placeholder = 'Address not found. Please type manually.';
            }
        });

        marker.addListener('dragend', function(e) {
            clearTimeout(geocodeTimer);
            placeMarker(e.latLng);
        });
    }

    function placeMarkerSilent(pos) {
        if (marker) marker.setMap(null);
        marker = new google.maps.Marker({
            position: pos,
            map: map,
            draggable: true,
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 10,
                fillColor: '#f97316',
                fillOpacity: 1,
                strokeColor: '#fff',
                strokeWeight: 2,
            }
        });
        marker.addListener('dragend', function(e) {
            placeMarker(e.latLng);
        });
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBwM-a1ufR8_gO-jlnumgft9P9OIx8g1_0&callback=initMap" async defer></script>
@endpush