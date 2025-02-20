@extends('admin.layouts.master')

@section('customer', 'active')
@section('title') {{ $data['title'] ?? '' }} @endsection

@push('style')
@endpush
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
                                            @if (Auth::user()->can('admin.customer.create'))
                                                <a href="{{ route('admin.customer.create') }}" class="btn btn-primary">Add
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
                                            <th width="5%">SL</th>
                                            <th width="25%">Name</th>
                                            <th width="15%">Email</th>
                                            <th width="15%">Status</th>
                                            <th width="15%">Register At</th>
                                            <th width="15%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (isset($data['rows']) && count($data['rows']) > 0)
                                            @foreach ($data['rows'] as $key => $row)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $row->name }}</td>
                                                    <td>{{ $row->email }}</td>
                                                    <td>
                                                        @if ($row->status == 1)
                                                            <span class="badge badge-success">Published</span>
                                                        @else
                                                            <span class="badge badge-danger">Unpublished</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $row->created_at }}</td>
                                                    <td>
                                                        @if (Auth::user()->can('admin.customer.view'))
                                                            <a href="{{ route('admin.customer.view', $row->id) }}"
                                                                class="btn btn-primary view btn-xs">View</a>
                                                        @endif

                                                        @if (Auth::user()->can('admin.customer.edit'))
                                                            <a href="{{ route('admin.customer.edit', $row->id) }}"
                                                                class="btn btn-secondary edit btn-xs">Edit</a>
                                                        @endif

                                                        @if (Auth::user()->can('admin.customer.delete'))
                                                            <a href="{{ route('admin.customer.delete', $row->id) }}"
                                                                id="deleteData" class="btn btn-danger btn-xs">Delete</a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
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
