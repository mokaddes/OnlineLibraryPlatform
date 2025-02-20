@php
    $notifications = getNotification('user');
    $unread = $notifications ? $notifications->where('is_read', 0) : null;
@endphp
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"
                                                                                 style="color: #7B057B;font-size: 23px;"></i></a>
        </li>
        {{-- <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('home') }}" target="_blank" class="nav-link">
                <i class="fas fa-globe fa-2"></i>
            </a>
        </li> --}}
        {{-- <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('admin.cacheClear') }}" class="nav-link">
                <i class="fas fa-broom"></i>
            </a>
        </li> --}}
        <li class="nav-item">
            <a class="nav-link" href="javascript:void(0)">
                @if(intval(Auth::user()->role_id) == 2)
                    <span class="badge bg-success p-2">Author</span>
                @elseif(intval(Auth::user()->role_id) ==3)
                    <span class="badge bg-primary p-2">Institute</span>
                @else
                    <span class="badge bg-info p-2"></span>
                @endif
            </a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        @if(Route::is('user.book.read'))
        <li class="nav-item ">
            <a class="nav-link d-flex justify-content-between align-items-center" href="javascript:void(0)">
                <small class="text-nowrap text-success">Page Time:</small> <small id="pageTimerDisplay" class="ml-1 text-success">00:00</small>
            </a>
        </li>
        <li class="nav-item ">
            <a class="nav-link d-flex justify-content-between align-items-center" href="javascript:void(0)">
                <small class="text-nowrap text-danger">Spend Time:</small> <small id="safeTimerDisplay" class="ml-1 text-danger">00:00</small>
            </a>
        </li>
        @endif
        <li class="nav-item">
            <a class="nav-link" href="{{ route('frontend.forum') }}">
                <span>Forum</span>
            </a>
        </li>
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
                <a href="#" class="dropdown-item dropdown-footer text-sm text-success"  onclick="readNotification({{ Auth::user()->id }}, 'all')">Clear All Notifications</a>
            </div>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <span class="image">
                    <img src="{{ getProfile(Auth::user()->image) }}" alt="{{ auth::user()->name }}"
                         class="rounded-circle" width="30" height="30"> &nbsp;
                    <span class="d-none d-sm-inline" style="color:#000000;">{{Auth::user()->name}} &nbsp;<i class="fas fa-angle-down right" style="color:#7C8DB5"></i></span>
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                <a href="{{ route('user.settings.index', ['tab' => 2]) }}" class="dropdown-item">{{ __('Profile & account') }}</a>
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
