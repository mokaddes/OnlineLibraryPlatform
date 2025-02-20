@php
    $settings = getSetting();
@endphp

<!DOCTYPE html>
<html lang="{{ app()->getLocale() ?? 'en' }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $settings->site_name }}</title>
    <link rel="stylesheet" href="{{ asset('massage/toastr/toastr.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/css/adminlte.min.css') }}">

</head>


<body class="login-page" style="background-color: #ebedf4;">
    <!--  SignIn  -->
    <div class="signin_sec login-box">
        <div class="container">
            <div class="card">
                <div class="card-body login-card-body">
                    <form method="POST" action="{{ route('admin.login') }}">
                        @csrf
                        <div>
                            <div class="mb-5 text-center">
                                <a href="{{ route('home') }}">
                                    <img src="{{ getLogo($settings->site_logo) }}" width="150" alt="logo">
                                </a>
                            </div>

                            <div class="mb-3">
                                <div class="form-group">
                                    <label for="email" class="form-label">{{ __('Admin Email') }}</label><br>
                                    <input type="email" name="email" id="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}" placeholder="Enter your email" required>
                                </div>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <label for="password" class="form-label">{{ __('Admin Password') }}</label><br>
                            <div class="input-group mb-3">
                                <input type="password" name="password" id="password" class="form-control 
                                @error('password') is-invalid @enderror" placeholder="Enter your password" required>
                                <div class="input-group-append">
                                    <span toggle="#password" class="toggle-password input-group-text">
                                        <i class="icon fa fa-fw fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            {{-- <div class="form-group mb-3">
                                <label for="password" class="form-label">{{ __('Admin Password') }}</label><br>
                                <div class="input-group">
                                    <input type="password" name="password" id="password"
                                        class="form-control
                                        @error('password') is-invalid @enderror"
                                        placeholder="Enter your password" required>
                                    <span toggle="#password"
                                        class="fa fa-fw fa-eye field-icon confirm_pass input-group-text"></span>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div> --}}
                            <button type="submit" class="btn btn-primary rounded w-100">{{ __('Sign In') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('massage/toastr/toastr.js') }}"></script>
    <script>
        $(document).ready(function () {
            $(".toggle-password").click(function () {
                var input = $($(this).attr("toggle"));
                if (input.attr("type") === "password") {
                    input.attr("type", "text");
                } else {
                    input.attr("type", "password");
                }
                $(".icon").toggleClass("fa-eye fa-eye-slash");
            });
        });
    </script>
    <script>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}", 'Error');
            @endforeach
        @endif
    </script>
</body>

</html>
