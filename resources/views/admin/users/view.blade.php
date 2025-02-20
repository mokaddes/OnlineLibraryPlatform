@extends('admin.layouts.master')
@section('user', 'active')
@section('title')
    {{ $data['title'] ?? '' }}
@endsection
@push('style')
    <style>
        input, select, textarea {
            border-radius: 10px !important;
        }
    </style>
@endpush
@section('content')
    <div class="content-wrapper mt-4">
        <div class="content">
            <div class="container-fluid">
                <div class="row mt-5">
                    <div class="col-md-6 offset-md-3">
                        <div class="card card-outline">
                            <div class="card-body box-profile">
                                <div class="text-center">
                                    <img class="profile-user-img img-fluid img-circle"
                                         src="{{ asset($data['row']->image ?? 'assets/images/default-user.png') }}"
                                         style="width:100px; height:100px; display:block;" alt="">
                                </div>
                                <h3 class="profile-username text-center">{{ $data['row']->name }} <sub
                                        style="font-size: 12px !important;">( {{ $data['role'] == 'User' ? 'Reader' : $data['role'] }}
                                        )</sub></h3>
                                <h6 class="text-center">@if($data['role'] == 'Author')
                                        Total Books: {{ $data['row']->products_count }}
                                    @elseif($data['role'] == 'User')
                                        Total Borrowed Books: {{ $data['row']->borrowed_count }}
                                    @else
                                        Total Books: {{ $data['row']->products_count }}
                                    @endif</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6 offset-md-3">
                        <div class="card w-100">
                            <ul class="list-group list-group-flush">
                                @if($data['role'] == 'Author' || $data['role'] == 'User')
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                        <h6>First Name</h6>
                                        <span class="text-secondary">{{ $data['row']->name ?? 'N/A' }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                        <h6>Last Name</h6>
                                        <span class="text-secondary">{{ $data['row']->last_name ?? 'N/A' }}</span>
                                    </li>
                                @endif

                                @if($data['role'] == 'Institution')
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                        <h6>Institution Name</h6>
                                        <span class="text-secondary">{{ $data['row']->name ?? 'N/A' }}</span>
                                    </li>
                                @endif

                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <h6>Email</h6>
                                    <span class="text-secondary">{{ $data['row']->email ?? 'N/A' }}</span>
                                </li>

                                @if($data['role'] == 'Author' || $data['role'] == 'User')
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                        <h6>Phone</h6>
                                        <span
                                            class="text-secondary">{{ $data['row']->dial_code . $data['row']->phone }}</span>
                                    </li>
                                @endif
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <h6>Country</h6>
                                    <span
                                        class="text-secondary">{{  $data['row']->country ?? 'N/A' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <h6>Age</h6>
                                    <span
                                        class="text-secondary">{{  $data['row']->age ?? 'N/A' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <h6>Gender</h6>
                                    <span
                                        class="text-secondary">{{  $data['row']->gender ?? 'N/A' }}</span>
                                </li>
                                @if($data['role'] == 'User')
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                        <h6>Subscription</h6>
                                        <span
                                            class="text-secondary">{{ $user->currentUserPlan->package->title ?? 'N/A' }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                        <h6>Subscription Expired</h6>
                                        <span
                                            class="text-secondary">{{ isset($user->currentUserPlan->created_at) ? date('d, M Y', strtotime($user->currentUserPlan->expired_date)) : 'N/A' }}</span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <a href="{{ route('admin.user.edit', ['id' => $data['row']->id, 'role' => $data['role'] ]) }}"
                       class="btn btn-primary" style="border: 1px solid #F1F1F1">
                        Edit
                    </a>
                    <a href="{{ route('admin.user.index') }}" class="btn btn-info"
                       style="border: 1px solid #F1F1F1">Back
                    </a>
                </div>

                {{-- <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h3 class="card-title"> @if($data['role'] == 'Author' or $data['role'] == 'User') User
                                        @else Institution  @endif Profile
                                    </h3>
                                </div>
                                <div>
                                    <a href="{{ route('admin.user.index') }}" class="btn btn-sm btn-light"
                                        style="border: 1px solid #F1F1F1">Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @if($data['role'] == 'Author' or $data['role'] == 'User')
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="form-label">First Name</label>
                                            <input type="text" name="name" id="name" value="{{ $data['row']->name ?? 'N/A' }}" class="form-control" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="last_name" class="form-label">Last Name</label>
                                            <input type="text" name="last_name" id="last_name" value="{{ $data['row']->last_name ?? 'N/A' }}" class="form-control" disabled>
                                        </div>
                                    </div>
                                @endif

                                @if($data['role'] == 'Institution')
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="form-label">Institution Name <span class="text-danger">*</span></label>
                                            <input type="text" name="last_name" id="last_name" value="{{ $data['row']->name ?? 'N/A' }}" class="form-control" disabled>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="text" name="email" id="email" value="{{ $data['row']->email ?? 'N/A' }}" class="form-control" disabled>
                                    </div>
                                </div>

                                @if($data['role'] == 'Author' or $data['role'] == 'User')
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone" class="form-label">Phone</label>
                                            <input type="text" name="phone" id="phone" value="{{ $data['row']->dial_code . $data['row']->phone }}" class="form-control" disabled>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
@endsection

