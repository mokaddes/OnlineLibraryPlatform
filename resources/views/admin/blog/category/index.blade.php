@extends('admin.layouts.master')
@section('blogCategory', 'active')
@section('blog_menu', 'active menu-open')
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
                                <h3 class="card-title" >Categories</h3>
                            </div>
                            <div>
                                <a href="{{ route('admin.blog.category.create') }}" class="btn btn-sm btn-light"
                                style="border: 1px solid #F1F1F1">Add New</a>
                            </div>
                        </div>
                    </div>
                    <div class="px-2 table-responsive">
                        <table class="table table-hover dataTables">
                            <thead>
                                <th class="text-center">Serial No.</th>
                                <th>Name</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </thead>
                            <tbody>
                                @foreach ($data['rows'] as $key => $row)
                                    <tr>
                                        <td class="text-center" style="width: 10%">{{$key+1}}</td>
                                        <td>{{$row->name}}</td>
                                        <td style="width: 10%" class="{{ $row->status == 1 ? 'text-success' : 'text-danger' }} text-center">
                                            &#9679; {{ $row->status == 1 ? 'Active' : 'Inactive' }}
                                        </td>                                        
                                        <td class="text-center" style="width: 20%">
                                            <a href="{{ route('admin.blog.category.edit', $row->id ) }}" 
                                                class="btn btn-sm" style="background: #4D1DD4" title="Edit">
                                                <i class="fas fa-pen" style="color: #ffffff;"></i>
                                            </a>
                                            <a href="{{ route('admin.blog.category.delete', $row->id ) }}" title="Delete"
                                                onclick="return confirm('{{ __('Are you sure want to delete this item') }}')"
                                                class="btn btn-sm" style="background: #EC2626">
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