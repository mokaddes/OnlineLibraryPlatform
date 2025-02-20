@extends('admin.layouts.master')
@section('package', 'active')
@section('title') {{ $title ?? '' }} @endsection
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
                                <h3 class="card-title" >All Packages</h3>
                            </div>
                            <div>
                                <a href="{{ route('admin.package.create') }}" class="btn btn-sm btn-light"
                                style="border: 1px solid #F1F1F1">Add New</a>
                            </div>
                        </div>
                    </div>
                    <div class="px-2 table-responsive">
                        <table class="table table-hover dataTables">
                            <thead>
                                <th class="text-center">Serial No.</th>
                                <th>Name</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Duration</th>
                                <th>Payment getway</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </thead>
                            <tbody>
                            @foreach($packages as $package)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $package->title }}</td>
                                    <td class="text-center">${{ $package->price }} or N{{ $package->price_ngn }}</td>
                                    <td class="text-center"> {{ $package->duration }} Days</td>
                                    <td>
                                        @if($package->price == 0)
                                            N/A
                                        @else
                                        <p>
                                            Paypal:
                                            @if($package->plan_id2)
                                            {{ $package->plan_id2 }}
                                            @else
                                            <a href="{{ route('admin.package.getPaypal',['id'=>$package->id]) }}" class="badge badge-info" style="font-size: 90% !important;">
                                                +
                                            </a>
                                            @endif
                                        </p>
                                        <p>
                                            Flutterwave:
                                            @if($package->plan_id)
                                            {{ $package->plan_id }}
                                            @else
                                            <a href="{{ route('admin.package.getFluterPlan',['id'=>$package->id]) }}" class="badge badge-info" style="font-size: 90% !important;">
                                                +
                                            </a>
                                            @endif
                                        </p>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($package->status == 1)
                                            <span class="text-success"> &#9679; Active</span>
                                        @else
                                            <span class="text-danger"> &#9679; Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.package.view', $package->id) }}"
                                            class="btn btn-sm btn-secondary" title="View">
                                            <i class="fas fa-eye" style="color: #ffffff;"></i>
                                        </a>
                                        <a href="{{ route('admin.package.edit', $package->id) }}"
                                            class="btn btn-sm btn-info" title="Edit">
                                            <i class="fas fa-edit" style="color: #ffffff;"></i>
                                        </a>
                                        <a href="{{ route('admin.package.delete', $package->id) }}" onclick="return confirm('Are your sure to delete package?')"
                                            class="btn btn-sm btn-danger" title="Delete">
                                            <i class="fas fa-trash" style="color: #ffffff;"></i>
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
