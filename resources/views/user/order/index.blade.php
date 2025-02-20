@extends('admin.layouts.user')
@section('order', 'active')
@section('title') {{ $title ?? '' }} @endsection
@push('style')
    <style>
        .bg-secondary {
            background: #567c73 !important;
        }
    </style>
@endpush
@php
    $balance = 0;
@endphp
@section('content')
    <div class="content-wrapper mt-3 pb-4" >
        <div class="content">
            <div class="container-fluid">
                <div class="row px-2 mb-4">
                    <h4>{{ $title }}</h4>
                </div>
                <div class="card mt-4" style="border: 1px solid#E6EDFF;">
                    <div class="card-header" >
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="card-title" >All Orders</h3>
                            </div>
                        </div>
                    </div>
                    <div class="px-2 table-responsive">
                        <table class="table  dataTables">
                            <thead>
                                <th>SL</th>
                                <th>Transaction ID</th>
                                <th>Book</th>
                                <th>Book Price</th>
                                <th>Company Commission</th>
                                <th>Ordered By</th>
                                <th>Ordered Date</th>
                                <th>status</th>
                            </thead>
                            <tbody>
                                @foreach ($orders as $key => $row)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $row->trx_id }}</td>
                                        <td>{{ $row->product_name }}</td>
                                        <td>{{ $row->amount }}$</td>
                                        <td>{{ $row->commission }}$</td>
                                        <td>{{ $row->userInfo->name ?? ''.' '.$row->userInfo->last_name ?? ''  }}</td>
                                        <td>{{ date('d-M-y H:i:s', strtotime($row->created_at)) }}</td>
                                        <td>
                                            @if($row->payment_status == 1)
                                                <span class="badge badge-success">Paid</span>
                                            @elseif($row->payment_status == 2)
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif($row->payment_status == 3)
                                                <span class="badge badge-danger">Rejected</span>
                                            @else
                                                <span class="badge badge-secondary">Unpaid</span>
                                            @endif
                                        </td>
                                        
                                    </tr>

                                    
                                    @php
                                        if($row->payment_status == 0) 
                                        {
                                            $balance = $balance + ($row->amount - $row->commission);
                                        }
                                    @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($balance > 0)
                <div class="row"><div class="col-4"> 
                    <div class="card p-4 bg-secondary">
                        <div class="row d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Balance : {{number_format($balance,2)}}$</h5>
                            <button class="btn bg-white" type="button" data-toggle="modal" data-target="#staticBackdrop">Claim</button></div> 
                        </div> 
                    </div> 
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Balance claim info</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('user.payment.request.submit') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="amount" id="amount" value="{{$balance}}">
            <div class="modal-body">
                <div class="form-group">
                  <label for="user-email" class="col-form-label">Email: <span class="text-danger">*</span></label>
                  <input type="email" class="form-control" id="user-email" name="email" placeholder="Your Email" required>
                </div>
                <div class="form-group">
                  <label for="user-phone" class="col-form-label">Phone: <span class="text-danger">*</span></label>
                  <input type="tel" class="form-control" id="user-phone" name="phone" placeholder="Your Phone" required>
                </div>
                <div class="form-group">
                    <label for="comment" class="col-form-label">Comment: <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="comment" cols="30" rows="10" name="comment" placeholder="Request info" required></textarea>
                  </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
            </div>
        </form>
      </div>
    </div>
  </div>
@endsection
@push('script')
@endpush
