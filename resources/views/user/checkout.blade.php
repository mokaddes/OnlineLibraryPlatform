@extends('admin.layouts.user')
@section('settings', 'active')
@section('title')
    {{ $title ?? '' }}
@endsection
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/intlTelInput.css') }}">
    <style>
        .table td, .table th {
            border: 1px solid #9b96967d !important;
            color: #666 !important;
        }

        .form-selectgroup-boxes .form-selectgroup-label {
            text-align: left;
            padding: 1rem 1rem;
            color: inherit;
        }

        .iti--allow-dropdown input, .iti--allow-dropdown input[type=text], .iti--allow-dropdown input[type=tel], .iti--separate-dial-code input, .iti--separate-dial-code input[type=text], .iti--separate-dial-code input[type=tel] {
            padding-right: 6px !important;
            padding-left: 52px !important;
            margin-left: 0;
        }

        .form-selectgroup-label {
            position: relative;
            display: block;
            min-width: calc(1.4285714em + 0.875rem + 2px);
            margin: 0;
            padding: 0.4375rem 0.75rem;
            font-size: .875rem;
            line-height: 1.4285714;
            color: #656d77;
            background: #fff;
            text-align: center;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            border: 1px solid #dadcde;
            border-radius: 3px;
            transition: border-color .3s, background .3s, color .3s;
        }

        .form-selectgroup-input[type=radio] + .form-selectgroup-label .form-selectgroup-check {
            border-radius: 50%;
        }

        .form-selectgroup-input {
            position: absolute;
            top: 0;
            left: 0;
            z-index: -1;
            opacity: 0;
        }

        .form-selectgroup .form-selectgroup-item {
            margin: 0 0.5rem 0.5rem 0;
        }

        .form-selectgroup-item {
            display: block;
            position: relative;
        }

        .form-selectgroup-input:checked + .form-selectgroup-label {
            z-index: 1;
            color: #206bc4;
            background: rgba(32, 107, 196, .25);
            border-color: #90b5e2;
        }

        input.country_selector,
        .country_selector button {
            height: 35px;
            margin: 0;
            padding: 6px 12px;
            border-radius: 2px;
            font-family: inherit;
            font-size: 100%;
            color: inherit;
        }

        ::-webkit-input-placeholder {
            color: #BBB;
        }

        ::-moz-placeholder {
            color: #BBB;
            opacity: 1;
        }

        :-ms-input-placeholder {
            color: #BBB;
        }

        #result {
            margin-bottom: 100px;
        }

        .country-select.inside {
            width: 100% !important;
        }

        .iti.iti--allow-dropdown {
            width: 100%;
        }
    </style>
@endpush
<?php
    if($checkout_for == 'book')
    {
        $plan->price = $plan->book_price;
    } else {
        $plan->price = $plan->price;
    }
