@extends('admin.layouts.master')
@section('admin-institute', 'active')
@section('title') {{ $data['title'] ?? '' }} @endsection
@push('style')
<style>
    input, select, textarea {
    border-radius: 10px !important;
}
</style>
@endpush
@section('content')
<div class="content-wrapper mt-4" >
    <div class="content">
        <div class="container-fluid">
            <div class="row mt-5">
                <div class="col-md-4 offset-md-4">
                    <div class="card card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid img-circle"
                                    src="{{ asset($data['row']->image ?? 'assets/images/default-user.png') }}"
                                    style="width:100px; height:100px; display:block;" alt="">
                            </div>
                            <h3 class="profile-username text-center">{{ $data['row']->name }} <sub style="font-size: 12px !important;">( Institute )</sub> </h3>
                            <h6 class="text-center">Total Books: {{ $product_count }} </h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-4 offset-md-4">
                    <div class="card w-100">
                        <ul class="list-group list-group-flush">
                            @if($data['role'] == 'Author' or $data['role'] == 'User')
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

                            @if($data['role'] == 'Author' or $data['role'] == 'User')
                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                <h6>Phone</h6>
                                <span class="text-secondary">{{ $data['row']->dial_code . $data['row']->phone }}</span>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <a href="{{ route('admin.institute.edit', ['id' => $data['row']->id ]) }}"
                    class="btn btn-primary" style="border: 1px solid #F1F1F1">
                    Edit
                </a>
                <a href="{{ route('admin.institute.index') }}" class="btn btn-info"
                    style="border: 1px solid #F1F1F1">Back
                </a>
            </div>

        </div>
    </div>
</div>
@endsection

