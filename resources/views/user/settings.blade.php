@extends('admin.layouts.user')
@section('settings', 'active')
@section('title') {{ $title ?? '' }} @endsection
<?php
$_tab = request()->get('tab');
?>
@push('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">
    <style>
        input,
        select,
        textarea {
            border-radius: 10px !important;
        }

        .iti input,
        .iti input[type=text],
        .iti input[type=tel] {
            padding-left: 97px !important;
        }

        .iti {
            display: block !important;
        }

        .nav .nav-link {
            background: #e1b9e11c;
            position: relative !important;
            padding: 10px 60px;
            font-weight: 500;
            font-size: 15px;
            border-radius: 2px !important;
            margin-bottom: 6px;
            color: #333;
        }

        .bg-col {
            background: #ff00000f;
        }

        .custom_form form-control {
            border-radius: 8px;
            background: #FFF;
            padding: 10px 10px;
            outline: none !important;
            box-shadow: none !important;
            border: 1px solid #CCC;
            width: 100%;
        }
    </style>
@endpush
@section('content')
    <div class="content-wrapper mt-3 p-3">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4 col-xl-3">
                        <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist"
                            aria-orientation="vertical">
                            @if (auth()->user()->role_id != 3 && auth()->user()->role_id != 2)
                                <!-- My Subscription -->
                                <a class="nav-link @if (!empty($_tab)) {{ $_tab == 1 ? 'active' : '' }} @else active @endif"
                                    id="vert-tabs-subscription-tab" data-toggle="pill" href="#vert-tabs-subscription"
                                    role="tab" aria-controls="vert-tabs-subscription" aria-selected="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="bevel">
                                        <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                        <path d="M7 15h0M2 9.5h20"></path>
                                    </svg>
                                    My Subscription
                                </a>
                            @endif
                            <!-- account settings -->
                            <a class="nav-link {{ $_tab == 2 ? 'active' : '' }}" id="vert-tabs-home-tab" data-toggle="pill"
                                href="#vert-tabs-home" role="tab" aria-controls="vert-tabs-home" aria-selected="true">
                                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="bevel">
                                    <circle cx="12" cy="12" r="3"></circle>
                                    <path
                                        d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
                                    </path>
                                </svg>
                                Account Settings
                            </a>
                            <!-- logout -->
                            <a class="nav-link" href="{{ route('logout') }}" style="margin-left: 5px;"
                                onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                                title="Logout">
                                <i class="fa-solid fa-right-from-bracket"></i>
                                {{ __('Logout') }}
                            </a>
                            <form class="logout" id="logout-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-8 col-xl-9">
                        <div class="tab-content" id="vert-tabs-tabContent">
                            @if (auth()->user()->role_id != 3 && auth()->user()->role_id != 2)
                            <!-- my subscription -->
                            <div class="tab-pane fade @if (!empty($_tab)) {{ $_tab == 1 ? 'show active' : '' }} @else show active @endif"
                                id="vert-tabs-subscription" role="tabpanel" aria-labelledby="vert-tabs-subscription-tab">
                                <div class="setting_tab_contetn">
                                    <div class="col-md-8 offset-md-2 mb-4">
                                        <div class="card bg-light p-0">
                                            <div class="card-header">
                                                <h4 class="card-title">
                                                    @if($user->currentUserPlan && $user->currentUserPlan->expired_date > now())
                                                        <span>{{ $user->currentUserPlan->package->title }}</span>
                                                    @else
                                                        {{ $user->package->title ?? 'Free' }}
                                                    @endif
                                                </h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <a class="btn btn-sm" id="custom_btn"
                                                        href="{{ route('frontend.pricing') }}">Upgrade Now</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-8 offset-md-2 mb-4">
                                            <div class="card bg-light">
                                                <div class="card-header justify-content-between align-items-center">
                                                    <h4 class="card-title">
                                                        Billing Information
                                                    </h4>
                                                    <div>
                                                        <a href="#" data-toggle="modal" data-target="#billingModal"
                                                            class="float-right">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="15"
                                                                height="15" viewBox="0 0 24 24" fill="none"
                                                                stroke="#000000" stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                                <path
                                                                    d="M20 14.66V20a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h5.34">
                                                                </path>
                                                                <polygon points="18 2 22 6 12 16 8 16 8 12 18 2">
                                                                </polygon>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <table class="table">
                                                        <tbody>
                                                            <tr>
                                                                <td>Name</td>
                                                                <td>{{ $user->billing_name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Email</td>
                                                                <td>{{ $user->billing_email }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>phone</td>
                                                                <td>{{ $user->billing_phone }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>User-Type</td>
                                                                <td>{{ $user->type }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Address</td>
                                                                <td>{{ $user->address }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Country</td>
                                                                <td>{{ $user->country }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>State</td>
                                                                <td>{{ $user->state }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>City</td>
                                                                <td>{{ $user->city }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Zip code</td>
                                                                <td>{{ $user->zipcode }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <!-- account settings -->
                            <div class="tab-pane text-left fade {{ $_tab == 2 ? 'show active' : '' }}" id="vert-tabs-home"
                                role="tabpanel" aria-labelledby="vert-tabs-home-tab">
                                <div class="setting_tab_contetn">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Account Settings</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12 d-flex justify-content-center mb-4">
                                                    <img src="{{ getProfile($user->image) }}"
                                                        class="img-fluid img-thumbnail rounded-circle"
                                                        alt="{{ $user->name }}" style=" width: 220px; height: 220px; ">
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="setting_form">
                                                        <form action="{{ route('user.profile.update', $user->id) }}"
                                                            method="post" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="row">
                                                                <div class="col-md-4 form-group mb-3">
                                                                    <label for="image" class="form-label">Image</label>
                                                                    <input type="file" name="image" id="image"
                                                                        class="form-control" placeholder="Image">
                                                                </div>
                                                                <div class="col-md-4 form-group mb-3">
                                                                    <label for="name" class="form-label">First Name
                                                                        <span class="text-danger">*</span></label>
                                                                    <input type="text" name="name"
                                                                        value="{{ $user->name }}" id="name"
                                                                        autofocus required
                                                                        class="custom_form form-control @error('name') is-invalid @enderror"
                                                                        placeholder="Enter your first name">
                                                                    @error('name')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-4 form-group mb-3">
                                                                    <label for="last_name" class="form-label">Last
                                                                        Name</label>
                                                                    <input type="text" name="last_name"
                                                                        value="{{ $user->last_name }}" id="last_name"
                                                                        class="custom_form form-control @error('last_name') is-invalid @enderror"
                                                                        placeholder="Enter your last name">
                                                                    @error('last_name')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                                <div
                                                                    class="@if (auth()->user()->role_id == 3) col-md-12 @else col-md-6 @endif form-group mb-3">
                                                                    <label for="email" class="form-label">Email <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="text" name="email"
                                                                        value="{{ $user->email }}" id="email"
                                                                        required
                                                                        class="custom_form form-control @error('email') is-invalid @enderror"
                                                                        placeholder="Enter your last name">
                                                                    @error('email')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                                @if (auth()->user()->role_id != 3)
                                                                    <div class="col-md-6">
                                                                        <div class="form-group mb-3">
                                                                            <label for="phone" class="form-label">Phone Number</label>
                                                                            <input type="tel" name="phone"
                                                                                value="{{ $user->dial_code . $user->phone }}"
                                                                                id="phone"
                                                                                class="custom_form form-control @error('phone') is-invalid @enderror"
                                                                                placeholder="Enter your phone number">
                                                                            @error('phone')
                                                                                <span class="invalid-feedback" role="alert">
                                                                                    <strong>{{ $message }}</strong>
                                                                                </span>
                                                                            @enderror
                                                                            <input type="hidden" id="dialCode" name="dial_code">
                                                                            <input type="hidden" id="country_name" name="country">
                                                                            <input type="hidden" id="country_code" name="country_code">
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="row mt-4">
                                                                <div class="float-right setting_btn">
                                                                    <button type="submit" id="custom_btn"
                                                                        class="btn btn-sm mr-3">
                                                                        <span class="btn-txt">Save</span>
                                                                    </button>
                                                                    <a href="javascript:void(0)"
                                                                        class="btn btn-sm btn-primary mr-3"
                                                                        data-toggle="modal"
                                                                        data-target="#reset_password">Change Password</a>
                                                                    <a href="javascript:void(0)"
                                                                        class="btn btn-sm btn-danger" data-toggle="modal"
                                                                        data-target="#deleteAccount">Delete Account</a>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="delete_modal">
        <div class="modal fade" id="deleteAccount" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-zoom  modal-dialog-centered">
                <div class="modal-content">
                    <!-- modal header -->
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Confirm Account Deletion</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <form action="{{ route('user.account.delete', $user->id) }}" method="POST"
                        id="accountDeletionForm">
                        @csrf
                        <!-- modal body -->
                        <div class="modal-body">
                            <p class="text-danger"> <strong>If you DELETE this account all data will be lost permanetly
                                    from our website!</strong> </p>
                            <div class="form-group">
                                <label for="delete" class="form-label">Enter "Delete" to confirm <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="delete" id="delete"
                                    class="form-control @error('delete') is-invalid @enderror" required>
                            </div>
                        </div>
                        <!-- modal footer -->
                        <div class="modal-footer pb-3">
                            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-sm" id="custom_btn">
                                <i class="loading-spinner fa-lg fas"></i>
                                <span class="btn-txt">Delete Account</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="reset_password_modal">
        <div class="modal fade" id="reset_password" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-zoom  modal-dialog-centered">
                <div class="modal-content">
                    <!-- modal header -->
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <form action="{{ route('user.password.update', $user->id) }}" method="post" id="passwordForm">
                        @csrf
                        <!-- modal body -->
                        <div class="modal-body">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="password" class="form-label">New Password <span
                                            class="text-danger">*</span></label>
                                    <input type="password" name="password" id="password"
                                        class="custom_form form-control
                                    @error('password') is-invalid @enderror"
                                        required placeholder="New Password">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="password_confirmation" class="form-label">Re enter New Password <span
                                            class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="custom_form form-control @error('password_confirmation') is-invalid @enderror"
                                        required placeholder="Re enter New Password">
                                </div>
                            </div>
                        </div>
                        <!-- modal footer -->
                        <div class="modal-footer pb-3">
                            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-sm" id="custom_btn">
                                <i class="loading-spinner fa-lg fas"></i>
                                <span class="btn-txt">Save</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="billing_modal">
        <div class="modal fade" id="billingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-zoom  modal-dialog-centered">
                <div class="modal-content">
                    <!-- modal header -->
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Billing details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('user.billing.update', $user->id) }}" method="post" id="billingForm">
                            @csrf
                            <div class="mb-3">
                                <label for="billing_name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="billing_name" name="billing_name" required
                                    value="{{ !empty($user->billing_name) ? $user->billing_name : $user->name . ' ' . $user->last_name }}">
                            </div>
                            <div class="mb-3">
                                <label for="billing_email" class="form-label">Email</label>
                                <input type="email" class="form-control" value="{{ !empty($user->billing_email) ? $user->billing_email : $user->email }}"
                                    id="billing_email" name="billing_email" required>
                            </div>
                            <div class="mb-3">
                                <label for="billing_phone" class="form-label">phone</label>
                                <input type="tel" class="form-control" value="{{ !empty($user->billing_phone) ? $user->billing_phone : $user->phone }}"
                                    id="billing_phone" name="billing_phone" required>
                            </div>
                            <div class="mb-3">
                                <label for="type" class="form-label">User Type</label>
                                <select class="form-control form-select" id="type" name="type" required>
                                    <option value="Personal" @if ($user->type == 'Personal') selected @endif>Personal
                                    </option>
                                    <option value="Business" @if ($user->type == 'Business') selected @endif>Business
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" value="{{ $user->address }}" id="address"
                                name="address" required>
                            </div>
                            <div class="mb-3">
                                <label for="country" class="form-label">Country / Region</label>
                                <select class="form-select" id="country" name="country" required>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="state" class="form-label">State</label>
                                <input type="text" class="form-control" value="{{ $user->state }}" id="state"
                                    name="state" required>
                            </div>
                            <div class="mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control" value="{{ $user->city }}" id="city"
                                    name="city" required>
                            </div>
                            <div class="mb-3">
                                <label for="zipcode" class="form-label">Zip Code</label>
                                <input type="text" class="form-control" value="{{ $user->zipcode }}" id="zipcode"
                                    name="zipcode" required>
                            </div>
                            <div class="modal-footer pb-3">
                                <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-sm" id="custom_btn">
                                    <i class="loading-spinner fa-lg fas"></i>
                                    <span class="btn-txt">Save billing details</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
@push('script')
    @include('frontend.phone_number_script')

    <script>
        $("#updateProfile").submit(function() {
            $(this).find(":submit").children(".loading-spinner").toggleClass('fa-spinner fa-spin');
            $(this).find(":submit").attr("disabled", true);
            $(this).find(":submit").children(".btn-txt").text("Processing");
            $("*").css("cursor", "wait");
        });
        $(document).on('submit', "#billingForm", function(e) {
            e.preventDefault();
            var form = $("#billingForm");
            var _this = $(this).find(":submit");
            console.log(form.attr('action'));
            $.ajax({
                type: 'post',
                data: form.serialize(),
                url: form.attr('action'),
                async: true,
                dataType: 'json',
                beforeSend: function() {
                    $("body").css("cursor", "progress");
                    $(_this).children(".loading-spinner").toggleClass('fa-spinner fa-spin');
                    $(_this).attr("disabled", true);
                    $(_this).children(".btn-txt").text("Processing");
                },
                success: function(response) {
                    if (response.status == 1) {
                        toastr.success(response.message);
                        $('#billingModal').modal('hide');
                        window.location.href = "{{ route('user.settings.index', ['tab' => 1]) }}";
                    } else {
                        toastr.error(response.message);
                    }
                    $(_this).attr("disabled", false);
                    $(_this).children(".loading-spinner").removeClass('fa-spinner fa-spin');
                    $(_this).children(".btn-txt").text("Save");
                },
                error: function(jqXHR, exception) {
                    toastr.error('An error occurred while processing the Password Change!');
                    $(_this).attr("disabled", false);
                    $(_this).children(".loading-spinner").removeClass('fa-spinner fa-spin');
                    $(_this).children(".btn-txt").text("Save");
                },
                complete: function(response) {
                    $("body").css("cursor", "default");
                }
            });
        });
        $(document).on('submit', "#accountDeletionForm", function(e) {
            e.preventDefault();
            var form = $("#accountDeletionForm");
            var _this = $(this).find(":submit");
            console.log(form.attr('action'));
            $.ajax({
                type: 'post',
                data: form.serialize(),
                url: form.attr('action'),
                async: true,
                beforeSend: function() {
                    $("body").css("cursor", "progress");
                    $(_this).children(".loading-spinner").toggleClass('fa-spinner fa-spin');
                    $(_this).attr("disabled", true);
                    $(_this).children(".btn-txt").text("Processing");
                },
                success: function(response) {
                    if (response.status == 1) {
                        toastr.success(response.message);
                        $('#deleteAccount').modal('hide');
                        location.reload();
                    } else {
                        toastr.error(response.message);
                    }
                    $(_this).attr("disabled", false);
                    $(_this).children(".loading-spinner").removeClass('fa-spinner fa-spin');
                    $(_this).children(".btn-txt").text("Delete Account");
                },
                error: function(jqXHR, exception) {
                    toastr.error('An error occurred while processing the Account Delete!');
                    $(_this).attr("disabled", false);
                    $(_this).children(".loading-spinner").removeClass('fa-spinner fa-spin');
                    $(_this).children(".btn-txt").text("Delete Account");
                },
                complete: function(response) {
                    $("body").css("cursor", "default");
                }
            });
        });
        $(document).on('submit', "#passwordForm", function(e) {
            e.preventDefault();
            var form = $("#passwordForm");
            var _this = $(this).find(":submit");
            console.log(form.attr('action'));
            $.ajax({
                type: 'post',
                data: form.serialize(),
                url: form.attr('action'),
                async: true,
                dataType: 'json',
                beforeSend: function() {
                    $("body").css("cursor", "progress");
                    $(_this).children(".loading-spinner").toggleClass('fa-spinner fa-spin');
                    $(_this).attr("disabled", true);
                    $(_this).children(".btn-txt").text("Processing");
                },
                success: function(response) {
                    if (response.status == 1) {
                        toastr.success(response.message);
                        $('#reset_password').modal('hide');
                        window.location.href = "{{ route('user.settings.index', ['tab' => 2]) }}";
                    } else {
                        toastr.error(response.message);
                    }
                    $(_this).attr("disabled", false);
                    $(_this).children(".loading-spinner").removeClass('fa-spinner fa-spin');
                    $(_this).children(".btn-txt").text("Save");
                },
                error: function(jqXHR, exception) {
                    toastr.error('An error occurred while processing the Password Change!');
                    $(_this).attr("disabled", false);
                    $(_this).children(".loading-spinner").removeClass('fa-spinner fa-spin');
                    $(_this).children(".btn-txt").text("Save");
                },
                complete: function(response) {
                    $("body").css("cursor", "default");
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            var defaultCountry = "{{ $user->country ?? '' }}";
            $('#country').select2({
                placeholder: 'Select a Country / Region',
                allowClear: true,
                ajax: {
                    url: 'https://restcountries.com/v2/all',
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    id: item.name,
                                    text: item.name
                                };
                            })
                        };
                    }
                },
                data: defaultCountry ? [{
                    id: defaultCountry,
                    text: defaultCountry
                }] : null
            });
        });
    </script>
@endpush