?>
@section('content')
    <div class="content-wrapper mt-3 p-3">
        <div class="content">
            <div class="container-fluid">
                <div class="row g-4">
                    <div class="col-xl-4">
                        <div class="card">
                            <div class="card-header">
                                @if($checkout_for == 'book')
                                    <h4 class="card-title">{{ __('Buy Book') }}</h4>
                                @else 
                                    <h4 class="card-title">{{ __('Upgrade Plan') }}</h4>
                                @endif
                            </div>
                            <div class="card-body">
                                <div class="card-table">
                                    <table class="table table-bordered">
                                        <thead class="bg-white">
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>{{ $checkout_for == 'book' ? __('Book title') : __('Plan title') }}</td>
                                            <td class="w-1 text-bold h6 text-right">
                                                {{ $plan->title }}
                                            </td>
                                        </tr>
                                        {{-- @if ($tax_percent)
                                            <tr>
                                                <td>Tax <small> (__%)</small></td>
                                                <td class="text-right">___</td>
                                            </tr>
                                        @endif --}}
                                        <tr>
                                            <td class="h6 text-bold">{{ __('Total Payable') }}</td>
                                            <td class="w-1 text-bold h6 text-right total-payable-td">
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8 col-12">
                        <form action="{{ route('user.checkout.submit', $plan->id) }}" id="order-form" method="post">
                            @csrf
                            @if($checkout_for == 'book')
                                <input type="hidden" name="billing_for" id="billing_for" value="book">
                            @else
                                <input type="hidden" name="billing_for" id="billing_for" value="plan">
                            @endif
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{ __('Billing Details') }}</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 col-xl-4">
                                            <div class="mb-3 form-group">
                                                <label class="form-label">{{ __('Name') }} <span
                                                        class="text-danger">*</span></label>
                                                <input type="text"
                                                       class="form-control @error('billing_name') is-invalid @enderror"
                                                       name="billing_name" placeholder="{{ __('Name') }}..." required
                                                       value="{{ old('billing_name') ?? $user->name }} {{ old('billing_name') ? '' :$user->last_name ?? ''}}">
                                                @if ($errors->has('billing_name'))
                                                    <span
                                                        class="help-block text-danger">{{ $errors->first('billing_name') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-xl-4">
                                            <div class="mb-3 form-group">
                                                <label class="form-label" for="billing_email">{{ __('Email') }} <span
                                                        class="text-danger">*</span></label>
                                                <input type="email"
                                                       class="form-control validated @error('billing_email') is-invalid @enderror"
                                                       name="billing_email" id="billing_email"
                                                       placeholder="{{ __('Email') }}..." required
                                                       value="{{ old('billing_email') ?? $user->email }}"
                                                       data-validation-required-message="Please enter your address">
                                                @if ($errors->has('billing_email'))
                                                    <span
                                                        class="help-block text-danger">{{ $errors->first('billing_email') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-xl-4">
                                            <div class="mb-3 form-group">
                                                <label for="billing_address"
                                                       class="form-label">{{ __('Billing Address') }} <span
                                                        class="text-danger">*</span></label>
                                                <textarea style="height: 43px !important"
                                                          class="form-control validated @error('billing_address') is-invalid @enderror"
                                                          name="billing_address" id="billing_address" cols="10" rows="1"
                                                          placeholder="{{ __('Billing Address') }}..."
                                                          required>{{ old('billing_address') ?? $user->address }}</textarea>
                                                @if ($errors->has('billing_address'))
                                                    <span
                                                        class="help-block text-danger">{{ $errors->first('billing_address') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-xl-4">
                                            <div class="mb-3 form-group">
                                                <label for="billing_city" class="form-label">{{ __('Billing City') }}
                                                    <span class="text-danger">*</span></label>
                                                <input type="text"
                                                       class="form-control @error('billing_city') is-invalid @enderror"
                                                       name="billing_city" id="billing_city"
                                                       value="{{ old('billing_city') ?? $user->city }}"
                                                       placeholder="{{ __('Billing City') }}..." required>
                                                @if ($errors->has('billing_city'))
                                                    <span
                                                        class="help-block text-danger">{{ $errors->first('billing_city') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-xl-4">
                                            <div class="mb-3 form-group">
                                                <label for="billing_state" class="form-label">{{ __('Billing State') }}
                                                    <span class="text-danger">*</span></label>
                                                <input type="text"
                                                       class="form-control @error('billing_state') is-invalid @enderror"
                                                       id="billing_state"
                                                       name="billing_state"
                                                       value="{{ old('billing_state') ?? $user->state }}"
                                                       placeholder="{{ __('Billing State/Province') }}..." required>
                                                @if ($errors->has('billing_state'))
                                                    <span
                                                        class="help-block text-danger">{{ $errors->first('billing_state') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-xl-4">
                                            <div class="mb-3 form-group">
                                                <label for="billing_zipcode"
                                                       class="form-label">{{ __('Billing Zip Code') }}
                                                    <span class="text-danger">*</span></label>
                                                <input type="text"
                                                       class="form-control @error('billing_zipcode') is-invalid @enderror"
                                                       name="billing_zipcode" id="billing_zipcode" v
                                                       placeholder="{{ __('Billing Zip Code') }}..."
                                                       value="{{ old('billing_zipcode') ?? $user->zipcode }}" required>
                                                @if ($errors->has('billing_zipcode'))
                                                    <span
                                                        class="help-block text-danger">{{ $errors->first('billing_zipcode') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-xl-4">
                                            <div class="mb-3 form-group">
                                                <label for="country" class="form-label">{{ __('Billing Country') }}
                                                    <span class="text-danger">*</span></label>
                                                <div class="form-item">
                                                    <select class="form-select" id="country" name="billing_country"
                                                            required></select>
                                                </div>
                                                <div class="form-item" style="display:none;">
                                                    <input type="text" id="country_selector_code" hidden
                                                           name="country_selector_code" data-countrycodeinput="1"
                                                           readonly="readonly"
                                                           placeholder="Selected country code will appear here"/>
                                                    <label for="country_selector_code"></label>
                                                </div>
                                                @if ($errors->has('billing_country'))
                                                    <span
                                                        class="help-block text-danger">{{ $errors->first('billing_country') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-xl-4">
                                            <div class="mb-3 form-group">
                                                <label class="form-label">{{ __('Type') }} <span
                                                        class="text-danger">*</span></label>
                                                <select name="type" id="type"
                                                        class="form-control @error('type') is-invalid @enderror">
                                                    <option value="personal">
                                                        {{ __('Personal') }}</option>
                                                    <option value="business">{{ __('Business') }}</option>
                                                </select>
                                                @if ($errors->has('type'))
                                                    <span
                                                        class="help-block text-danger">{{ $errors->first('type') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-xl-4">
                                            <div class="mb-3 form-group">
                                                <label for="billing_phone" class="form-label d-block">{{ __('Phone') }}
                                                    <span class="text-danger">*</span></label>
                                                <input id="billing_phone" name="billing_phone"
                                                       value="{{ $user->billing_phone ? $user->billing_phone : $user->dial_code . $user->phone  }}"
                                                       class="form-control" type="tel" required>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label for="payment_gateway_id" class="form-label d-block">
                                                {{ __('Payment Method') }} <span class="text-danger">*</span>
                                            </label>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <div
                                                            class="form-selectgroup form-selectgroup-boxes d-flex flex-column">
                                                            <label class="form-selectgroup-item flex-fill">
                                                                <input type="radio" name="payment_gateway_id"
                                                                       value="select_1"
                                                                       id="payment_gateway_id"
                                                                       class="select_1 form-selectgroup-input" checked>
                                                                <div
                                                                    class="form-selectgroup-label d-flex align-items-center p-3">
                                                                    <div class="me-3">
                                                                        <span class="form-selectgroup-check"></span>
                                                                    </div>
                                                                    <div>
                                                                        <span class="payment payment-xs me-2">
                                                                            <img width="36"
                                                                                 src="{{ asset('assets/images/paypal-logo.png') }}"
                                                                                 alt="Paypal">
                                                                        </span>
                                                                        {{ __('Paypal') }}
                                                                    </div>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($checkout_for != 'book')
                                                    <div class="col-md-3">
                                                        <div class="mb-3">
                                                            <div
                                                                class="form-selectgroup form-selectgroup-boxes d-flex flex-column">
                                                                <label class="form-selectgroup-item flex-fill">
                                                                    <input type="radio" name="payment_gateway_id"
                                                                        value="select_2"
                                                                        id="payment_gateway_id"
                                                                        class="select_2 form-selectgroup-input">
                                                                    <div
                                                                        class="form-selectgroup-label d-flex align-items-center p-3"
                                                                        style="height: 70px !important;">
                                                                        <div class="me-3">
                                                                            <span class="form-selectgroup-check"></span>
                                                                        </div>
                                                                        <div>
                                                                            <span class="payment payment-xs me-2">
                                                                                <img width="36"
                                                                                    src="{{ asset('assets/images/flutterwave_logo.png') }}"
                                                                                    alt="Paypal">
                                                                            </span>
                                                                            {{ __('Flutterwave') }}
                                                                        </div>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            {{-- @if (!empty($gateways) && count($gateways) > 0)
                                                @foreach ($gateways as $key => $gateway)
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <div
                                                                class="form-selectgroup form-selectgroup-boxes d-flex flex-column">
                                                                <label class="form-selectgroup-item flex-fill">
                                                                    <input type="radio" name="payment_gateway_id"
                                                                            id="payment_gateway_id"
                                                                            value="{{ $gateway->id }}"
                                                                            class="form-selectgroup-input @error('payment_gateway_id') is-invalid @enderror" {{$key==0 ? 'checked':''}}>
                                                                    <div
                                                                        class="form-selectgroup-label d-flex align-items-center p-3">
                                                                        <div class="me-3">
                                                                            <span class="form-selectgroup-check"></span>
                                                                        </div>
                                                                        <div>
                                                                            <span
                                                                                class="payment payment-provider-{{ $gateway->payment_gateway_name == 'Paypal' ? 'paypal' : 'visa' }} payment-xs me-2">
                                                                                <img width="36"
                                                                                        src="{{ asset($gateway->payment_gateway_logo) }}"
                                                                                        alt="{{ $gateway->display_name }}">
                                                                            </span>
                                                                            {{ $gateway->display_name }}
                                                                            <strong></strong>
                                                                        </div>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif --}}
                                            <button type="submit"
                                                    class="btn btn-primary">{{ __('Continue for Payment') }}</button>
                                        </div>
                                    </div>
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
    <script src="{{ asset('assets/js/intlTelInput.js') }}"></script>
    <script>
        $(document).ready(function () {
            var countryCode = '';
            var input = document.querySelector("#billing_phone");
            window.intlTelInput(input, {
                autoHideDialCode: false,
                autoPlaceholder: "on",
                dropdownContainer: document.body,
                formatOnDisplay: true,
                geoIpLookup: function (callback) {
                    $.get("https://ipinfo.io", function () {
                    }, "jsonp").always(function (resp) {
                        var countryCode = (resp && resp.country) ? resp.country : "";
                        callback(countryCode);
                        console.log(countryCode);
                    });
                },
                hiddenInput: "full_number",
                initialCountry: "NG",
                nationalMode: false,
                placeholderNumberType: "MOBILE",
                preferredCountries: ['us', 'uk', 'ca'],
                separateDialCode: false,
                utilsScript: "{{ asset('assets/js/utils.js') }}",
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            var defaultCountry = "{{ $user->country ?? '' }}";
            $('#country').select2({
                placeholder: 'Select a Country / Region',
                allowClear: true,
                ajax: {
                    url: 'https://restcountries.com/v2/all',
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    id: item.name,
                                    text: item.name
                                };
                            })
                        };
                    }
                },
                data: defaultCountry ? [{id: defaultCountry, text: defaultCountry}] : null
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $('.total-payable-td').text('$' + '{{ number_format($plan->price, 2) }}');

            $('.select_1, .select_2').on('change', function () {
                var selectedValue = $('input[name="payment_gateway_id"]:checked').val();

                if (selectedValue == 'select_1') {
                    $('.total-payable-td').text('$' + '{{ number_format($plan->price, 2) }}');
                } else if (selectedValue == 'select_2') {
                    $('.total-payable-td').text('N' + '{{ number_format($plan->price_ngn, 2) }}');
                }
            });
        });
    </script>
@endpush
