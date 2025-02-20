@extends('admin.layouts.master')
@section('transaction', 'active')
@section('title') {{ $data['title'] ?? '' }} @endsection
@push('style')
<style>
    input, select, textarea {
    border-radius: 10px !important;
}   
</style>
@endpush
@section('content')
    <div class="content-wrapper mt-3" >
        <div class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header" >
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="card-title">Edit Transaction</h3>
                            </div>
                            <div>
                                <a href="{{ route('admin.transaction.index') }}" class="btn btn-sm btn-light"
                                    style="border: 1px solid #F1F1F1">Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mt-4">
                        <form action="#" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="customer" class="form-label">Customer</label>
                                        <input type="text" name="customer" id="customer" class="form-control" required placeholder="Customer">
                                    </div>
                                    @error('customer')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="amount" class="form-label">Amount</label>
                                        <input type="text" name="amount" id="amount" class="form-control" required placeholder="Amount">
                                    </div>
                                    @error('amount')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="title" class="form-label">Plan Name</label>
                                        <select name="physicalForm" id="physicalForm" class="form-select form-control">
                                            <option value="basic">Basic</option>
                                            <option value="gold">Gold</option>
                                            <option value="premium">Premium</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row offset-md-2">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="title" class="form-label">Payment Type</label>
                                        <select name="physicalForm" id="physicalForm" class="form-select form-control">
                                            <option value="offline">Offline</option>
                                            <option value="online">Online</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="title" class="form-label">Payment Status</label>
                                        <select name="physicalForm" id="physicalForm" class="form-select form-control">
                                            <option value="paid">Paid</option>
                                            <option value="declined">Declined</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn text-light px-5" id="custom_btn">Edit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
