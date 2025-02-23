<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@php
    $setting = getSetting();
@endphp

<head>
    <title>@yield('title') - {{ config('app.name', 'Laravel') }}</title>

    {{-- meta info --}}
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="author" content="Md. Mokaddes Hosain, Md. Rabin Mia">
    <meta property="fb:app_id" content="{{ '100087492087217' }}" />
    <meta name="robots" content="index,follow">
    <meta name="Developed By" content="Md. Mokaddes Hosain" />
    <meta name="Developer" content="Md. Mokaddes Hosain" />
    <meta property="og:image:width" content="700" />
    <meta property="og:image:height" content="400" />
    <meta property="og:site_name" content="{{ $setting->site_name ?? 'Online Library' }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="WEBSITE" />
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    @if (View::hasSection('meta'))
        @yield('meta')
    @else
        <meta property="og:title" content="{{ $setting->site_name ?? config('app.name') }} - @yield('title')" />
        <meta property="og:image" content="{{ asset($setting->seo_image) }}" />
        <meta property="og:description" content="{{ $setting->seo_meta_description	 }}" />
        <meta property="og:keywords" content="{{ $setting->seo_keywords }}" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endif

    {{-- style  --}}

    {{-- toastr style --}}
    <link rel="icon" type="image/png" href="{{ asset($setting->favicon) }}" />
    <link rel="apple-touch-icon" type="image/png" href="{{ asset($setting->favicon) }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    {{-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> --}}
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap-switch-button@1.1.0/css/bootstrap-switch-button.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap-switch-button@1.1.0/dist/bootstrap-switch-button.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">


    {{-- custom style  --}}
    @include('frontend.layouts.style')
    <link rel="stylesheet" href="{{ asset('massage/toastr/toastr.css') }}">
    <style>
        .footer__mobile-app {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            margin-bottom: 20px;
        }
        .footer__mobile-app .app {
            border: 1px solid #ffffff;
        }
        .footer__mobile-app .app:hover {
            border: 1px solid #111111;
        }

        .footer__mobile-app .app-logo {
            margin-right: 12px;
        }

        .footer__mobile-app .app {
            margin-left: 12px;
            position: relative;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -ms-flex-negative: 0;
            flex-shrink: 0;
            padding: 8px 16px;
            border-radius: 8px;
            border: 1px solid #ebeef7;
            width: 170px;
        }
        .navbar-brand img{
            width: 160px;
            height: 80px;
        }
    </style>
    @stack('style')
    <input type="hidden" name="baseurl" id="baseurl" value="{{ url('/') }}" />

</head>

<body>

    {{-- header section  --}}
    <div>
        @include('frontend.layouts.header')
    </div>



    {{-- content section  --}}
    <div style="min-height: 80vh;">
        @yield('content')
    </div>


    {{-- footer section  --}}
    @include('frontend.layouts.footer')

    {{-- javascript  --}}
    @include('frontend.layouts.script')



    {{-- custom js area  --}}
    @stack('script')

    <script type="text/javascript">
        var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
        var srcUrl = "{{ $setting->tawk_src  ?? 'https://embed.tawk.to/65632cb1ba9fcf18a80ef8f1/1hg5md5gj' }}";
        console.log(srcUrl);
        console.log('Your Tawk');
        (function () {
            var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = srcUrl;
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>

</body>

</html>
