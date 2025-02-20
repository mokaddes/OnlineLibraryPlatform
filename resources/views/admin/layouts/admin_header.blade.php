@php
    $notifications = getNotification('admin');
    $unread = $notifications ? $notifications->where('is_read', 0) : null;
@endphp
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light"
    style="border-bottom:none !important; margin-top:10px;">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item d-block d-lg-none">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item mt-1">
            <h4 class="m-0 ml-2">{{ $data['title'] ?? $title ?? 'Welcome Admin' }}</h4>
        </li>
    </ul>


    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        {{--<li class="nav-item">
            <a href="{{ url('/') }}" class="nav-link">
                <i class="fas fa-globe mt-2"></i>
            </a>
        </li>--}}
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="javascript:void(0)" aria-expanded="false">
                <i class="far fa-bell"></i>
                <span class="badge badge-primary navbar-badge notify_count">{{ $unread ? $unread->count() : 0 }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
                <span class="dropdown-item dropdown-header">{{ $unread ? $unread->count() : 0 }} Unread Notifications</span>
                <div class="dropdown-divider"></div>
                <div class="notification">
                    @foreach($unread as $msg)
                        <a href="{{ $msg->url }}" class="dropdown-item text-sm d-flex" onclick="readNotification({{ Auth::user()->id }}, {{$msg->id}})">
                            <div style="text-wrap: wrap">{{ $msg->title }}</div>
                            <div class="float-right text-muted text-sm ">{{ \Carbon\Carbon::parse($msg->created_at)->diffForHumans() }}</div>
                        </a>
                    @endforeach
                </div>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer text-sm text-success"  onclick="readNotification({{ Auth::user()->id }}, 'admin')">Clear All Notifications</a>
            </div>
        </li>
        <li class="nav-item dropdown">
            <img src="{{ getProfile(Auth::user()->image) }}" alt="{{ auth::user()->name }}" width="35"
                height="35" style="border-radius: 50%;">
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <span style="color:#000000;">{{ auth::user()->name }} &nbsp;<i class="fas fa-angle-down right"
                        style="color:#7C8DB5"></i></span>
            </a>
            <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                <a href="{{ route('admin.setting') }}" class="dropdown-item">{{ __('Profile & account') }}</a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('home') }}" class="dropdown-item">{{ __('Go to Website') }}</a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('logout') }}" class="dropdown-item text-danger"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                <form class="logout" id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                </form>
            </div>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
