@extends('admin.layouts.master')
@section('faq', 'active')
@section('title'){{ $data['title'] ?? '' }} @endsection

@php
    $rows = $data['rows'];
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
                                        <h3 class="card-title">Manage Faq </h3>
                                    </div>
                                    <div class="col-6">
                                        <div class="float-right">
                                            @if (Auth::user()->can('admin.faq.create'))
                                                <a href="{{ route('admin.faq.create') }}" class="btn btn-primary">Add
                                                    New</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body table-responsive p-0">
                                <table id="dataTables" class="table table-hover text-nowrap jsgrid-table">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Question</th>
                                            <th>Answer</th>
                                            <th>Published Status</th>
                                            <th>Order Number</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rows as $key => $row)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $row->title }}</td>
                                                <td>{!! Str::limit($row->body, 50, '...') !!}</td>
                                                <td>
                                                    @if ($row->is_active == 1)
                                                        <span class="badge badge-success">Published</span>
                                                    @else
                                                        <span class="badge badge-danger">Unpublished</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $row->order_id }}
                                                </td>
                                                <td>
                                                    @if (Auth::user()->can('admin.faq.view'))
                                                        <a href="{{ route('admin.faq.view', $row->id) }}"
                                                            class="btn btn-xs btn-primary btn-xs">View</a>
                                                    @endif

                                                    @if (Auth::user()->can('admin.faq.edit'))
                                                        <a href="{{ route('admin.faq.edit', $row->id) }}"
                                                            class="btn btn-xs btn-secondary btn-xs">Edit</a>
                                                    @endif

                                                    @if (Auth::user()->can('admin.faq.delete'))
                                                        <a href="{{ route('admin.faq.delete', $row->id) }}" id="deleteData"
                                                            class="btn btn-xs btn-danger btn-xs">Delete</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection


@push('script')
@endpush
