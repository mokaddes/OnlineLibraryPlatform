<!-- ======================= header start  ============================ -->
<header class="header_section home_header">
    <div class="container">
        <div class="header_menu position-relative">
            <!-- desktop menu -->
            <nav class="navbar navbar-expand-lg bg-light p-0">
                <div class="container-fluid">
                    <a class="navbar-brand" href="{{ route('home') }}"><img src="{{ asset('assets/images/white-logo.png') }}" width="150"
                            alt="logo"></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#MobileWithBothOptions" aria-controls="MobileWithBothOptions">
                        <i class="las la-bars"></i>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto" style="margin-right:12rem !important">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('frontend.category') }}">By Location</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('frontend.category') }}">By Investment</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('frontend.category') }}">By Industry</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('frontend.franchises') }}">By Popular</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="header_right d-none d-lg-block">
                <ul>
                    <li><a href="{{ route('frontend.advertise') }}" class="btn btn-secondary">Advertise With Us</a></li>
                </ul>
            </div>
            <!-- mobile menu -->
            <div class="mobile_menu d-block d-lg-none">
                <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="MobileWithBothOptions"
                    aria-labelledby="MobileWithBothOptionsLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="MobileWithBothOptionsLabel">
                            <a href="{{ route('home') }}">
                                <img src="{{ asset('assets/images/logo.png') }}" width="120" alt="logo">
                            </a>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('frontend.category') }}">By Location</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('frontend.category') }}">By Investment</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('frontend.category') }}">By Industry</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('frontend.franchises') }}">By Popular</a>
                            </li>
                            <li class="mt-4">
                                <a href="{{ route('frontend.advertise') }}" class="btn btn-primary w-100">Advertise With Us</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- ======================= header end  ============================ -->
