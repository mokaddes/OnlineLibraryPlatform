<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>@yield('title')</title>

    {{-- meta info --}}
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="author" content="Md. Mokaddes Hosain, Md. Rabin Mia">
    <meta property="fb:app_id" content="{{ '100087492087217' }}" />
    <meta name="robots" content="index,follow">
    <meta name="Developed By" content="Arobilo Limited" />
    <meta name="Developer" content="Md. Mokaddes Hosain" />
    <meta property="og:image:width" content="700" />
    <meta property="og:image:height" content="400" />
    <meta property="og:site_name" content="{{ $settings->site_name ?? 'Franchises Available Now' }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="WEBSITE" />
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    @if (View::hasSection('meta'))
        @yield('meta')
    @else
        <meta property="og:title" content="{{ $settings->site_name ?? config('app.name') }} - @yield('title')" />
        <meta property="og:description" content="" />
        <meta property="og:image" content="" />
    @endif

    {{-- style  --}}
    @include('frontend.layouts.style')

    {{-- toastr style  --}}
    {{-- <link rel="stylesheet" href="{{asset('massage/toastr/toastr.css')}}"> --}}

    {{-- custom style  --}}
    @stack('style')

</head>

<body>

    {{-- header section  --}}
    @include('frontend.layouts.header')

    {{-- content section  --}}
    @yield('content')

    {{-- footer section  --}}
    @include('frontend.layouts.footer')

    {{-- javascript  --}}
    @include('frontend.layouts.script')

    {{-- <script src="{{asset('massage/toastr/toastr.js')}}"></script>
        {!! Toastr::message() !!}
        <script>
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error('{{ $error }}','Error',{
                        closeButton:true,
                        progressBar:true,
                    });
                @endforeach
            @endif
        </script> --}}

    {{-- custom js area  --}}

    <script>
        function setRequestItem(id) {
            $.ajax({
                type: "get",
                url: "{{ route('frontend.getRequest') }}",
                data: {
                    id: id
                },
                success: function (data) {
                    console.log(data);
                    console.log(id);
                    $("#request_franchise").load(location.href + " #request_franchise>*", "");
                    $("#checked_" + id).load(location.href + " #checked_" + id + ">*", "");
                    $("#checked_" + id + "new").load(location.href + " #checked_" + id + "new>*", "");
                    $("#checked_" + id + "best").load(location.href + " #checked_" + id + "best>*", "");
                    $("#checked_" + id + "low_cost").load(location.href + " #checked_" + id + "low_cost>*", "");
                }
            });
        }
    </script>
    @stack('script')

</body>

</html>
