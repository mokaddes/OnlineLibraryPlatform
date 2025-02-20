@extends('admin.layouts.master')
@section('admin-roles', 'active')
@section('admin_management', 'active menu-open')
@section('title') Admin| roles @endsection

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
                                <h5 class="m-0">{{ __('Admin roles list') }}
                                    <span class="float-right">
                                        <a href="{{ route('admin.admins.index') }}" class="btn btn-sm btn-light"
                                            style="border: 1px solid #F1F1F1">All Admins</a>
                                        <a href="{{ route('admin.roles.create') }}" class="btn btn-sm btn-light"
                                            style="border: 1px solid #F1F1F1">Create Role</a>
                                    </span>
                                </h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="1%">No</th>
                                        <th>Name</th>
                                        <th>Permission</th>
                                        <th width="10%" colspan="3">Action</th>
                                    </tr>
                                    @foreach ($roles as $key => $role)
                                        <tr>
                                            <td>{{ $role->id }}</td>
                                            <td>{{ $role->name }}</td>
                                            <td>
                                                <div>
                                                    @foreach ($role->permissions as $item)
                                                        <span
                                                            class="badge badge-primary permission">{{ __($item->name) }}</span>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td>
                                                {{-- <a class="btn btn-info btn-xs"
                                                    href="{{ route('admin.roles.show', $role->id) }}">Show</a> --}}
                                                <a class="btn btn-info btn-xs"
                                                    href="{{ route('admin.roles.edit', $role->id) }}">Edit</a>

                                                <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST"
                                                    class="d-inline">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button
                                                        onclick="return confirm('Are you sure you want to delete this item?');"
                                                        class="btn btn-danger btn-xs">Delete</button>
                                                </form>

                                            </td>
                                        </tr>
                                    @endforeach
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
