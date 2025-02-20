@extends('admin.layouts.user')
@section('promo', 'active')
@section('title') {{ $title ?? '' }} @endsection
@push('style')

@endpush
@section('content')
    <div class="content-wrapper mt-3" >
        <div class="content">
            <div class="container-fluid">
                <div class="row px-2 mb-4">
                    <h4>{{ $title ?? "Apply PromoCode" }}</h4>
                </div>
                <div class="card mt-4" style="border: 1px solid#E6EDFF;">

                    <div class="card-body mt-4">
                        <form action="{{ route('user.promo-code.store') }}" method="post" >
                            @csrf
                            <div class="row col-md-6 mx-auto">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="apply_for" class="form-label">Apply For <span class="text-danger">*</span></label>
                                        <select name="apply_for" id="apply_for" class="form-select form-control" required>
                                            <option value="" >Select one</option>
                                            <option value="book" {{ old('apply_for') == 'book' ? 'selected' : '' }}>Book</option>
                                            <option value="package" {{ old('apply_for') == 'package' ? 'selected' : '' }}>Package </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="code" class="form-label">Code <span class="text-danger">*</span></label>
                                        <input type="text" name="code" id="code" class="form-control" required placeholder="Promo code" value="{{ old('code') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn text-light px-5" id="custom_btn">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
@endpush
