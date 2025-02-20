<div class="modal fade" id="viewLoginModal" tabindex="-1" aria-labelledby="viewLoginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewLoginModalLabel">Login</h5>
                <button type="button" style="border: none;line-height: normal;" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="modal_body">
                    <div class="signin_form">
                        <form id="formLogin">
                            @csrf
                            <div class="text-center">
                                <img src="{{asset('assets/frontend/images/Login.gif')}}" width="300" class="img-fluid" alt="">
                            </div>
                            <div class="form-group mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="custom_form form-control @error('email') is-invalid @enderror"
                                    placeholder="name@example.com" >
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password"
                                        class="custom_form form-control border-end-0 @error('password') is-invalid @enderror" placeholder="Enter your password" >
                                        <span toggle="#password" style="@error('password') border-top-right-radius: 8px;border-bottom-right-radius: 8px; @enderror"
                                        class="fa fa-fw fa-eye field-icon toggle-password input-group-text @error('password') border-danger @enderror"></span>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn_primary w-100">Sign In</button>
                            </div>
                            <p class="have_account mb-5 text-center">
                                Don’t have an account? <a href="{{ route('user.registration') }}">Sign Up</a>
                            </p>

                            <div class="shape_divider mb-4 text-center">
                                <span>Or continue with:</span>
                            </div>

                            <div class="social_login text-center pb-4">
                                {{-- <a href="#" style="background-color: #584AF8;">
                                    <img src="{{asset('assets/frontend/images/social/facebook.png')}}" alt="Facebook">
                                    Facebook
                                </a> --}}
                                <a href="{{ route('google.login') }}" style="background-color: #FF7D7D;">
                                    <img src="{{asset('assets/frontend/images/social/google.png')}}" alt="Google">
                                    Google
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
