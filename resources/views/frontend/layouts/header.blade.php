<style>

</style>
<div class="top-bg">
    <!-- ======================= header start  ============================ -->
    <header class="header_sec header-bg position-relative">
        <div class="container">
            <nav class="navbar-expand-lg p-0">
                <div class="container-fluid p-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="logo d-flex align-items-center justify-content-between">
                            <a class="navbar-brand" href="{{ route('home') }}">
                                <img src="{{ asset($setting->site_logo) }}" alt="Logo" style="margin-bottom: 10px;">
                            </a>
                            <button class="navbar-toggler d-block d-lg-none" type="button"
                                data-bs-toggle="offcanvas" data-bs-target="#mobileNav" aria-controls="mobileNav">
                                <i class="fa fa-bars"></i>
                            </button>
                        </div>
                        <div class="d-none main-menu d-lg-block">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <li class="nav-item"><a class="nav-link {{ Request::routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>
                                <li class="nav-item"><a class="nav-link {{ Request::routeIs('frontend.faq') ? 'active' : '' }}" href="{{ route('frontend.faq') }}">Faq's</a></li>
                                <li class="nav-item"><a class="nav-link {{ Request::routeIs('frontend.pricing') ? 'active' : '' }}" href="{{ route('frontend.pricing') }}">Pricing</a></li>
                                <li class="nav-item"><a class="nav-link {{ Request::routeIs('frontend.blogs') || Request::routeIs('frontend.blogs.details') ? 'active' : '' }}" href="{{ route('frontend.blogs') }}">Blog</a></li>
                                <li class="nav-item"><a class="nav-link {{ Request::routeIs('frontend.forum') || Request::routeIs('frontend.forum.details') ? 'active' : '' }}" href="{{ route('frontend.forum') }}">Forum</a></li>
                                <li class="nav-item"><a class="nav-link {{ Request::routeIs('frontend.contact') ? 'active' : '' }}" href="{{ route('frontend.contact') }}">Contact Us</a></li>
                            </ul>
                        </div>
                        <div class="right-side-menu">
                            <ul class="d-flex">
                                @auth
                                <li class="nav-item ms-1 ms-3 user_dashboard">
                                    <div class="btn-group">
                                        <button type="button" class="dropdown-toggle border shadow-none rounded-circle"
                                            data-bs-toggle="dropdown" aria-expanded="false" id="dropdown-toggle">
                                            <img src="{{ getProfile(Auth::user()->image) }}" alt="user" width="50"
                                                height="50" class="rounded-circle">
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end" id="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('user.dashboard') }}">
                                                    <i class="fa fa-user"></i>
                                                    Dashboard
                                                </a>
                                            </li>
                                            {{-- <li>
                                                <a class="dropdown-item" href="{{ route('user.subscription') }}">
                                                    <i class="fa fa-user"></i>
                                                    My Subscription
                                                </a>
                                            </li> --}}
                                            <li>
                                                <a class="dropdown-item" href="{{ route('user.logout') }}">
                                                    <i class="fa fa-sign-out-alt"></i>
                                                    Logout
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                @else
                                <li class="nav-item me-3">
                                    <a class="nav-link d-flex align-items-center {{ Request::routeIs('user.login') ? 'active' : '' }}" href="{{ route('user.login') }}">
                                        <img src="{{asset('assets/frontend/images/login.png')}}" class="me-1" width="18" alt="Login">
                                        <span>Sign In</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center {{ Request::routeIs('user.registration') ? 'active' : '' }}" href="{{ route('user.registration') }}">
                                        <img src="{{asset('assets/frontend/images/add-user.png')}}" class="me-1" width="18" alt="Login">
                                        <span>Register</span>
                                    </a>
                                </li>
                                @endauth
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>


            <!-- mobile menu -->
            <div class="mobile_nav">
                <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileNav"
                    aria-labelledby="mobileNavLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="mobileNavLabel">
                            <a href="index.html"> <img src="{{asset('assets/frontend/images/logo.png')}}" alt="Logo"></a>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <div class="dropdown">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('frontend.faq') }}">Faq's</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('frontend.pricing') }}">Pricing</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('frontend.blogs') }}">Blog</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('frontend.forum') }}">Forum</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('frontend.contact') }}">Contact Us</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- ======================= header end  ============================ -->
</div>
