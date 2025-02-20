@extends('admin.layouts.master')
@section('transaction', 'active')
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
    <div class="content-wrapper mt-3" style="background: #FFFFFF;">
        <div class="content">
            <div class="container-fluid">
                <div class="card" style="border: 1px solid#E6EDFF;">
                    <div class="card-header" style="border-bottom:none !important; background: #ebeefc91;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="card-title" style="font-size: 1.3rem;">Transactions</h3>
                            </div>
                        </div>
                    </div>
                    <div class="px-2 table-responsive">
                        <table class="table table-hover dataTables">
                            <thead>
                                <th>Order ID</th>
                                <th>Transaction ID</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Plan Name</th>
                                <th>Payment Type</th>
                                <th>Created Time</th>
                                <th>Payment Status</th>
                                <th>Actions</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>575962784</td> 
                                    <td>tr_651108f4e32c9</td>
                                    <td>Jabid Hasan</td>
                                    <td>$/N 1000</td> 
                                    <td><span class="badge badge-success">Basic</span></td>
                                    <td>Offline</td>
                                    <td>Sep 25, 2023</td>
                                    <td class="text-success">&#9679; Paid</td>
                                    <td>
                                        <a href="{{ route('admin.transaction.edit', 1) }}" class="btn btn-sm" style="background: #4D1DD4">
                                            <i class="fas fa-pen" style="color: #ffffff;"></i>
                                        </a>
                                        <button class="btn btn-sm" style="background: #EC2626">
                                            <i class="fas fa-trash-alt" style="color: #ffffff;"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>575962784</td> 
                                    <td>tr_651108f4e32c9</td>
                                    <td>Jabid Hasan</td>
                                    <td>$/N 1000</td> 
                                    <td><span class="badge badge-success">Basic</span></td>
                                    <td>Offline</td>
                                    <td>Sep 25, 2023</td>
                                    <td class="text-success">&#9679; Paid</td>
                                    <td>
                                        <a href="{{ route('admin.transaction.edit', 2) }}" class="btn btn-sm" style="background: #4D1DD4">
                                            <i class="fas fa-pen" style="color: #ffffff;"></i>
                                        </a>
                                        <button class="btn btn-sm" style="background: #EC2626">
                                            <i class="fas fa-trash-alt" style="color: #ffffff;"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>575962784</td> 
                                    <td>tr_651108f4e32c9</td>
                                    <td>Jabid Hasan</td>
                                    <td>$/N 1000</td> 
                                    <td><span class="badge badge-success">Basic</span></td>
                                    <td>Offline</td>
                                    <td>Sep 25, 2023</td>
                                    <td class="text-danger">&#9679; Declined</td>
                                    <td>
                                        <a href="{{ route('admin.transaction.edit', 3) }}" class="btn btn-sm" style="background: #4D1DD4">
                                            <i class="fas fa-pen" style="color: #ffffff;"></i>
                                        </a>
                                        <button class="btn btn-sm" style="background: #EC2626">
                                            <i class="fas fa-trash-alt" style="color: #ffffff;"></i>
                                        </button>
                                    </td>
                                </tr>
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