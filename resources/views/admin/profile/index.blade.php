@extends('admin.layouts.master')
@section('title')
    {{ $data['title'] ?? '' }}
@endsection
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Profile</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Profile</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card card-outline">
                            <div class="card-body box-profile">
                                <div class="text-center">
                                    <img class="profile-user-img img-fluid img-circle"
                                        src="{{ asset(Auth::user()->image ?? 'assets/default.png') }}"
                                        style="width:100px; height:100px; display:block;" alt="">
                                </div>
                                <h3 class="profile-username text-center">{{ Auth::user()->name }}</h3>
                                <p class="text-muted text-center">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Update Profile</h3>
                            </div>
                            <div class="card-body">
                                <form action="#" method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="image" class="form-label">Profile Photo</label>
                                                <input type="file" name="image" id="image" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name" class="form-label">Name</label>
                                                <input type="text" name="name" id="name" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="text" name="email" id="email" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Change Password</h3>
                            </div>
                            <div class="card-body">
                                <form action="#" method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label for="old-password" class="form-label">Old Password <span class="text-danger">*</span></label>
                                                <input type="password" name="old-password" id="old-password" required
                                                    class="form-control" placeholder="Enter your old password">
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label for="new-password" class="form-label">New Password <span class="text-danger">*</span></label>
                                                <input type="password" name="new-password" id="new-password" required
                                                    class="form-control" placeholder="Enter your new password">
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label for="confirm-password" class="form-label">Confirm
                                                    Password <span class="text-danger">*</span></label>
                                                <input type="password" name="confirm-password" id="confirm-password" required
                                                    class="form-control" placeholder="Confirm your new password">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
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
@endsection
