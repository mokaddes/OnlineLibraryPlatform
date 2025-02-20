@extends('admin.layouts.master')
@section('transaction', 'active')
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
                                <h3 class="card-title" >Transactions</h3>
                            </div>
                        </div>
                    </div>
                    <div class="px-2 table-responsive">
                        <table class="table table-hover dataTables">
                            <thead>
                                <th>SL</th>
                                <th>Transaction ID</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Plan Name</th>
                                <th>Payment Provider</th>
                                <th>Payment Status</th>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $tr)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $tr->transaction_id }}</td>
                                        <td>{{ $tr->user->name ?? 'N/A' }} {{ $tr->user->last_name ?? '' }}</td>
                                        <td>{{ $tr->amount == 0 ? 'N/A' : $tr->amount }}</td>
                                        <td>{{ $tr->package->title ?? 'N/A' }}</td>
                                        <td>{{ $tr->payment_provider }}</td>
                                        <td><span class="text-success">&#9679; {{ $tr->payment_status }}</span></td>
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
