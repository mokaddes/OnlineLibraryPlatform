@extends('admin.layouts.master')
@section('promo-package', 'active')
@section('promo-code', 'active menu-open')
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
                                <h3 class="card-title" >All Package PromoCode</h3>
                            </div>
                            <div>
                                <a href="{{ route('admin.package-promo.create') }}" class="btn btn-sm btn-light"
                                style="border: 1px solid #F1F1F1">Add New</a>
                            </div>
                        </div>
                    </div>
                    <div class="px-2 table-responsive">
                        <table class="table table-hover dataTables">
                            <thead>
                                <th class="text-center">SL.</th>
                                <th class="text-center">Title</th>
                                <th class="text-center">Package</th>
                                <th class="text-center">Code</th>
                                <th class="text-center">Validity</th>
                                <th class="text-center">Valid Date</th>
                                <th class="text-center">User Limit</th>
                                <th class="text-center">Used Count</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </thead>
                            <tbody>
                            @foreach($promoCode as $promo)
                                <tr class="text-center">
                                    <td> {{ $loop->iteration }} </td>
                                    <td> {{ $promo->title }} </td>
                                    <td> {{ $promo->package->title ?? '' }} </td>
                                    <td> {{ $promo->code }} </td>
                                    <td> {{ $promo->validity }} Days</td>
                                    <td> {{ date('d M Y', strtotime($promo->valid_date)) }} </td>
                                    <td> {{ $promo->user_limit }} </td>
                                    <td> {{ $promo->used_count }} </td>
                                    <td>
                                        @if($promo->status == 1)
                                            <span class="text-success"> &#9679; Active</span>
                                        @else
                                            <span class="text-danger"> &#9679; Inactive</span>
                                        @endif
                                    </td>
                                    <td>

                                        <a href="{{ route('admin.package-promo.edit', $promo->id) }}"
                                            class="btn btn-sm btn-info" title="Edit">
                                            <i class="fas fa-edit" style="color: #ffffff;"></i>
                                        </a>
                                        <a href="{{ route('admin.package-promo.delete', $promo->id) }}" onclick="return confirm('Are your sure to delete package?')"
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
