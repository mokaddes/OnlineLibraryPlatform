@extends('admin.layouts.master')
@section('admin-permissions', 'active')
@section('admin_management', 'active menu-open')
@section('title') Admin| permissions @endsection

@push('style')
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="m-0">{{ __('Admin permissions list') }}
                                    <span class="float-right">
                                    <a href="{{ route('admin.permissions.create') }}" class="btn btn-sm btn-light"
                                        style="border: 1px solid #F1F1F1">Add new</a>
                                    </span>
                                </h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th scope="col" width="15%">Name</th>
                                        <th scope="col">Guard</th>
                                        <th scope="col" colspan="3" width="10%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($permissions as $permission)
                                            <tr>
                                                <td>{{ $permission->name }}</td>
                                                <td>{{ $permission->guard_name }}</td>


                                                <td>
                                                    <a href="{{ route('admin.permissions.edit', $permission->id) }}" class="btn btn-info btn-xs">Edit</a>
                                                    {!! Form::open(['method' => 'POST','route' => ['admin.permissions.destroy', $permission->id],'style'=>'display:inline']) !!}
                                                    {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-xs']) !!}
                                                    {!! Form::close() !!}
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
