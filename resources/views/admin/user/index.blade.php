@extends('admin.layouts.app')

@section('title', 'User List')

@section('content')
@php
    $user = Auth::user();
@endphp

<div class="dataOverviewSection mt-3">
    <div class="section-title">
        <h6 class="fw-bold m-0 custem">All Customer<span class="fw-normal text-muted">({{ count($users) }})</span></h6>
        <a href="#" class="primary-btn addBtn" data-bs-toggle="modal" data-bs-target="#addUserModal">+ Add Customer</a>
    </div>

    <!-- Add User Modal Start -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="formAuthentication" method="POST" action="{{ route('custom.register.submit') }}" autocomplete="off">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addUserModalLabel">Add User</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Input Fields for Name, Email, Password, etc. -->
                        <div class="mb-1 w-100">
                            <label for="name" class="form-label mb-1">Name</label>
                            <input type="text" class="form-control w-100" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name') <div class="form-text text-danger">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-1 w-100">
                            <label for="email" class="form-label mb-1">Email ID</label>
                            <input type="email" class="form-control w-100" id="email" name="email" value="{{ old('email') }}" required autocomplete="example@domain.com">
                            @error('email') <div class="form-text text-danger">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-1 w-100">
                            <label for="password" class="form-label mb-1">Password</label>
                            <input type="password" id="password" name="password" class="form-control w-100" required autocomplete="paswword_new">
                            <button class="btn bg-transparent border-0 toggle-password" type="button" data-target="password">
                                    <i class="bi bi-eye-slash"></i>
                                </button>
                            @error('password') <div class="form-text text-danger">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-1 w-100">
                            <label for="password_confirmation" class="form-label mb-1">Confirm Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control w-100" required>
                            <button class="btn bg-transparent border-0 toggle-password" type="button" data-target="password_confirmation">
                                    <i class="bi bi-eye-slash"></i>
                                </button>
                            @error('password_confirmation') <div class="form-text text-danger">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-1 w-100">
                            <label for="role" class="form-label mb-1">Select Role</label>
                            <select class="form-select form-select-lg w-100" id="role" name="role" required>
                                <option value="">Select</option>
                                <option value="Admin">Admin</option>
                                <option value="Vendor">Vendor</option>
                                <option value="Help Desk">Help Desk</option>
                                <option value="Fulfillment Desk">Fulfillment Desk</option>
                                <option value="Data Entry Operator">Data Entry Operator</option>
                                <option value="Accounts">Accounts</option>
                                @if ($user->hasAnyRole(['Super Admin', 'Admin']))
                                    
                                @else
                                @endif
                                
                                
                            </select>
                            @error('role') <div class="form-text text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="secondary-btn" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="primary-btn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add User Modal End -->

    <!-- Edit User Modal Start -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="editUserForm" method="POST" action="{{ route('user.update', ['user' => '__user_id__']) }}" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="editUserModalLabel">Edit User</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-1 w-100">
                            <label for="edit_name" class="form-label mb-1">Name</label>
                            <input type="text" class="form-control w-100" id="edit_name" name="name" required>
                            <div class="form-text text-danger" id="edit_name_error"></div> <!-- Validation message -->
                        </div>
                        <div class="mb-1 w-100">
                            <label for="edit_email" class="form-label mb-1">Email ID</label>
                            <input type="email" class="form-control w-100" id="edit_email" name="email" required>
                            <div class="form-text text-danger" id="edit_email_error"></div> <!-- Validation message -->
                        </div>
                        <div class="mb-1 w-100">
                            <label for="edit_role" class="form-label mb-1">Role</label>
                            <select class="form-select form-select-lg w-100" id="edit_role" name="role" required>
                                <option value="Admin">Admin</option>
                                <option value="Help Desk">Help Desk</option>
                                <option value="Fulfillment Desk">Fulfillment Desk</option>
                                <option value="Data Entry Operator">Data Entry Operator</option>
                                <option value="Accounts">Accounts</option>
                                <option value="Vendor">Vendor</option>
                                
                            </select>
                            <div class="form-text text-danger" id="edit_role_error"></div> <!-- Validation message -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="secondary-btn" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="primary-btn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit User Modal End -->

    <!-- Change Password Modal Start -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="changePasswordForm" method="POST" action="{{ route('user.change-password', ['user' => '__user_id__']) }}" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="changePasswordModalLabel">Change Password</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-1 w-100">
                            <label for="current_password" class="form-label mb-1">Current Password</label>
                            <div class="input-group w-100">
                                <input type="password" class="form-control border-0" id="current_password" name="current_password" required>
                                <button class="btn bg-transparent border-0 toggle-password" type="button" data-target="current_password">
                                    <i class="bi bi-eye-slash"></i>
                                </button>
                            </div>
                            <div class="form-text text-danger" id="current_password_error"></div> <!-- Validation message -->
                        </div>
                        <div class="mb-1 w-100">
                            <label for="new_password" class="form-label mb-1">New Password</label>
                            <div class="input-group w-100">
                                <input type="password" class="form-control border-0" id="new_password" name="new_password" required>
                                <button class="btn bg-transparent border-0 toggle-password" type="button" data-target="new_password">
                                    <i class="bi bi-eye-slash"></i>
                                </button>
                            </div>
                            <div class="form-text text-danger" id="new_password_error"></div> <!-- Validation message -->
                        </div>
                        <div class="mb-1 w-100">
                            <label for="new_password_confirmation" class="form-label mb-1">Confirm New Password</label>
                            <div class="input-group w-100">
                                <input type="password" class="form-control border-0" id="new_password_confirmation" name="new_password_confirmation" required>
                                <button class="btn bg-transparent border-0 toggle-password" type="button" data-target="new_password_confirmation">
                                    <i class="bi bi-eye-slash"></i>
                                </button>
                            </div>
                            <div class="form-text text-danger" id="new_password_confirmation_error"></div> <!-- Validation message -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="secondary-btn addBtn" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="primary-btn addBtn">Change Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Change Password Modal End -->


    <!-- Delete User Modal Start -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="deleteUserForm" method="POST" action="{{ route('user.delete', ['user' => '__user_id__']) }}" autocomplete="off">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="deleteUserModalLabel">Delete User</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this user?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="secondary-btn" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="primary-btn">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Delete User Modal End -->

    <div class="dataOverview mt-3">
        <div class="table-responsive">
            <table class="table" id="projectsTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->getRoleNames()[0] }}</td>
                    <td>
                        <div class="dropdown">
                            <i class="bi bi-three-dots-vertical" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false"></i>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item small editBtn" href="#" data-bs-toggle="modal" data-bs-target="#editUserModal"
                                    data-id="{{ $user->id }}" data-name="{{ $user->name }}" data-email="{{ $user->email }}" data-role="{{ $user->getRoleNames()[0] }}">Edit</a></li>
                                
                                <li><a class="dropdown-item small" href="#" data-id="{{ $user->id }}" data-bs-toggle="modal"
                                        data-bs-target="#changePasswordModal">Change Password</a>
                                </li>
                                
                                <li><a class="dropdown-item small deleteBtn" href="#" data-bs-toggle="modal" data-bs-target="#deleteUserModal" data-id="{{ $user->id }}">Delete</a></li>

                            </ul>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function () {
        // Edit User Modal
        $('#editUserModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); 
            var userId = button.data('id'); 
            var name = button.data('name'); 
            var email = button.data('email'); 
            var role = button.data('role'); 
            
            var modal = $(this);
            modal.find('#edit_name').val(name);
            modal.find('#edit_email').val(email);
            modal.find('#edit_role').val(role);

            var formAction = '{{ route('user.update', ['user' => '__user_id__']) }}'.replace('__user_id__', userId);
            modal.find('form').attr('action', formAction);
        });

        // Change Password Modal
        $('#changePasswordModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); 
            var userId = button.data('id'); 

            var modal = $(this);
            var formAction = '{{ route('user.change-password', ['user' => '__user_id__']) }}'.replace('__user_id__', userId);
            modal.find('form').attr('action', formAction);
        });

        // Delete User Modal
        $('#deleteUserModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); 
            var userId = button.data('id'); 

            var modal = $(this);
            var formAction = '{{ route('user.delete', ['user' => '__user_id__']) }}'.replace('__user_id__', userId);
            modal.find('form').attr('action', formAction);
        });

        // jQuery Validation for User Forms
        

        $("#changePasswordForm").validate({
            rules: {
                current_password: { required: true },
                new_password: { required: true, minlength: 6 },
                new_password_confirmation: { equalTo: "#new_password" }
            },
            messages: {
                current_password: "Please provide your current password",
                new_password: { required: "Please provide a new password", minlength: "Your password must be at least 6 characters long" },
                new_password_confirmation: { equalTo: "Please enter the same password again" }
            },
            errorElement: "div", // Use div to display errors
            errorPlacement: function (error, element) {
                error.addClass("form-text text-danger xsmall");
                error.insertAfter(element); // Place the error directly after the input element
            },
            highlight: function (element) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function (element) {
                $(element).removeClass("is-invalid").addClass("is-valid");
            }
        });
    });
