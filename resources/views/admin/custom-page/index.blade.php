@extends('admin.layouts.master')
@section('title') {{ $data['title'] ?? '' }} @endsection
@section('cpage', 'active')
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
                                        <h3 class="card-title">Manage {{ $data['title'] ?? '' }}</h3>
                                    </div>
                                    <div class="col-6">
                                        <div class="float-right">
                                            @if (Auth::user()->can('admin.cpage.index'))
                                                <a href="{{ route('admin.cpage.create') }}" class="btn btn-primary">Add
                                                    New</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body table-responsive p-0">
                                <table id="dataTables" class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Sl</th>
                                            <th>Page Name</th>
                                            <th>Published Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rows as $key => $row)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $row->title }}</td>
                                                <td>
                                                    @if ($row->is_active == 1)
                                                        <span class="badge badge-success">Published</span>
                                                    @else
                                                        <span class="badge badge-danger">Unpublished</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (Auth::user()->can('admin.cpage.view'))
                                                        <a href="{{ route('admin.cpage.view', $row->id) }}"
                                                            class="btn btn-xs btn-primary btn-xs">View</a>
                                                    @endif

                                                    @if (Auth::user()->can('admin.cpage.edit'))
                                                        <a href="{{ route('admin.cpage.edit', $row->id) }}"
                                                            class="btn btn-xs btn-secondary btn-xs">Edit</a>
                                                    @endif

                                                    @if (Auth::user()->can('admin.cpage.delete'))
                                                        <a href="{{ route('admin.cpage.delete', $row->id) }}"
                                                            id="deleteData" class="btn btn-xs btn-danger btn-xs">Delete</a>
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
