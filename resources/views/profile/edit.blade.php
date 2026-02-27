@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Page Header -->
            <div class="page-header mb-4">
                <div class="d-flex align-items-center">
                    <div class="header-icon">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div>
                        <h2 class="page-title">Profile Settings</h2>
                        <p class="page-subtitle">Manage your account information and security</p>
                    </div>
                </div>
            </div>

            <!-- Profile Overview Card -->
            <div class="profile-overview mb-4">
                <div class="overlay-bg"></div>
                <div class="profile-info">
                    <div class="profile-avatar">
                        <i class="fas fa-user-circle"></i>
                        <div class="avatar-badge">
                            <i class="fas fa-camera"></i>
                        </div>
                    </div>
                    <div class="profile-details">
                        <h3>{{ auth()->user()->name }}</h3>
                        <p class="text-secondary">{{ auth()->user()->email }}</p>
                        <span class="role-badge {{ auth()->user()->role }}">
                            <i class="fas {{ auth()->user()->role == 'admin' ? 'fa-crown' : (auth()->user()->role == 'staff' ? 'fa-user-tie' : 'fa-user') }}"></i>
                            {{ ucfirst(auth()->user()->role) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Settings Card -->
            <div class="settings-card">
                <!-- Tabs Navigation -->
                <div class="settings-tabs">
                    <button class="tab-btn active" onclick="switchTab('profile')">
                        <i class="fas fa-user me-2"></i>
                        Profile Information
                    </button>
                    <button class="tab-btn" onclick="switchTab('security')">
                        <i class="fas fa-shield-alt me-2"></i>
                        Security
                    </button>
                    <button class="tab-btn" onclick="switchTab('preferences')">
                        <i class="fas fa-sliders-h me-2"></i>
                        Preferences
                    </button>
                </div>

                <div class="settings-content">
                    <!-- Profile Information Tab -->
                    <div class="tab-pane active" id="profile-tab">
                        <form method="post" action="{{ route('profile.update') }}" id="profileForm">
                            @csrf
                            @method('patch')

                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Personal Information
                                </h5>

                                <!-- Name -->
                                <div class="form-group">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-user me-2"></i>
                                        Full Name
                                        <span class="required">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <i class="fas fa-user input-icon"></i>
                                        <input type="text" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name', auth()->user()->name) }}" 
                                               required
                                               placeholder="Enter your full name">
                                    </div>
                                    @error('name')
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="form-group">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope me-2"></i>
                                        Email Address
                                        <span class="required">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <i class="fas fa-envelope input-icon"></i>
                                        <input type="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email', auth()->user()->email) }}" 
                                               required
                                               placeholder="Enter your email">
                                    </div>
                                    @error('email')
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div class="form-group">
                                    <label for="phone" class="form-label">
                                        <i class="fas fa-phone me-2"></i>
                                        Phone Number
                                        <span class="optional-badge">Optional</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <i class="fas fa-phone input-icon"></i>
                                        <input type="tel" 
                                               class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" 
                                               name="phone" 
                                               value="{{ old('phone', auth()->user()->phone) }}"
                                               placeholder="+1 (555) 123-4567">
                                    </div>
                                    @error('phone')
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Address -->
                                <div class="form-group">
                                    <label for="address" class="form-label">
                                        <i class="fas fa-map-marker-alt me-2"></i>
                                        Address
                                        <span class="optional-badge">Optional</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <i class="fas fa-map-marker-alt textarea-icon"></i>
                                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                                  id="address" 
                                                  name="address" 
                                                  rows="3"
                                                  placeholder="123 Main St, City, State 12345">{{ old('address', auth()->user()->address) }}</textarea>
                                    </div>
                                    @error('address')
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn-save" id="saveProfileBtn">
                                    <i class="fas fa-save me-2"></i>
                                    Save Changes
                                </button>
                                <button type="button" class="btn-cancel" onclick="resetProfileForm()">
                                    <i class="fas fa-undo me-2"></i>
                                    Reset
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Security Tab -->
                    <div class="tab-pane" id="security-tab" style="display: none;">
                        <form method="post" action="{{ route('password.update') }}" id="passwordForm">
                            @csrf
                            @method('put')

                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-lock me-2"></i>
                                    Change Password
                                </h5>

                                <!-- Current Password -->
                                <div class="form-group">
                                    <label for="current_password" class="form-label">
                                        <i class="fas fa-lock me-2"></i>
                                        Current Password
                                        <span class="required">*</span>
                                    </label>
                                    <div class="password-wrapper">
                                        <i class="fas fa-lock input-icon"></i>
                                        <input type="password" 
                                               class="form-control @error('current_password') is-invalid @enderror" 
                                               id="current_password" 
                                               name="current_password" 
                                               required
                                               placeholder="Enter your current password">
                                        <i class="fas fa-eye password-toggle" onclick="togglePassword('current_password', this)"></i>
                                    </div>
                                    @error('current_password')
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- New Password -->
                                <div class="form-group">
                                    <label for="password" class="form-label">
                                        <i class="fas fa-key me-2"></i>
                                        New Password
                                        <span class="required">*</span>
                                    </label>
                                    <div class="password-wrapper">
                                        <i class="fas fa-key input-icon"></i>
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               required
                                               placeholder="Enter new password"
                                               onkeyup="checkPasswordStrength()">
                                        <i class="fas fa-eye password-toggle" onclick="togglePassword('password', this)"></i>
                                    </div>
                                    
                                    <!-- Password Strength Meter -->
                                    <div class="password-strength">
                                        <div class="strength-bar">
                                            <div class="strength-segment" id="strength1"></div>
                                            <div class="strength-segment" id="strength2"></div>
                                            <div class="strength-segment" id="strength3"></div>
                                        </div>
                                        <div class="strength-text">
                                            Password strength: <span id="strength-text">Enter password</span>
                                        </div>
                                    </div>

                                    <!-- Password Requirements -->
                                    <div class="password-requirements">
                                        <div class="requirement" id="req-length">
                                            <i class="fas fa-circle"></i>
                                            At least 8 characters
                                        </div>
                                        <div class="requirement" id="req-uppercase">
                                            <i class="fas fa-circle"></i>
                                            One uppercase letter
                                        </div>
                                        <div class="requirement" id="req-lowercase">
                                            <i class="fas fa-circle"></i>
                                            One lowercase letter
                                        </div>
                                        <div class="requirement" id="req-number">
                                            <i class="fas fa-circle"></i>
                                            One number
                                        </div>
                                        <div class="requirement" id="req-special">
                                            <i class="fas fa-circle"></i>
                                            One special character
                                        </div>
                                    </div>

                                    @error('password')
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div class="form-group">
                                    <label for="password_confirmation" class="form-label">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Confirm New Password
                                        <span class="required">*</span>
                                    </label>
                                    <div class="password-wrapper">
                                        <i class="fas fa-check-circle input-icon"></i>
                                        <input type="password" 
                                               class="form-control" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               required
                                               placeholder="Confirm your new password"
                                               onkeyup="checkPasswordMatch()">
                                        <i class="fas fa-eye password-toggle" onclick="togglePassword('password_confirmation', this)"></i>
                                    </div>
                                    <div id="password-match-message" class="requirement" style="margin-top: 0.5rem;">
                                        <i class="fas fa-circle"></i>
                                        Passwords match
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn-save" id="savePasswordBtn">
                                    <i class="fas fa-save me-2"></i>
                                    Update Password
                                </button>
                                <button type="button" class="btn-cancel" onclick="resetPasswordForm()">
                                    <i class="fas fa-undo me-2"></i>
                                    Reset
                                </button>
                            </div>
                        </form>

                        <!-- Two Factor Authentication (Optional) -->
                        <div class="two-factor-section mt-4">
                            <h5 class="section-title">
                                <i class="fas fa-mobile-alt me-2"></i>
                                Two-Factor Authentication
                            </h5>
                            <p class="text-secondary mb-3">Add an extra layer of security to your account</p>
                            <button class="btn-enable-2fa" onclick="enable2FA()">
                                <i class="fas fa-shield-alt me-2"></i>
                                Enable 2FA
                            </button>
                        </div>
                    </div>

                    <!-- Preferences Tab -->
                    <div class="tab-pane" id="preferences-tab" style="display: none;">
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="fas fa-bell me-2"></i>
                                Notification Preferences
                            </h5>

                            <div class="preferences-list">
                                <div class="preference-item">
                                    <div class="preference-info">
                                        <h6>Email Notifications</h6>
                                        <p class="text-secondary">Receive email updates about your requests</p>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox" checked>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>

                                <div class="preference-item">
                                    <div class="preference-info">
                                        <h6>SMS Notifications</h6>
                                        <p class="text-secondary">Get text messages for status updates</p>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox">
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>

                                <div class="preference-item">
                                    <div class="preference-info">
                                        <h6>Browser Notifications</h6>
                                        <p class="text-secondary">Receive real-time alerts in your browser</p>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox" checked>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-section mt-4">
                            <h5 class="section-title">
                                <i class="fas fa-palette me-2"></i>
                                Theme Preferences
                            </h5>

                            <div class="theme-options">
                                <div class="theme-option active">
                                    <div class="theme-preview dark"></div>
                                    <span>Dark</span>
                                </div>
                                <div class="theme-option">
                                    <div class="theme-preview light"></div>
                                    <span>Light</span>
                                </div>
                                <div class="theme-option">
                                    <div class="theme-preview system"></div>
                                    <span>System</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="danger-zone mt-4">
                <div class="danger-header">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Danger Zone
                </div>
                <div class="danger-body">
                    <div class="danger-item">
                        <div class="danger-info">
                            <h6>Delete Account</h6>
                            <p class="text-secondary">Permanently delete your account and all associated data</p>
                        </div>
                        <button class="btn-delete" onclick="confirmDelete()">
                            <i class="fas fa-trash-alt me-2"></i>
                            Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2" style="color: #ef4444;"></i>
                    Delete Account
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Are you sure you want to delete your account? This action cannot be undone.</p>
                <p class="text-secondary mb-3">All your data, including requests, messages, and feedback will be permanently removed.</p>
                <form action="{{ route('profile.destroy') }}" method="POST" id="deleteForm">
                    @csrf
                    @method('delete')
                    <label for="delete_password" class="form-label">Please enter your password to confirm</label>
                    <input type="password" class="form-control" name="password" id="delete_password" required>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="document.getElementById('deleteForm').submit()">
                    <i class="fas fa-trash-alt me-2"></i>
                    Permanently Delete
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Page Header */
    .page-header {
        background: linear-gradient(135deg, var(--dark-card) 0%, var(--dark-secondary) 100%);
        border: 1px solid var(--border-dark);
        border-radius: 20px;
        padding: 1.5rem 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .header-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--orange-primary), #f97316);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1.5rem;
        font-size: 2rem;
        color: white;
        box-shadow: 0 10px 20px rgba(249, 115, 22, 0.3);
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

    /* Profile Overview */
    .profile-overview {
        background: linear-gradient(135deg, var(--dark-card) 0%, var(--dark-secondary) 100%);
        border: 1px solid var(--border-dark);
        border-radius: 20px;
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }

    .overlay-bg {
        position: absolute;
        top: 0;
        right: 0;
        width: 300px;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(249, 115, 22, 0.1));
        transform: skewX(-20deg) translateX(100px);
    }

    .profile-info {
        display: flex;
        align-items: center;
        gap: 2rem;
        position: relative;
        z-index: 1;
    }

    .profile-avatar {
        position: relative;
        width: 100px;
        height: 100px;
    }

    .profile-avatar i {
        font-size: 6rem;
        color: var(--orange-primary);
    }

    .avatar-badge {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 30px;
        height: 30px;
        background: var(--orange-primary);
        border: 2px solid var(--dark-card);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .avatar-badge:hover {
        transform: scale(1.1);
        background: var(--orange-hover);
    }

    .profile-details h3 {
        color: var(--text-primary);
        font-size: 1.8rem;
        margin-bottom: 0.25rem;
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .role-badge.admin {
        background: rgba(249, 115, 22, 0.1);
        color: var(--orange-primary);
        border: 1px solid rgba(249, 115, 22, 0.2);
    }

    .role-badge.staff {
        background: rgba(6, 182, 212, 0.1);
        color: #06b6d4;
        border: 1px solid rgba(6, 182, 212, 0.2);
    }

    .role-badge.citizen {
        background: rgba(107, 114, 128, 0.1);
        color: var(--text-secondary);
        border: 1px solid var(--border-dark);
    }

    /* Settings Card */
    .settings-card {
        background: var(--dark-card);
        border: 1px solid var(--border-dark);
        border-radius: 20px;
        overflow: hidden;
    }

    .settings-tabs {
        display: flex;
        background: rgba(0, 0, 0, 0.2);
        border-bottom: 1px solid var(--border-dark);
        padding: 0.5rem;
    }

    .tab-btn {
        flex: 1;
        padding: 1rem;
        background: transparent;
        border: none;
        color: var(--text-secondary);
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border-radius: 10px;
    }

    .tab-btn:hover {
        color: var(--orange-primary);
        background: rgba(249, 115, 22, 0.1);
    }

    .tab-btn.active {
        background: linear-gradient(135deg, var(--orange-primary), #f97316);
        color: white;
    }

    .settings-content {
        padding: 2rem;
    }

    .tab-pane {
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Form Sections */
    .form-section {
        margin-bottom: 2rem;
    }

    .section-title {
        color: var(--text-primary);
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--border-dark);
    }

    .section-title i {
        color: var(--orange-primary);
    }

    /* Form Elements */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        color: var(--text-primary);
        font-weight: 500;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
    }

    .form-label i {
        color: var(--orange-primary);
    }

    .required {
        color: #ef4444;
        margin-left: 0.25rem;
    }

    .optional-badge {
        background: rgba(107, 114, 128, 0.2);
        color: var(--text-secondary);
        padding: 0.2rem 0.5rem;
        border-radius: 12px;
        font-size: 0.7rem;
        margin-left: 0.5rem;
    }

    .input-wrapper, .password-wrapper {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        transition: all 0.3s ease;
        z-index: 1;
    }

    .textarea-icon {
        position: absolute;
        left: 1rem;
        top: 1.25rem;
        color: var(--text-muted);
        transition: all 0.3s ease;
        z-index: 1;
    }

    .password-toggle {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 1;
    }

    .password-toggle:hover {
        color: var(--orange-primary);
    }

    .form-control {
        width: 100%;
        padding: 0.875rem 1rem 0.875rem 2.75rem;
        background-color: var(--input-bg);
        border: 2px solid var(--input-border);
        border-radius: 10px;
        color: var(--text-primary);
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    textarea.form-control {
        padding-left: 2.75rem;
        resize: vertical;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--orange-primary);
        background-color: var(--dark-card);
        box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.1);
    }

    .form-control:focus + .input-icon,
    .form-control:focus ~ .textarea-icon {
        color: var(--orange-primary);
    }

    /* Password Strength */
    .password-strength {
        margin-top: 0.75rem;
    }

    .strength-bar {
        display: flex;
        gap: 0.25rem;
        margin-bottom: 0.25rem;
    }

    .strength-segment {
        height: 4px;
        flex: 1;
        background-color: var(--border-dark);
        border-radius: 2px;
        transition: all 0.3s ease;
    }

    .strength-segment.weak {
        background-color: #ef4444;
    }

    .strength-segment.medium {
        background-color: #f59e0b;
    }

    .strength-segment.strong {
        background-color: #10b981;
    }

    .strength-text {
        color: var(--text-secondary);
        font-size: 0.85rem;
    }

    /* Password Requirements */
    .password-requirements {
        background: rgba(0, 0, 0, 0.2);
        border: 1px solid var(--border-dark);
        border-radius: 10px;
        padding: 1rem;
        margin-top: 0.75rem;
    }

    .requirement {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-secondary);
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
    }

    .requirement:last-child {
        margin-bottom: 0;
    }

    .requirement.met {
        color: #10b981;
    }

    .requirement.met i {
        color: #10b981;
    }

    /* Error Message */
    .error-message {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid #ef4444;
        color: #f87171;
        padding: 0.5rem 1rem;
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

    /* Form Actions */
    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border-dark);
    }

    .btn-save {
        flex: 2;
        padding: 0.875rem;
        background: linear-gradient(135deg, var(--orange-primary), #f97316);
        border: none;
        border-radius: 10px;
        color: white;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(249, 115, 22, 0.3);
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(249, 115, 22, 0.4);
    }

    .btn-cancel {
        flex: 1;
        padding: 0.875rem;
        background: transparent;
        border: 1px solid var(--border-dark);
        border-radius: 10px;
        color: var(--text-secondary);
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        border-color: var(--orange-primary);
        color: var(--orange-primary);
    }

    /* Preferences */
    .preferences-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .preference-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid var(--border-dark);
        border-radius: 10px;
    }

    .preference-info h6 {
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: var(--border-dark);
        transition: .3s;
        border-radius: 24px;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 2px;
        bottom: 2px;
        background-color: white;
        transition: .3s;
        border-radius: 50%;
    }

    input:checked + .toggle-slider {
        background-color: var(--orange-primary);
    }

    input:checked + .toggle-slider:before {
        transform: translateX(26px);
    }

    /* Theme Options */
    .theme-options {
        display: flex;
        gap: 1rem;
    }

    .theme-option {
        flex: 1;
        text-align: center;
        cursor: pointer;
    }

    .theme-preview {
        height: 80px;
        border-radius: 10px;
        margin-bottom: 0.5rem;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .theme-preview.dark {
        background: linear-gradient(135deg, #0a0c0f, #1a1e24);
    }

    .theme-preview.light {
        background: linear-gradient(135deg, #ffffff, #f3f4f6);
    }

    .theme-preview.system {
        background: linear-gradient(135deg, #0a0c0f 50%, #ffffff 50%);
    }

    .theme-option.active .theme-preview {
        border-color: var(--orange-primary);
        box-shadow: 0 0 20px rgba(249, 115, 22, 0.3);
    }

    .theme-option span {
        color: var(--text-secondary);
    }

    .theme-option.active span {
        color: var(--orange-primary);
        font-weight: 600;
    }

    /* Two Factor Auth */
    .two-factor-section {
        padding: 1.5rem;
        background: rgba(249, 115, 22, 0.05);
        border: 1px solid rgba(249, 115, 22, 0.1);
        border-radius: 15px;
    }

    .btn-enable-2fa {
        padding: 0.75rem 1.5rem;
        background: transparent;
        border: 2px solid var(--orange-primary);
        border-radius: 10px;
        color: var(--orange-primary);
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-enable-2fa:hover {
        background: var(--orange-primary);
        color: white;
        transform: translateY(-2px);
    }

    /* Danger Zone */
    .danger-zone {
        background: rgba(239, 68, 68, 0.05);
        border: 1px solid rgba(239, 68, 68, 0.2);
        border-radius: 15px;
        overflow: hidden;
    }

    .danger-header {
        padding: 1rem 1.5rem;
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        font-weight: 600;
        border-bottom: 1px solid rgba(239, 68, 68, 0.2);
    }

    .danger-body {
        padding: 1.5rem;
    }

    .danger-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .danger-info h6 {
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .btn-delete {
        padding: 0.75rem 1.5rem;
        background: transparent;
        border: 2px solid #ef4444;
        border-radius: 10px;
        color: #ef4444;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-delete:hover {
        background: #ef4444;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(239, 68, 68, 0.3);
    }

    /* Modal */
    .modal-content {
        background: var(--dark-card);
        border: 1px solid var(--border-dark);
    }

    .modal-header {
        border-bottom: 1px solid var(--border-dark);
    }

    .modal-title {
        color: var(--text-primary);
    }

    .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .profile-info {
            flex-direction: column;
            text-align: center;
        }
        
        .settings-tabs {
            flex-direction: column;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .danger-item {
            flex-direction: column;
            text-align: center;
        }
        
        .theme-options {
            flex-direction: column;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    let currentTab = 'profile';

    // Switch tabs
    function switchTab(tab) {
        // Update tab buttons
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');
        
        // Hide all tabs
        document.querySelectorAll('.tab-pane').forEach(pane => pane.style.display = 'none');
        
        // Show selected tab
        document.getElementById(`${tab}-tab`).style.display = 'block';
        currentTab = tab;
    }

    // Toggle password visibility
    function togglePassword(inputId, icon) {
        const input = document.getElementById(inputId);
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Password strength checker
    function checkPasswordStrength() {
        const password = document.getElementById('password').value;
        const strength1 = document.getElementById('strength1');
        const strength2 = document.getElementById('strength2');
        const strength3 = document.getElementById('strength3');
        const strengthText = document.getElementById('strength-text');

        // Requirements
        const hasLength = password.length >= 8;
        const hasUpperCase = /[A-Z]/.test(password);
        const hasLowerCase = /[a-z]/.test(password);
        const hasNumber = /[0-9]/.test(password);
        const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);

        // Update requirement indicators
        updateRequirement('req-length', hasLength);
        updateRequirement('req-uppercase', hasUpperCase);
        updateRequirement('req-lowercase', hasLowerCase);
        updateRequirement('req-number', hasNumber);
        updateRequirement('req-special', hasSpecial);

        // Calculate strength
        const requirements = [hasLength, hasUpperCase, hasLowerCase, hasNumber, hasSpecial];
        const metCount = requirements.filter(Boolean).length;

        // Update strength meter
        strength1.className = 'strength-segment';
        strength2.className = 'strength-segment';
        strength3.className = 'strength-segment';

        if (password.length === 0) {
            strengthText.textContent = 'Enter password';
        } else if (metCount <= 2) {
            strength1.classList.add('weak');
            strengthText.textContent = 'Weak';
        } else if (metCount <= 4) {
            strength1.classList.add('medium');
            strength2.classList.add('medium');
            strengthText.textContent = 'Medium';
        } else {
            strength1.classList.add('strong');
            strength2.classList.add('strong');
            strength3.classList.add('strong');
            strengthText.textContent = 'Strong';
        }

        checkPasswordMatch();
    }

    // Update requirement indicator
    function updateRequirement(elementId, met) {
        const element = document.getElementById(elementId);
        if (met) {
            element.classList.add('met');
            element.querySelector('i').className = 'fas fa-check-circle';
        } else {
            element.classList.remove('met');
            element.querySelector('i').className = 'fas fa-circle';
        }
    }

    // Check password match
    function checkPasswordMatch() {
        const password = document.getElementById('password').value;
        const confirm = document.getElementById('password_confirmation').value;
        const matchMessage = document.getElementById('password-match-message');

        if (confirm.length > 0) {
            if (password === confirm) {
                matchMessage.classList.add('met');
                matchMessage.querySelector('i').className = 'fas fa-check-circle';
            } else {
                matchMessage.classList.remove('met');
                matchMessage.querySelector('i').className = 'fas fa-circle';
            }
        }
    }

    // Reset forms
    function resetProfileForm() {
        document.getElementById('profileForm').reset();
    }

    function resetPasswordForm() {
        document.getElementById('passwordForm').reset();
        checkPasswordStrength();
    }

    // Enable 2FA
    function enable2FA() {
        showNotification('2FA feature coming soon!', 'info');
    }

    // Confirm delete
    function confirmDelete() {
        $('#deleteModal').modal('show');
    }

    // Show notification
    function showNotification(message, type) {
        const notification = $(`
            <div class="notification ${type}">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                ${message}
            </div>
        `);
        
        $('body').append(notification);
        
        notification.css({
            'position': 'fixed',
            'top': '20px',
            'right': '20px',
            'background': type === 'success' ? '#10b981' : (type === 'error' ? '#ef4444' : '#3b82f6'),
            'color': 'white',
            'padding': '1rem 2rem',
            'border-radius': '10px',
            'box-shadow': '0 5px 20px rgba(0,0,0,0.3)',
            'z-index': '9999',
            'display': 'flex',
            'align-items': 'center',
            'gap': '0.75rem',
            'animation': 'slideInRight 0.3s ease'
        });
        
        setTimeout(() => {
            notification.fadeOut(300, function() {
                $(this).remove();
            });
        }, 3000);
    }

    // Form submission loading states
    $('#profileForm').on('submit', function(e) {
        const btn = $('#saveProfileBtn');
        btn.html('<i class="fas fa-spinner fa-spin me-2"></i> Saving...').prop('disabled', true);
    });

    $('#passwordForm').on('submit', function(e) {
        const btn = $('#savePasswordBtn');
        btn.html('<i class="fas fa-spinner fa-spin me-2"></i> Updating...').prop('disabled', true);
    });

    // Auto-resize textarea
    $('#address').on('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    // Initialize
    $(document).ready(function() {
        // Set initial textarea height
        $('#address').css('height', 'auto');
        $('#address').css('height', $('#address')[0].scrollHeight + 'px');
    });
</script>
@endpush
