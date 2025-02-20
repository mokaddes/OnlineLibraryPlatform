@extends('admin.layouts.user')
@section('transaction', 'active')
@section('title')
    {{ 'Transaction' }}
@endsection
@push('style')
    <style>
    </style>
@endpush
@section('content')
    <div class="content-wrapper mt-3 pb-4">
        <div class="content">
            <div class="container-fluid">
                <div class="row px-2 mb-4">
                    <h4>Transactions</h4>
                </div>

                <div class="card mt-4" style="border: 1px solid#E6EDFF;">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="card-title">Transaction</h3>
                            </div>
                        </div>
                    </div>
                    <div class="px-2 table-responsive">
                        <table class="table  dataTables">
                            <thead>
                            <th>SL</th>
                            <th>Transaction ID</th>
                            <th>Transaction For</th>
                            <th>Payment Provider</th>
                            <th>Amount</th>
                            <th>Payment Status</th>
                            </thead>
                            <tbody>
                            @foreach ($transactions as $tr)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $tr->transaction_id }}</td>
                                    @if(isset($tr->book_id) && !empty($tr->book_id))
                                    <td>Book : {{ $tr->book->title ?? '' }}</td>
                                    @else
                                    <td>Package : {{ $tr->package->title ?? '' }}</td>
                                    @endif
                                    <td>{{ $tr->payment_provider ?? 'N/A' }}</td>
                                    <td>{{ $tr->usd_amount ? $tr->usd_amount.'$'  : '0'}}</td>
                                    <td>{{ $tr->payment_status ?? 'N/A'}}</td>
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
@push('script')
@endpush
