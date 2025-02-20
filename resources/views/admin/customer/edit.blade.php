@extends('admin.layouts.master')

@section('customer', 'active')
@section('title') {{ $data['title'] ?? '' }} @endsection

@push('style')
<style>
        .custom-img {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 5px;
    width: 100px;
    height: 90px;
    }
</style>
@endpush
@php
     $user = $data['user'];
    $role = $data['role'];
@endphp
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $data['title'] ?? '' }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">{{ $data['title'] ?? '' }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <h3 class="card-title">Manage {{ $data['title'] ?? '' }} </h3>
                                </div>
                                <div class="col-6">
                                    <div class="float-right">
                                        @if (Auth::user()->can('admin.category.create'))
                                        <a href="{{ route('admin.customer.index') }}"  class="btn btn-primary">Back</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body table-responsive p-4">
                            <form action="{{ route('admin.customer.update',$user->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="image" class="form-lable">Customer Image</label>
                                            <input type="file" name="image" id="image" class="form-control" >
                                              <img class="custom-img mt-2" src="@if($user->image)
                                                {{ asset('assets/images/customer/'.$user->image) }}
                                                @else
                                                    {{ asset('assets/images/default-user.png') }}
                                              @endif" alt="Paris" width="60" height="80">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="name" class="form-lable">Customer Name<span class="text-danger">*</span></label>
                                            <input type="text" name="name" value="{{ $user->name }}" id="name" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="email" class="form-lable">Customer Email<span class="text-danger">*</span></label>
                                            <input type="email" name="email" value="{{ $user->email }}"  id="email" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="phone" class="form-lable">Customer Phone<span class="text-danger">*</span></label>
                                            <input type="text" name="phone" id="phone" value="{{ $user->phone }}"  class="form-control" required>
                                        </div>
                                    </div>
                                     <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="status" class="form-lable">Published Status<span class="text-danger">*</span></label>
                                            <select name="status" id="status" class="form-control" required>
                                                <option value="1" {{ $user->status == 1? "selected" : "" }}>Published</option>
                                                <option value="0" {{ $user->status == 0? "selected" : "" }}>Unpublished</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="form-group">
                                            <label for="address" class="form-lable">Customer Address<span class="text-danger">*</span></label>
                                            <input type="text" name="address" value="{{ $user->address }}" id="address" class="form-control" required>
                                        </div>
                                    </div>

                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="password" class="form-lable">Customer Password (If you want to change password)</label>
                                            <input type="password" name="password"  id="password" class="form-control">
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success">Update Customer</button>
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



