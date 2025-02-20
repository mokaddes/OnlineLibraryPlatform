@extends('admin.layouts.master')
@section('promo-package', 'active')
@section('promo-code', 'active menu-open')
@section('title')
    {{ $title ?? '' }}
@endsection

@push('style')

    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <style>
        input, select, textarea {
            border-radius: 0 !important;
        }

        @media only screen and (min-width: 768px) {
            #responsive_btn {
                margin-top: 30px;
            }
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__display {
            color: #111111 !important;
        }
    </style>
@endpush
@section('content')
    <div class="content-wrapper mt-3">
        <div class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="card-title">{{ isset($promocodePackage) ? 'Edit' : 'Add New'}}</h3>
                            </div>
                            <div>
                                <a href="{{ route('admin.package-promo.index') }}" class="btn btn-sm btn-light"
                                   style="border: 1px solid #F1F1F1">Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mt-4">
                        <div class="col-md-8 container-fluid">
                            <form method="POST"
                                  action="{{ isset($promocodePackage) ? route('admin.package-promo.update', ['id' => $promocodePackage->id]) : route('admin.package-promo.store') }}">
                                @csrf

                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" id="title" name="title"
                                           value="{{ old('title', $promocodePackage->title ?? '') }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="code">Code</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="code" name="code" value="{{ old('code', $promocodePackage->code ?? '') }}" required>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" id="generateCode">Generate Code</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="validity">Validity (in days)</label>
                                    <input type="number" class="form-control" id="validity" name="validity"
                                           value="{{ old('validity', $promocodePackage->validity ?? '') }}" required>
                                </div>

{{--                                <div class="form-group">--}}
{{--                                    <label for="valid_date">Valid Date</label>--}}
{{--                                    <input type="text" class="form-control" id="valid_date" name="valid_date"--}}
{{--                                           value="{{ old('valid_date', isset($promocodePackage->valid_date) ? date('d-m-Y', strtotime($promocodePackage->valid_date)) : '') }}" required>--}}
{{--                                </div>--}}

                                <div class="form-group">
                                    <label for="user_limit">User Limit</label>
                                    <input type="number" class="form-control" id="user_limit" name="user_limit"
                                           value="{{ old('user_limit', $promocodePackage->user_limit ?? '') }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="package_id">Package</label>
                                    <select class="form-control select2" id="package_id" name="package_id"  required>
                                        @foreach($packages as $package)
                                            <option
                                                value="{{ $package->id }}" {{ $package->id == old('package_id', $promocodePackage->package_id ?? '') ? 'selected' : '' }}>
                                                {{ $package->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option
                                            value="1" {{ isset($promocodePackage) && $promocodePackage->status === '1' ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option
                                            value="0" {{ isset($promocodePackage) && $promocodePackage->status === '0' ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                    </select>
                                </div>

                                <button type="submit"
                                        class="btn btn-primary">{{ isset($promocodePackage) ? 'Update' : 'Create' }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        $(document).ready(function () {
            $('#generateCode').on('click', function () {
                var generatedCode = generateRandomCode(8);
                $('#code').val(generatedCode);
            });

            function generateRandomCode(length) {
                var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                var code = '';

                for (var i = 0; i < length; i++) {
                    code += characters.charAt(Math.floor(Math.random() * characters.length));
                }

                return code;
            }
        });
        $( function() {
            $('#valid_date').datepicker({
                dateFormat: 'dd-mm-yy',
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                onSelect: function(selectedDate) {
                    // we can write code here
                }
            });
        } );
    </script>
@endpush
