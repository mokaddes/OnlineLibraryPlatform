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
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}?v=1" />

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
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap-switch-button@1.1.0/css/bootstrap-switch-button.min.css" rel="stylesheet">
    <link href="https://adminlte.io/themes/v3/plugins/summernote/summernote-bs4.min.css" rel="stylesheet">
    <link href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap-switch-button@1.1.0/dist/bootstrap-switch-button.min.js"></script>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        {{-- header area --}}
        {{-- @include('admin.layouts.header') --}}

        @include('admin.layouts.admin_header')
        {{-- sidebar area --}}
        {{-- @include('admin.layouts.sidebar') --}}

        @include('admin.layouts.admin_sidebar')
        {{-- main content --}}
        @yield('content')
        {{-- footer --}}
        {{-- @include('admin.layouts.footer') --}}

        {{-- javascript --}}
        @include('admin.layouts.script')

    </div>
    {{-- toastr javascript --}}
    <script src="{{ asset('massage/toastr/toastr.js') }}"></script>
    {!! Toastr::message() !!}
    <script>
        @if($errors->any())
            @foreach($errors->all() as $error)
                toastr.error("{{ $error }}", 'Error');
            @endforeach
        @endif
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

    {{-- summernote --}}
    <script>
        $('.summernote').summernote({
            height: 200,
        })
        $('.select2').select2()
    </script>

    {{-- custom js area --}}
    @stack('script')

</body>

</html>
