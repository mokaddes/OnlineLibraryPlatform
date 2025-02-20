@extends('admin.layouts.master')
@section('admin-user', 'active')
@section('admin_management', 'active menu-open')
@section('title') {{ $data['title'] ?? '' }} @endsection
@push('style')
<style>
.status-badge {
    font-size: 0.3rem;
    font-weight: 300;
    padding: 0px 2px;
}
th {
    font-weight: normal !important;
}
.filter{
    background:rgba(92, 36, 130, 0.075);
    border: 1px solid #F1F1F1
}

.filter-active {
    padding: 8px;
    border: 1px solid #10101036;
    background: #ab043124;
}
</style>
@endpush
@section('content')
    <div class="content-wrapper mt-3" >
        <div class="content">
            <div class="container-fluid">
                <div class="card" >
                    <div class="card-header" >
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="card-title">  All Admins </h3>
                            </div>
                            <div>
                                <a href="{{ route('admin.admins.create') }}" class="btn btn-sm btn-light"
                                    style="border: 1px solid #F1F1F1">Add New</a>
                            </div>
                        </div>
                    </div>
                    <div class="px-2 table-responsive">
                        <table class="table table-hover dataTables">
                            <thead>
                                <th class="text-center">Serial No.</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th class="text-center">Role</th>
                                <th class="text-center">Action</th>
                            </thead>
                            <tbody>
                                @foreach ($data['rows'] as $key => $row)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td>{{$row->name ?? 'N/A'}}</td>
                                    <td>{{$row->email ?? 'N/A'}}</td>
                                    <td>{{ucwords($row->role->name ?? 'N/A')}}</td>
                                    <td class="text-center">
                                        <a href="{{route('admin.admins.view',$row->id)}}" class="btn btn-sm" style="background: #9C9C9C" title="View">
                                            <i class="far fa-eye" style="color: #ffffff;"></i>
                                        </a>
                                        <a href="{{route('admin.admins.edit',$row->id)}}" class="btn btn-sm" style="background: #4D1DD4" title="Edit">
                                            <i class="fas fa-pen" style="color: #ffffff;"></i>
                                        </a>
                                        <a href="{{route('admin.admins.destroy',$row->id)}}" title="Delete" class="btn btn-sm" style="background: #EC2626"
                                            onclick="return confirm('{{ __('Are you sure want to delete this Admin') }}')">
                                            <i class="fas fa-trash-alt" style="color: #ffffff;"></i>
                                        </a>
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
@endsection
@push("script")
@endpush
