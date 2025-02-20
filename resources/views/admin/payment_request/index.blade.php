@extends('admin.layouts.master')
@section('paymentRequest', 'active')
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
                                <h3 class="card-title" >Payment Request</h3>
                            </div>
                        </div>
                    </div>
                    <div class="px-2 table-responsive">
                        <table class="table table-hover dataTables">
                            <thead>
                                <th>SL</th>
                                <th>User</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Comment</th>
                                <th>Amount</th>
                                <th>Request Date</th>
                                <th>Payment Status</th>
                                <td></td>
                            </thead>
                            <tbody>
                                @foreach ($rows as $tr)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $tr->userInfo->name ?? '' }}&nbsp;{{ $tr->userInfo->last_name ?? '' }}</td>
                                        <td>{{ $tr->email ?? 'N/A' }}</td>
                                        <td>{{ $tr->phone ?? 'N/A' }}</td>
                                        <td>{{ $tr->comment ?? 'N/A' }}</td>
                                        <td>{{ $tr->amount ? number_format($tr->amount,2) : 0.00 }}$</td>
                                        <td>{{ date('d-M-y H:i:s', strtotime($tr->created_at)) }}</td>
                                        <td>
                                            @if($tr->payment_status == 'paid')
                                                <span class="text-success">&#9679; {{ ucfirst($tr->payment_status) }}</span>
                                            @elseif($tr->payment_status == 'pending')
                                                <span class="text-warning">&#9679; {{ ucfirst($tr->payment_status) }}</span>
                                            @elseif($tr->payment_status == 'rejected')
                                                <span class="text-danger">&#9679; {{ ucfirst($tr->payment_status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($tr->payment_status == 'pending')
                                                <a href="{{route('admin.payment.request.status.change', ['id' => $tr->id, 'status' => 'paid'])}}" class="btn btn-sm btn-success">Pay</a>
                                                <a href="{{route('admin.payment.request.status.change', ['id' => $tr->id, 'status' => 'rejected'])}}" class="btn btn-sm btn-danger">Reject</a>
                                            @endif
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
