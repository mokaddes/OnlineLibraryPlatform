@extends('admin.layouts.user')
@section('blog', 'active')
@section('title') {{ $title ?? 'Manage Blogs' }} @endsection

@section('content')
    <div class="content-wrapper pb-5">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="mb-0">Blog</h1>
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
                                            <a href="{{ route('user.blog.create') }}" class="btn btn-sm" id="custom_btn">Add
                                                New</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body table-responsive p-0">
                                <table id="dataTables" class="table table-hover text-nowrap jsgrid-table">
                                    <thead>
                                        <tr>
                                            <th style="width:5%;">Serial No.</th>
                                            <th style="width:15%;">Image</th>
                                            <th style="width:45">Title</th>
                                            <th style="width:10%;">Status</th>
                                            <th style="width:10%;">Total Reply</th>
                                            <th style="width:12%;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (isset($data['rows']) && count($data['rows']) > 0)
                                            @foreach ($data['rows'] as $key => $row)
                                                <tr>
                                                    <td >{{ $key + 1 }}</td>
                                                    <td >
                                                        <img src="{{ asset($row->image ?? 'assets/default.png') }}" alt="{{ $row->title }}" width="50" height="50" style="border-radius: 20%;">
                                                    </td>
                                                    <td>
                                                        @if ($row->status == 1)
                                                            <a href="{{ route('frontend.blogs.details', ['slug' => $row->slug]) }}">{{ $row->title }}</a>
                                                        @else
                                                            {{ $row->title }}
                                                        @endif
                                                    </td>
                                                    <td class="{{ $row->status == 1 ? 'text-success' : 'text-danger' }}">
                                                        &#9679; {{ $row->status == 1 ? 'Active' : 'Inactive' }}
                                                    </td>
                                                    <td >{{ $row->comments_count }}</td>
                                                    <td >
                                                        <a href="{{ route('user.blog.edit', $row->id) }}" title="Edit"
                                                            class="btn btn-sm" style="background: #4D1DD4">
                                                            <i class="fas fa-pen" style="color: #ffffff;"></i>
                                                        </a>
                                                        <a href="{{ route('user.blog.delete', $row->id) }}" title="Delete"
                                                            onclick="return confirm('{{ __('Are you sure want to delete this item') }}')"
                                                            class="btn btn-sm" style="background: #EC2626">
                                                            <i class="fas fa-trash-alt" style="color: #ffffff;"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <td colspan="6" class="text-center">Data not found</td>
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
