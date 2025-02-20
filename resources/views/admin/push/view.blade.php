@extends('admin.layouts.master')
@section('push', 'active')
@section('title')
    {{ $title ?? '' }}
@endsection

@push('style')
    <style>
        input, select, textarea {
            border-radius: 10px !important;
        }

    </style>
@endpush
@section('content')
    <div class="content-wrapper mt-3">
        <div class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="card-title">View Package</h3>
                            </div>
                            <div>
                                <a href="{{ route('admin.package.index') }}" class="btn btn-sm btn-light"
                                   style="border: 1px solid #F1F1F1">Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mt-4">
                        <div class="pricing_table table-responsive">
                            <table class="table table-striped text-successtable-border border-light custom_table">
                                <thead class="border-light">
                                <tr>
                                    <th style="width: 25%" class="title" scope="col">Title</th>
                                    <th style="width: 25%" class="title" scope="col">{{ $push->title }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th scope="row">Body</th>
                                    <td>
                                        {{ $push->body }}
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Action Url</th>
                                    <td>
                                        {{ $push->url }}
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Success Rate</th>
                                    <td>
                                        {{ $push->total_success/count($push->user_ids) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Total resend count</th>
                                    <td>
                                        {{ $push->total_send }}
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Status</th>
                                    <td>
                                        @if($push->status == 1)
                                            <span class="text-success"> &#9679; Active</span>
                                        @else
                                            <span class="text-danger"> &#9679; Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Assigned Users</th>
                                    <td>
                                        @forelse($users as $user)
                                            <span class="badge bg-success">{{ $user->name }} {{ $user->last_name }} ({{ $types[$user->role_id] ?? '' }})</span>
                                        @empty
                                            No users
                                        @endforelse
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
