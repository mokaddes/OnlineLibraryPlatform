<!DOCTYPE html>
<html lang="en">
@php
    $setting = getSetting();
@endphp

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta charset="utf-8">
    <meta name="viewport"
    content="width=device-width, minimum-scale=1, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <link rel="icon" type="image/png" href="{{ asset($setting->favicon) }}" />
    <link rel="apple-touch-icon" type="image/png" href="{{ asset($setting->favicon) }}" />
    <title>@yield('title') - {{ config('app.name', 'Laravel') }}</title>

    {{-- style --}}
    @include('admin.layouts.style')

    {{-- toastr style --}}
    <link rel="stylesheet" href="{{ asset('massage/toastr/toastr.css') }}">
    {{-- custom style --}}
    <style>
        .select2-container--default .select2-selection--single {
            height: 43px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 34px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 42px !important;
        }
    </style>
    @stack('style')
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link href="https://adminlte.io/themes/v3/plugins/summernote/summernote-bs4.min.css" rel="stylesheet">
    <link href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        {{-- header area --}}
        {{-- @include('admin.layouts.header') --}}

        @include('admin.layouts.header')
        {{-- sidebar area --}}
        {{-- @include('admin.layouts.sidebar') --}}

        @include('admin.layouts.sidebar')
        {{-- main content --}}
        @yield('content')
        {{-- footer --}}
        @include('admin.layouts.footer')

        {{-- javascript --}}
        @include('admin.layouts.script')

    </div>
    {{-- toastr javascript --}}
    <script src="{{ asset('massage/toastr/toastr.js') }}"></script>
    {!! Toastr::message() !!}
    <script>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}', 'Error', {
                    closeButton: true,
                    progressBar: true,
                });
            @endforeach
        @endif

        function readNotification(userId, notifyId) {
            fetch(`/read-notification/${userId}/${notifyId}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                },
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }

                    return response.json();

                })
                .then(data => {
                    console.log(data);
                    $('.notify_count').text(data.count);
                    if(data.load == 'true') { location.reload(); }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                });
        }
    </script>
    <script>
        $('.dataTables').DataTable();
    </script>
    {{-- delete sweetalert2 --}}
    <script>
        $(document).on("click", "#deleteData", function(e) {
            e.preventDefault();
            var link = $(this).attr("href");
            Swal.fire({
                title: 'Are you want to delete?',
                text: "Once Delete, This will be Permanently Delete!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#8bc34a',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((willDelete) => {
                if (willDelete.isConfirmed) {
                    window.location.href = link;
                }
            })
        })
    </script>

    <script>
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>

    {{-- summernote --}}
    <script>
        $('.summernote').summernote({
            height: 200,
        })
        $('.select2').select2()
    </script>

    {{-- custom js area --}}
    @stack('script')

    @if(!Route::is('user.book.read'))
    <script type="text/javascript">
        var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
        var srcUrl = "{{ $setting->tawk_src  ?? 'https://embed.tawk.to/5d5db61beb1a6b0be608aec3/default' }}";
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
    @endif

    <!--End of Tawk.to Script-->

</body>

</html>