</script>
<script>
    $(document).ready(function () {
        // Add custom validation method for regex pattern
        $.validator.addMethod("pattern", function (value, element, param) {
            if (this.optional(element)) {
                return true;
            }
            return param.test(value);
        }, "Invalid format.");

        // Initialize form validation
        $("#formAuthentication").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 3
                },
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 8,
                    pattern: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&]).+$/
                },
                password_confirmation: {
                    required: true,
                    equalTo: "#password"
                },
                role: {
                    required: true
                }
            },
            messages: {
                name: {
                    required: "Please enter your name",
                    minlength: "Name must be at least 3 characters long"
                },
                email: {
                    required: "Please enter your email",
                    email: "Please enter a valid email address"
                },
                password: {
                    required: "Please enter your password",
                    minlength: "Password must be at least 8 characters long",
                    pattern: "Password must include uppercase, lowercase, number, and special character"
                },
                password_confirmation: {
                    required: "Please confirm your password",
                    equalTo: "The password and its confirmation do not match"
                },
                role: {
                    required: "Please select a role"
                }
            },
            errorElement: "div", // Use div to display errors
            errorPlacement: function (error, element) {
                error.addClass("form-text text-danger xsmall");
                error.insertAfter(element); // Place the error directly after the input element
            },
            highlight: function (element) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function (element) {
                $(element).removeClass("is-invalid").addClass("is-valid");
            }
        });
    });
</script>
@endsection
