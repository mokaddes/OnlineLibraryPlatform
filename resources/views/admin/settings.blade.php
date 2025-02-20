@extends('admin.layouts.master')
@section('setting', 'active')
@section('title')
    {{ $data['title'] ?? '' }}
@endsection

@push('style')
    <style>
        input, select, textarea {
            border-radius: 10px !important;
        }

        .card-title {
            float: none;
            text-align: center;
        }
    </style>
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="content-wrapper mt-3">
        <div class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="card-title">{{ $data['title'] ?? '' }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.settings.general_store') }}" method="post"
                              enctype="multipart/form-data" id="settingUpdate">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Site Settings</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <img src="{{ getLogo($settings->site_logo) }}" height="50px"/>
                                                    <div class="mb-3">
                                                        <div class="form-label">{{ __('Site Logo') }} <span
                                                                class="text-danger">
                                                                ({{ __('Recommended size : 180x60') }})</span>
                                                        </div>
                                                        <input type="file" class="form-control" name="site_logo"
                                                               placeholder="{{ __('Site Logo') }}..."
                                                               accept=".png,.jpg,.jpeg,.gif,.svg"/>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <img src="{{ getLogo($settings->admin_logo) }}"
                                                         height="50px"/>

                                                    <div class="mb-3">
                                                        <div class="form-label">{{ __('Admin Logo') }} <span
                                                                class="text-danger">({{ __('Recommended size : 180x60') }})</span>
                                                        </div>
                                                        <input type="file" class="form-control" name="admin_logo"
                                                               placeholder="{{ __('admin logo') }}..."
                                                               accept=".png,.jpg,.jpeg,.gif,.svg"/>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">

                                                    <img src="{{ getSeoImage($settings->seo_image) }}"
                                                         height="50px"/>

                                                    <div class="mb-3">
                                                        <div class="form-label">{{ __('SEO image') }} <span
                                                                class="text-danger">
                                                                ({{ __('Recommended size : 728x680') }})</span>
                                                        </div>
                                                        <input type="file" class="form-control" name="seo_image"
                                                               placeholder="{{ __('SEO image') }}..."
                                                               accept=".png,.jpg,.jpeg,.gif,.svg"/>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    @if ($settings->favicon)
                                                        <img src="{{ getIcon($settings->favicon) }}"
                                                             height="50px"/>
                                                    @endif
                                                    <div class="mb-3">
                                                        <div class="form-label">{{ __('Favicon') }} <span
                                                                class="text-danger">
                                                                ({{ __('Recommended size : 200x200') }})</span>
                                                        </div>
                                                        <input type="file" class="form-control" name="favicon"
                                                               placeholder="{{ __('Favicon') }}..."
                                                               accept=".png,.jpg,.jpeg,.gif,.svg"/>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">{{ __('App Name') }}</label>
                                                        <input type="text" class="form-control" name="app_name"
                                                               value="{{ config('app.name') }}"
                                                               placeholder="{{ __('App Name') }}..." readonly>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label required">{{ __('Site Name') }}</label>
                                                        <input type="text" class="form-control" name="site_name"
                                                               value="{{ $settings->site_name }}"
                                                               placeholder="{{ __('Site Name') }}..." required>
                                                    </div>
                                                </div>
                                                {{-- <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label required">{{ __('Site Title') }}</label>
                                                        <input type="text" class="form-control"
                                                            name="site_name" value="{{ $settings->site_name }}"
                                                            placeholder="{{ __('Site Title') }}..." required>
                                                    </div>
                                                </div> --}}
                                                <div class="col-12">
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label required">{{ __('SEO Meta Description') }}</label>
                                                        <textarea class="form-control" name="seo_meta_desc" rows="3"
                                                                  placeholder="{{ __('SEO Meta Description') }}"
                                                                  style="height: 120px !important;"
                                                                  required>{{ $settings->seo_meta_description }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">{{ __('SEO Keywords') }}</label>
                                                        <textarea class="form-control required" name="meta_keywords"
                                                                  rows="3"
                                                                  placeholder="{{ __('SEO Keywords (Keyword 1, Keyword 2)') }}"
                                                                  style="height: 120px !important;"
                                                                  required>{{ $settings->seo_keywords }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    {{-- <div class="mb-3">
                                                        <label class="form-label">{{ __('Main Motto') }}</label>
                                                        <textarea class="form-control required" name="main_motto" rows="3" placeholder="{{ __('Main moto') }}"
                                                            style="height: 120px !important;" required>{{ $settings->main_motto }}</textarea>
                                                    </div> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">General Settings</h3>
                                        </div>
                                        <div class="card-body">

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label required"
                                                               for="timezone">{{ __('Timezone') }}</label>
                                                        <select name="timezone" id="timezone"
                                                                class="form-control" required>
                                                            @foreach (timezone_identifiers_list() as $timezone)
                                                                <option value="{{ $timezone }}"
                                                                    {{ $config[2]->config_value == $timezone ? ' selected' : '' }}>
                                                                    {{ $timezone }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('timezone')
                                                        <span
                                                            class="help-block text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label required"
                                                               for="currency">{{ __('Currency') }}</label>
                                                        <select name="currency" id="currency"
                                                                class="form-control" required>
                                                            @foreach ($currencies as $currency)
                                                                <option value="{{ $currency->iso_code }}"
                                                                    {{ $config[1]->config_value == $currency->iso_code ? ' selected' : '' }}>
                                                                    {{ $currency->name }}
                                                                    ({{ $currency->symbol }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('currency')
                                                        <span
                                                            class="help-block text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <div class="form-label">{{ __('Email') }}</div>
                                                        <input type="email" name="email" class="form-control"
                                                               value="{{ $settings->email }}">
                                                        @error('email')
                                                        <span
                                                            class="help-block text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <div class="form-label">{{ __('Support Email') }}</div>
                                                        <input type="support_email" name="support_email"
                                                               class="form-control"
                                                               value="{{ $settings->support_email }}">
                                                        @error('support_email')
                                                        <span
                                                            class="help-block text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <div class="form-label">{{ __('Email Verification') }}</div>
                                                        <select type="text" class="form-select form-control"
                                                                name="email_verification">
                                                            <option
                                                                value="1" {{ $settings->email_verification == '1' ? 'selected' : '' }}>
                                                                {{ __('Yes') }}
                                                            </option>
                                                            <option
                                                                value="0" {{ $settings->email_verification == '0' ? 'selected' : '' }}>
                                                                {{ __('No') }}
                                                            </option>
                                                        </select>
                                                        @error('email_verification')
                                                        <span class="help-block text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <div class="form-label">{{ __('Phone No') }}</div>
                                                        <input type="tel" name="phone_no"
                                                               class="form-control"
                                                               value="{{ $settings->phone_no }}">
                                                        @error('phone_no')
                                                        <span
                                                            class="help-block text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <div class="form-label">{{ __('Commission rate (in percentage)') }}</div>
                                                        <input type="number" name="commission"
                                                               class="form-control" min="0" step="0.01"
                                                               value="{{ $settings->commission }}">
                                                        @error('commission')
                                                        <span
                                                            class="help-block text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <div class="form-label">{{ __('Office Address') }}</div>
                                                        <input type="office_address" name="office_address"
                                                               class="form-control"
                                                               value="{{ $settings->office_address }}">
                                                        @error('office_address')
                                                        <span
                                                            class="help-block text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- email setting --}}
                                {{-- <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Email Configuration Settings
                                            </h3>
                                        </div>
                                        <div class="card-body">

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">{{ __('Mailer Host') }}</label>
                                                        <input type="text" class="form-control"
                                                            name="mail_host" value="{{ $settings->host }}"
                                                            placeholder="{{ __('Mailer Host') }}...">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">{{ __('Mailer Port') }}</label>
                                                        <input type="text" class="form-control"
                                                            name="mail_port" value="{{ $settings->port }}"
                                                            placeholder="{{ __('Mailer Port') }}...">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label">{{ __('Mailer Encryption') }}</label>
                                                        <input type="text" class="form-control"
                                                            name="mail_encryption"
                                                            value="{{ $settings->encryption }}"
                                                            placeholder="{{ __('Mailer Encryption') }}...">
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label">{{ __('Mailer Username') }}</label>
                                                        <input type="text" class="form-control"
                                                            name="mail_username"
                                                            value="{{ $settings->username }}"
                                                            placeholder="{{ __('Mailer Username') }}...">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label">{{ __('Mailer Password') }}</label>
                                                        <input type="password" class="form-control"
                                                            name="mail_password"
                                                            value="{{ $settings->password }}"
                                                            placeholder="{{ __('Mailer Password') }}...">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                                {{-- paypal setting --}}
                                {{-- <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Paypal Settings</h3>
                                        </div>
                                        <div class="card-body">

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label required">{{ __('Mode') }}</label>
                                                        <select type="text" class="form-select form-control"
                                                            placeholder="Select a payment mode"
                                                            id="select-tags-advanced" name="paypal_mode" required>
                                                            <option value="sandbox"
                                                                {{ $config[3]->config_value == 'sandbox' ? 'selected' : '' }}>
                                                                {{ __('Sandbox') }}</option>
                                                            <option value="live"
                                                                {{ $config[3]->config_value == 'live' ? 'selected' : '' }}>
                                                                {{ __('Live') }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label required">{{ __('Client Key') }}</label>
                                                        <input type="text" class="form-control"
                                                            name="paypal_client_key"
                                                            value="{{ $config[4]->config_value }}"
                                                            placeholder="{{ __('Client Key') }}..." required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label class="form-label"
                                                            required>{{ __('Secret') }}</label>
                                                        <input type="text" class="form-control"
                                                            name="paypal_secret"
                                                            value="{{ $config[5]->config_value }}"
                                                            placeholder="{{ __('Secret') }}..." required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                                {{-- strip setting --}}
                                {{-- <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Strip Settings</h3>
                                        </div>
                                        <div class="card-body">

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label required">{{ __('Mode') }}</label>
                                                        <select type="text" class="form-select form-control"
                                                            placeholder="Select a payment mode"
                                                            id="select-tags-advanced" name="paypal_mode" required>
                                                            <option value="sandbox"
                                                                {{ $config[3]->config_value == 'sandbox' ? 'selected' : '' }}>
                                                                {{ __('Sandbox') }}</option>
                                                            <option value="live"
                                                                {{ $config[3]->config_value == 'live' ? 'selected' : '' }}>
                                                                {{ __('Live') }}</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label required">{{ __('Publishable Key') }}</label>
                                                        <input type="text" class="form-control"
                                                            name="stripe_publishable_key"
                                                            value="{{ $config[9]->config_value }}"
                                                            placeholder="{{ __('Publishable Key') }}..." required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label required">{{ __('Secret') }}</label>
                                                        <input type="text" class="form-control"
                                                            name="stripe_secret"
                                                            value="{{ $config[10]->config_value }}"
                                                            placeholder="{{ __('Secret') }}..." required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                                {{-- Social --}}
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Social URL</h3>
                                        </div>
                                        <div class="card-body">

                                            <div class="row mt-3 mb-5">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label">{{ __('Instagram URL') }}</label>
                                                        <input type="url" class="form-control"
                                                               name="instagram_url"
                                                               value="{{ $settings->instagram_url }}"
                                                               placeholder="{{ __('Instagram URL') }}...">
                                                        @error('instagram_url')
                                                        <span
                                                            class="help-block text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label">{{ __('Facebook URL') }}</label>
                                                        <input type="url" class="form-control"
                                                               name="facebook_url"
                                                               value="{{ $settings->facebook_url }}"
                                                               placeholder="{{ __('Facebook URL') }}...">
                                                        @error('facebook_url')
                                                        <span
                                                            class="help-block text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">{{ __('Youtube Url') }}</label>
                                                        <input type="url" class="form-control"
                                                               name="youtube_url"
                                                               value="{{ $settings->youtube_url }}"
                                                               placeholder="{{ __('Youtube Url') }}...">
                                                        @error('youtube_url')
                                                        <span
                                                            class="help-block text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">{{ __('Twitter Url') }}</label>
                                                        <input type="url" class="form-control"
                                                               name="twitter_url"
                                                               value="{{ $settings->twitter_url }}"
                                                               placeholder="{{ __('Twitter Url') }}...">
                                                        @error('twitter_url')
                                                        <span
                                                            class="help-block text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label">{{ __('Linkedin url') }}</label>
                                                        <input type="url" class="form-control"
                                                               name="linkedin_url"
                                                               value="{{ $settings->linkedin_url }}"
                                                               placeholder="{{ __('Linkedin url') }}...">
                                                        @error('linkedin_url')
                                                        <span
                                                            class="help-block text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label">{{ __('Telegram url') }}</label>
                                                        <input type="url" class="form-control"
                                                               name="telegram_url"
                                                               value="{{ $settings->telegram_url }}"
                                                               placeholder="{{ __('Linkedin url') }}...">
                                                        @error('telegram_url')
                                                        <span
                                                            class="help-block text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label">{{ __('WhatsApp Number') }}</label>
                                                        <input type="text" class="form-control"
                                                               name="whatsapp_number"
                                                               value="{{ $settings->whatsapp_number }}"
                                                               placeholder="{{ __('WhatsApp Number') }}...">
                                                        @error('whatsapp_number')
                                                        <span
                                                            class="help-block text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Google Settings --}}
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Google Login</h3>
                                        </div>
                                        <div class="card-body">

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label">{{ __('Google client id') }}</label>
                                                        <input type="text" class="form-control"
                                                               name="google_client_id"
                                                               value="{{ $settings->google_client_id }}"
                                                               placeholder="{{ __('Google client id') }}...">
                                                        @error('google_client_id')
                                                        <span
                                                            class="help-block text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label">{{ __('Google client secret') }}</label>
                                                        <input type="text" class="form-control"
                                                               name="google_client_secret"
                                                               value="{{ $settings->google_client_secret }}"
                                                               placeholder="{{ __('Google client secret') }}...">
                                                        @error('google_client_secret')
                                                        <span
                                                            class="help-block text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Facebook Settings --}}
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Facebook Login</h3>
                                        </div>
                                        <div class="card-body">

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label">{{ __('Facebook client id') }}</label>
                                                        <input type="text" class="form-control"
                                                               name="facebook_client_id"
                                                               value="{{ $settings->facebook_client_id }}"
                                                               placeholder="{{ __('Facebook client id') }}...">
                                                        @error('facebook_client_id')
                                                        <span
                                                            class="help-block text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label">{{ __('Facebook client secret') }}</label>
                                                        <input type="text" class="form-control"
                                                               name="facebook_client_secret"
                                                               value="{{ $settings->facebook_client_secret }}"
                                                               placeholder="{{ __('Facebook client secret') }}...">
                                                        @error('facebook_client_secret')
                                                        <span
                                                            class="help-block text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Apple Settings --}}
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Apple Login</h3>
                                        </div>
                                        <div class="card-body">

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label">{{ __('Apple client id') }}</label>
                                                        <input type="text" class="form-control"
                                                               name="apple_client_id"
                                                               value="{{ $settings->apple_client_id }}"
                                                               placeholder="{{ __('Apple client id') }}...">
                                                        @error('apple_client_id')
                                                        <span class="help-block text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label">{{ __('Apple client secret') }}</label>
                                                        <input type="text" class="form-control"
                                                               name="apple_client_secret"
                                                               value="{{ $settings->apple_client_secret }}"
                                                               placeholder="{{ __('Facebook client secret') }}...">
                                                        @error('apple_client_secret')
                                                        <span class="help-block text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Tawk Settings --}}
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Tawk Setting</h3>
                                        </div>
                                        <div class="card-body">

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="tawk_src"
                                                               class="form-label">{{ __('Tawk src url') }}</label>
                                                        <input type="text" class="form-control" name="tawk_src"
                                                               id="tawk_src" value="{{ $settings->tawk_src }}"
                                                               placeholder="{{ __('tawk src') }}...">
                                                        @error('tawk_src')
                                                        <span class="help-block text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="tawk_chat_url"
                                                               class="form-label">{{ __('Tawk direct chat url') }}</label>
                                                        <input type="text" class="form-control" name="tawk_chat_url"
                                                               id="tawk_chat_url" value="{{ $settings->tawk_chat_url }}"
                                                               placeholder="{{ __('tawk src') }}...">
                                                        @error('tawk_chat_url')
                                                        <span class="help-block text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Mailchimp Settings --}}
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Mailchimp Setting</h3>
                                        </div>
                                        <div class="card-body">

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="mailchimp_api_key"
                                                               class="form-label">{{ __('Mailchimp Api Key') }}</label>
                                                        <input type="text" class="form-control" name="mailchimp_api_key"
                                                               id="mailchimp_api_key"
                                                               value="{{ $settings->mailchimp_api_key }}"
                                                               placeholder="{{ __('Mailchimp Api key') }}...">
                                                        @error('mailchimp_api_key')
                                                        <span class="help-block text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="mailchimp_list_id"
                                                               class="form-label">{{ __('Mailchimp List ID') }}</label>
                                                        <input type="text" class="form-control" name="mailchimp_list_id"
                                                               id="mailchimp_list_id"
                                                               value="{{ $settings->mailchimp_list_id }}"
                                                               placeholder="{{ __('Mailchimp List ID') }}...">
                                                        @error('mailchimp_list_id')
                                                        <span class="help-block text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Payment Settings --}}
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Flutterwave Setting</h3>
                                        </div>
                                        <div class="card-body">

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="flutterwave_public_key"
                                                               class="form-label">{{ __('Flutterwave public key') }}</label>
                                                        <input type="text" class="form-control"
                                                               name="flutterwave_public_key"
                                                               id="flutterwave_public_key"
                                                               value="{{ $settings->flutterwave_public_key }}"
                                                               placeholder="{{ __('Flutterwave public key') }}...">
                                                        @error('flutterwave_public_key')
                                                        <span class="help-block text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="flutterwave_secret_key"
                                                               class="form-label">{{ __('Flutterwave secret key') }}</label>
                                                        <input type="text" class="form-control"
                                                               name="flutterwave_secret_key"
                                                               id="flutterwave_secret_key"
                                                               value="{{ $settings->flutterwave_secret_key }}"
                                                               placeholder="{{ __('Flutterwave secret key') }}...">
                                                        @error('flutterwave_secret_key')
                                                        <span class="help-block text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="flutterwave_encription_key"
                                                               class="form-label">{{ __('Flutterwave encription key') }}</label>
                                                        <input type="text" class="form-control"
                                                               name="flutterwave_encription_key"
                                                               id="flutterwave_encription_key"
                                                               value="{{ $settings->flutterwave_encription_key }}"
                                                               placeholder="{{ __('Flutterwave encription key') }}...">
                                                        @error('flutterwave_encription_key')
                                                        <span class="help-block text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Paypal Setting</h3>
                                        </div>
                                        <div class="card-body">

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="paypal_mode"
                                                               class="form-label">{{ __('Paypal Mode') }}</label>
                                                        <select name="paypal_mode" id="paypal_mode"
                                                                class="form-control form-select">
                                                            <option value="sandbox">Sandbox</option>
                                                            <option value="live">Live</option>
                                                        </select>
                                                        @error('paypal_mode')
                                                        <span class="help-block text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="paypal_client_id"
                                                               class="form-label">{{ __('Paypal Client ID') }}</label>
                                                        <input type="text" class="form-control" name="paypal_client_id"
                                                               id="paypal_client_id"
                                                               value="{{ $settings->paypal_client_id }}"
                                                               placeholder="{{ __('Paypal Client ID') }}...">
                                                        @error('paypal_client_id')
                                                        <span class="help-block text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="paypal_client_secret"
                                                               class="form-label">{{ __('Paypal Client Secret') }}</label>
                                                        <input type="text" class="form-control"
                                                               name="paypal_client_secret"
                                                               id="paypal_client_secret"
                                                               value="{{ $settings->paypal_client_secret }}"
                                                               placeholder="{{ __('Paypal Client Secret') }}...">
                                                        @error('paypal_client_secret')
                                                        <span class="help-block text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">App Download Links</h3>
                                        </div>
                                        <div class="card-body">


                                            <div class="mb-3">
                                                <label for="android_app_url"
                                                       class="form-label">{{ __('Android App Download Link') }}</label>
                                                <input type="url" class="form-control" name="android_app_url"
                                                       id="android_app_url"
                                                       value="{{ $settings->android_app_url }}"
                                                       placeholder="{{ __('Android App Download Link') }}...">
                                                @error('android_app_url')
                                                <span class="help-block text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="ios_app_url"
                                                       class="form-label">{{ __('ISO App Download Link') }}</label>
                                                <input type="url" class="form-control" name="ios_app_url"
                                                       id="ios_app_url" value="{{ $settings->ios_app_url }}"
                                                       placeholder="{{ __('ISO App Download Link') }}...">
                                                @error('ios_app_url')
                                                <span class="help-block text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Recaptcha settings</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="status_toggle"
                                                       class="form-label">{{ __('Recaptcha Enable') }}</label>
                                                <br>
                                                <input id="status_toggle" type="checkbox" name="recaptcha_enable"
                                                       {{ $settings->recaptcha_enable == 1 ? 'checked' : '' }} data-toggle="toggle"
                                                       data-on="{{ __('Active') }}"
                                                       data-off="{{ __('InActive') }}"
                                                       data-onstyle="success" data-offstyle="danger"
                                                       readonly>
                                            </div>
                                            <div class="row">
                                                <div class="mb-3 col-md-6">
                                                    <label for="recaptcha_site_key"
                                                           class="form-label">{{ __('Recaptcha Site Key') }}</label>
                                                    <input type="text" class="form-control"
                                                           name="recaptcha_site_key" id="recaptcha_site_key"
                                                           value="{{ $settings->recaptcha_site_key }}"
                                                           placeholder="{{ __('Recaptcha Site Key') }}...">
                                                    @error('recaptcha_site_key')
                                                    <span
                                                        class="help-block text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label for="recaptcha_secret_key"
                                                           class="form-label">{{ __('Recaptcha Secret Key') }}</label>
                                                    <input type="text" class="form-control"
                                                           name="recaptcha_secret_key" id="recaptcha_secret_key"
                                                           value="{{ $settings->recaptcha_secret_key }}"
                                                           placeholder="{{ __('Recaptcha Secret Key') }}...">
                                                    @error('recaptcha_secret_key')
                                                    <span
                                                        class="help-block text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center col-12">
                                <button type="submit" class="btn text-light px-4"
                                        id="updateButton">Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script>
        const form = document.getElementById("settingUpdate");
        const submitButton = form.querySelector("button[type='submit']");

        form.addEventListener("submit", function () {

            $("#updateButton").html(`
                <span id="">
                    <span class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"></span>
                    Updating Setting...
                </span>
            `);

            submitButton.disabled = true;

        });
    </script>
@endpush
