<footer class="footer mt-auto">
    <div class="container">
        <div class="row gy-4 gy-sm-0 align-items-center">
            <div class="col-sm-4">
                <div class="copyright text-center text-sm-start">
                    <p class="mb-3">© [{{ explode(' ', $setting->site_name)[0] ?? '' }} {{ date('Y') }}] -
                        {{ $setting->site_name }}</p>
                    <a href="{{ route('privacy-policy') }}" class="text-sm">Privacy Policy</a>
                    <span class="text-white text-sm">|</span>
                    <a href="{{ route('terms-condition') }}" class="text-sm">Terms & Conditions</a>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="footer_logo text-center">
                    <img src="{{ asset($setting->site_logo) }}" class="img-fluid" alt="Logo">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="social_links d-flex flex-column justify-content-center">
                    <ul class="d-flex justify-content-center">
                        @if (isset($setting->instagram_url))
                            <li>
                                <a href="{{ asset($setting->instagram_url) }}" target="_blank">
                                    <img src="{{ asset('assets/frontend/images/social/instagram.png') }}"
                                         alt="instagram">
                                </a>
                            </li>
                        @endif
                        @if (isset($setting->twitter_url))
                            <li>
                                <a href="{{ asset($setting->twitter_url) }}" target="_blank">
                                    <img src="{{ asset('assets/frontend/images/social/twitter.png') }}"
                                         alt="twitter">
                                </a>
                            </li>
                        @endif
                        @if (isset($setting->youtube_url))
                            <li>
                                <a href="{{ asset($setting->youtube_url) }}" target="_blank">
                                    <img src="{{ asset('assets/frontend/images/social/youtube.png') }}"
                                         alt="youtube">
                                </a>
                            </li>
                        @endif
                        @if (isset($setting->facebook_url))
                            <li>
                                <a href="{{ asset($setting->facebook_url) }}" target="_blank">
                                    <img src="{{ asset('assets/frontend/images/social/facebook.png') }}"
                                         alt="facebook">
                                </a>
                            </li>
                        @endif

                        @if (isset($setting->linkedin_url))
                            <li>
                                <a href="{{ asset($setting->linkedin_url) }}" target="_blank">
                                    <img src="{{ asset('assets/frontend/images/social/linkedin.png') }}"
                                         alt="linkedin">
                                </a>
                            </li>
                        @endif
                    </ul>
                    <ul class="footer__mobile-app justify-content-center mt-3">
                        <li>
                            <a target="_blank" href="{{ asset($setting->android_app_url ?? '') }}" class="app">
                                <div class="app-logo">
                                    @include('common.google-play-icon')
                                </div>
                                <div class="d-flex flex-column ml-3">
                                    <span class="text-white">{{ __('Get it now') }}</span>
                                    <strong class="text-white">{{ __('Google Store') }}</strong>
                                </div>
                            </a>
                        </li>
                        <li>

                            <a target="_blank" href="{{ asset($setting->ios_app_url ?? '') }}" class="app ">
                                <div class="app-logo">
                                    @include('common.apple-icon')
                                </div>
                                <div class="d-flex flex-column ml-3">
                                    <span class="text-white">{{ __('Get it now') }}</span>
                                    <strong class="text-white">{{ __('App Store') }}</strong>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
