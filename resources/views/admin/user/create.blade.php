@extends('admin.layouts.app')

@section('title', 'Home')

@section('content')

    <div class="row">
        <!-- FormValidation -->
        <div class="col-12">
            <div class="card">
                <h5 class="card-header">User Registration</h5>
                <div class="card-body">
                    <form id="formAuthentication" method="POST" action="{{ route('custom.register.submit') }}" class="row">
                        @csrf

                        <div class="col-md-3 mb-3">
                            <label for="name" class=" col-form-label text-md-end required">{{ __('Name') }}</label>

                                <input  type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="email" class=" col-form-label text-md-end required">{{ __('Email Address') }}</label>

                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="password" class=" col-form-label text-md-end required">{{ __('Password') }}</label>

                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                <button class="btn bg-transparent border-0 toggle-password" type="button" data-target="password">
                                    <i class="bi bi-eye-slash"></i>
                                </button>

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="password-confirm" class="col-form-label text-md-end required">{{ __('Confirm Password') }}</label>

                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                <button class="btn bg-transparent border-0 toggle-password" type="button" data-target="password_confirmation">
                                    <i class="bi bi-eye-slash"></i>
                                </button>
                            
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="role" class="col-form-label text-md-end required">Select Role</label>
                            <select name="role" class="form-select">
                                <option value="">Select</option>
                                <option value="Admin">Admin</option>
                                <option value="Help Desk">Help Desk</option>
                                <option value="Fulfillment Desk">Fulfillment Desk</option>
                                <option value="Data Entry Operator">Data Entry Operator</option>
                                <option value="Accounts">Accounts</option>
                                 <option value="Vendors">Vendors</option>
                                <!--<option value="Franchise Team Member">Franchise Team Member</option> -->
                            </select>
                        </div>
                        
                        <div class="col-md-2 mt-9">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /FormValidation -->
    </div>
@endsection
@section('script')
<script>
    let formAuthentication = document.querySelector("#formAuthentication");

    document.addEventListener("DOMContentLoaded", function(e) {
        if (formAuthentication) {
            // Initialize form validation
            FormValidation.formValidation(formAuthentication, {
                fields: {
                    'name': {
                        validators: {
                            notEmpty: { message: "Please enter your name" },
                            stringLength: { min: 3, message: "Name must be at least 3 characters long" }
                        }
                    },
                    'email': {
                        validators: {
                            notEmpty: { message: "Please enter your email" },
                            emailAddress: { message: "Please enter a valid email address" }
                        }
                    },
                    'password': {
                        validators: {
                            notEmpty: { message: "Please enter your password" },
                            stringLength: { min: 8, message: "Password must be at least 8 characters long" },
                            regexp: {
                                regexp: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])/,
                                message: "Password must include uppercase, lowercase, number, and special character"
                            }
                        }
                    },
                    'password_confirmation': {
                        validators: {
                            notEmpty: { message: "Please confirm your password" },
                            identical: {
                                compare: function() {
                                    return formAuthentication.querySelector('[name="password"]').value;
                                },
                                message: "The password and its confirmation do not match"
                            }
                        }
                    },
                    'role': {
                        validators: {
                            notEmpty: { message: "Please select a role" }
                        }
                    }
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap5: new FormValidation.plugins.Bootstrap5({
                        eleValidClass: "",
                        rowSelector: ".mb-3"
                    }),
                    submitButton: new FormValidation.plugins.SubmitButton(),
                    defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                    autoFocus: new FormValidation.plugins.AutoFocus()
                },
                init: e => {
                    e.on("plugins.message.placed", function(e) {
                        if (e.element.parentElement.classList.contains("input-group")) {
                            e.element.parentElement.insertAdjacentElement("afterend", e.messageElement);
                        }
                    });
                }
            });
        }
    });
</script>

@endsection
