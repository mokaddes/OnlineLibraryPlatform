@php
    $settings   = DB::table('settings')->first();
    $user       = DB::table('user_plans')->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->first();
    $blogAccess = DB::table('packages')->where('id', $user->package_id)->first();
@endphp
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="d-flex justify-content-center">
        <a href="{{ route('user.dashboard') }}" class="brand-link text-center" id="sidebar_brand">
            <img src="{{ asset($settings->site_logo) }}" class="img-fluid" style="margin-top: -10%;">
        </a>
    </div>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('user.dashboard') }}" class="nav-link @yield('user')">
                        <img src="{{ asset('assets/uploads/user_logo/dashboard.png') }}" class="img-fluid nav-icon">
                        <p>{{ __('Dashboard') }}</p>
                    </a>
                </li>


                <li class="nav-item @yield('books')">
                    <a href="#" class="nav-link">
                        <img src="{{ asset('assets/uploads/user_logo/library.png') }}" class="img-fluid nav-icon">
                        <p>
                            @if (auth()->user()->role_id == 2)
                                My Books
                            @else(auth()->user()->role_id == 2)
                                Library
                            @endif
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>

                    <ul class="pt-1 nav nav-treeview @yield('books')">
                        @if (auth()->user()->role_id == 1)
                            <li class="nav-item">
                                <a href="{{ route('user.book.index') }}" class="nav-link @yield('all_book')">
                                    <i class="fa fa-circle nav-icon" style="font-size: 10px;"></i>
                                    <p>All Books</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('user.book.favourite') }}" class="nav-link @yield('favourite_book')">
                                    <i class="fa fa-circle nav-icon" style="font-size: 10px;"></i>
                                    <p>Favourite Books</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('user.book.borrowed') }}" class="nav-link @yield('borrowed_book')">
                                    <i class="fa fa-circle nav-icon" style="font-size: 10px;"></i>
                                    <p>Borrowed Books</p>
                                </a>
                            </li>
                        @elseif(auth()->user()->role_id == 2)
                            <li class="nav-item">
                                <a href="{{ route('author.books.index') }}" class="nav-link @yield('my_books')">
                                    <i class="fa fa-circle nav-icon" style="font-size: 10px;"></i>
                                    <p>My Books</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('author.books.pending') }}" class="nav-link @yield('pending_books')">
                                    <i class="fa fa-circle nav-icon" style="font-size: 10px;"></i>
                                    <p>Pending Books</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('author.books.declined') }}" class="nav-link @yield('declined_books')">
                                    <i class="fa fa-circle nav-icon" style="font-size: 10px;"></i>
                                    <p>Declined Books</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('author.books.readers') }}" class="nav-link @yield('my_readers')">
                                    <i class="fa fa-circle nav-icon" style="font-size: 10px;"></i>
                                    <p>My Readers</p>
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route('user.book.borrowed') }}" class="nav-link @yield('borrowed_book')">
                                    <i class="fa fa-circle nav-icon" style="font-size: 10px;"></i>
                                    <p>{{ auth()->user()->role_id == 1 ? 'Borrowed' : 'Issued' }} Books</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('user.book.favourite') }}" class="nav-link @yield('favourite_book')">
                                    <i class="fa fa-circle nav-icon" style="font-size: 10px;"></i>
                                    <p>Favourite Books</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                @if (applyPromo())
                    <li class="nav-item">
                        <a href="{{ route('user.promo-code.index') }}" class="nav-link @yield('promo')">
                            <img src="{{ asset('assets/uploads/user_logo/promo.png') }}" class="img-fluid nav-icon">
                            <p>{{ __('Apply PromoCode') }}</p>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->role_id == 2)
                    <li class="nav-item">
                        <a href="{{ route('user.order.index') }}" class="nav-link @yield('order')">
                            <img src="{{ asset('assets/uploads/user_logo/checklist.png') }}" class="img-fluid nav-icon">
                            <p>{{ __('Order list') }}</p>
                        </a>
                    </li>
                @endif

                <li class="nav-item">
                    <a href="{{ route('user.ticket.index') }}" class="nav-link @yield('ticket')">
                        <img src="{{ asset('assets/uploads/user_logo/ticket.png') }}" class="img-fluid nav-icon">
                        <p>{{ __('Support Tickets') }}</p>
                    </a>
                </li>

                @if (auth()->user()->role_id == 1)
                    <li class="nav-item">
                        <a href="{{ route('user.club.index') }}" class="nav-link @yield('club')">
                            <img src="{{ asset('assets/uploads/user_logo/club.png') }}" class="img-fluid nav-icon">
                            <p>{{ __('Club') }}</p>
                        </a>
                    </li>
                @endif

                @if (auth()->user()->role_id == 1 && $blogAccess->blog == '3')
                    <li class="nav-item">
                        <a href="{{ route('user.blog.index') }}" class="nav-link @yield('blog')">
                            <img src="{{ asset('assets/uploads/user_logo/blog.png') }}" class="img-fluid nav-icon">
                            <p>{{ __('Blog') }}</p>
                        </a>
                    </li>
                @endif

                @if (auth()->user()->role_id == 1)
                    <li class="nav-item">
                        <a href="{{ route('user.transaction.index') }}" class="nav-link @yield('transaction')">
                            <img src="{{ asset('assets/uploads/user_logo/transaction.png') }}"
                                class="img-fluid nav-icon">
                            <p>{{ __('Transactions') }}</p>
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a href="@if (auth()->user()->role_id == 3 or auth()->user()->role_id == 2) {{ route('user.settings.index', ['tab' => 2]) }} @else {{ route('user.settings.index') }} @endif"
                        class="nav-link @yield('settings')">
                        <img src="{{ asset('assets/uploads/user_logo/settings.png') }}" class="img-fluid nav-icon">
                        <p>{{ __('Settings') }}</p>
                    </a>
                </li>

                {{-- <li class="nav-item">
                    <a href="{{ route('admin.book.index') }}" class="nav-link @yield('books')">
                        <i class="fa fa-book"></i>
                        {{ __('My Books') }}
                    </a>
                </li> --}}

                {{-- <li class="nav-item">
                    <a href="{{ route('admin.industry.index') }}"
                        class="nav-link {{ Request::routeIs('admin.industry.index') ? 'active' : '' }}">
                        <i class="fa fa-building"></i>
                        {{ __('Industry') }}
                    </a>
                </li> --}}
                {{-- <li class="nav-item">
                    <a href="{{ route('admin.location.index') }}"
                        class="nav-link {{ Request::routeIs('admin.location.index') ? 'active' : '' }}">
                        <i class="fa fa-location-dot"></i>
                        {{ __('Location') }}
                    </a>
                </li> --}}
                {{-- <li class="nav-item">
                    <a href="{{ route('admin.investment.index') }}"
                        class="nav-link {{ Request::routeIs('admin.investment.index') ? 'active' : '' }}">
                        <i class="fa fa-dollar"></i>
                        {{ __('Investment') }}
                    </a>
                </li> --}}
                {{-- <li class="nav-item">
                    <a href="{{ route('admin.franchises.index') }}"
                        class="nav-link {{ Request::routeIs('admin.franchises.index') ? 'active' : '' }} @yield('franchises_create')">
                        <i class="fa fa-file"></i>
                        {{ __('All Listing') }}
                    </a>
                </li>
                <li class="nav-item @yield('blogDropdown')">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-file-pen"></i>
                        <p> Blog <i class="fas fa-angle-left right"></i> </p>
                    </a>
                    <ul class="nav nav-treeview @yield('blockDropdownMenu')">
                        @if (Auth::user()->can('admin.blog-category.index'))
                            <li class="nav-item">
                                <a href="{{ route('admin.blog-category.index') }}" class="nav-link @yield('blog-category')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Blog Category</p>
                                </a>
                            </li>
                        @endif

                        @if (Auth::user()->can('admin.blog-post.index'))
                            <li class="nav-item">
                                <a href="{{ route('admin.blog-post.index') }}" class="nav-link  @yield('blog-post')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Blog Post</p>
                                </a>
                            </li>
                        @endif

                    </ul>
                </li>

                @if (Auth::user()->can('admin.category.index'))
                    <li class="nav-item">
                        <a href="{{ route('admin.category.index') }}" class="nav-link @yield('category')">
                            <i class="fa fa-address-book"></i>
                            {{ __('Category') }}
                        </a>
                    </li>
                @endif

                @if (Auth::user()->can('admin.subcategory.index'))
                    <li class="nav-item">
                        <a href="{{ route('admin.subcategory.index') }}" class="nav-link @yield('subcategory')">
                            <i class="fa fa-address-book"></i>
                            {{ __('Sub Category') }}
                        </a>
                    </li>
                @endif


                @if (Auth::user()->can('admin.contact.index'))
                    <li class="nav-item">
                        <a href="{{ route('admin.contact.index') }}" class="nav-link  @yield('contact')">
                            <i class="fa fa-address-book"></i>
                            {{ __('Contact') }}
                        </a>
                    </li>
                @endif

                @if (Auth::user()->can('admin.faq.index'))
                    <li class="nav-item">
                        <a href="{{ route('admin.faq.index') }}" class="nav-link @yield('faq')">
                            <i class="fa fa-question"></i>
                            {{ __('Faq') }}
                        </a>
                    </li>
                @endif

                @if (Auth::user()->can('admin.cpage.index'))
                    <li class="nav-item">
                        <a href="{{ route('admin.cpage.index') }}" class="nav-link  @yield('cpage')">
                            <i class=" fa fa-book"></i>
                            {{ __('Custom page') }}
                        </a>
                    </li>
                @endif

                <li class="nav-item">
                    <a href="{{ route('admin.cpage.index') }}"
                        class="nav-link {{ Request::routeIs('admin.cpage.index') ? 'active' : '' }} @yield('cpage_create')">
                        <i class=" fa fa-award"></i>
                        {{ __('Brand') }}
                    </a>
                </li>

                @if (Auth::user()->can('admin.brand.index'))
                    <li class="nav-item">
                        <a href="{{ route('admin.brand.index') }}" class="nav-link  @yield('brand')">
                            <i class=" fa fa-award"></i>
                            {{ __('Brand') }}
                        </a>
                    </li>
                @endif



                <li class="nav-item ">
                    <a href="javascript:;" class="nav-link ">
                        <i class="nav-icon far fa-envelope"></i>
                        <p>
                            Newsletter
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.newsletter.list') }}"
                                class="nav-link @yield('newsletter')">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Email List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Send Mail</p>
                            </a>
                        </li>

                    </ul>
                </li>


                @if (Auth::user()->can('admin.customer.index'))
                    <li class="nav-item">
                        <a href="{{ route('admin.customer.index') }}" class="nav-link @yield('customer')">
                            <i class=" fa fa-users"></i>
                            {{ __('Customer') }}
                        </a>
                    </li>
                @endif

                <li class="nav-item @yield('location_menu')">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon far fa-envelope"></i>
                        <p>Location<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (Auth::user()->can('admin.country.index'))
                            <li class="nav-item">
                                <a href="{{ route('admin.country.index') }}" class="nav-link @yield('country')">
                                    <i class="fas fa-cog nav-icon"></i>
                                    <p>Country</p>
                                </a>
                            </li>
                        @endif
                        @if (Auth::user()->can('admin.region.index'))
                            <li class="nav-item">
                                <a href="{{ route('admin.region.index') }}" class="nav-link @yield('region')">
                                    <i class="fas fa-globe nav-icon"></i>
                                    <p>Region</p>
                                </a>
                            </li>
                        @endif
                        @if (Auth::user()->can('admin.city.index'))
                            <li class="nav-item">
                                <a href="{{ route('admin.city.index') }}" class="nav-link @yield('city')">
                                    <i class="fas fa-globe nav-icon"></i>
                                    <p>city</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>

                --}}

                {{-- <li class="nav-item @yield('settings_menu') ">
                    <a href="{{ route('admin.settings') }}" class="nav-link ">
                        <i class="nav-icon fa fa-cogs"></i>
                        <p>Settings <i class="fas fa-angle-left right"></i></p>
                    </a>

                    <ul class="nav nav-treeview @yield('settings_menu')">
                        <li class="nav-item">
                            <a href="{{ route('admin.settings.general') }}" class="nav-link @yield('general')">
                                <i class="fas fa-cog nav-icon"></i>
                                <p>General Settings</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.settings.language') }}" class="nav-link @yield('language')">
                                <i class="fas fa-globe nav-icon"></i>
                                <p>Language</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.settings.Smtp.mail') }}" class="nav-link @yield('smtp')">
                                <i class="fas fa-envelope nav-icon"></i>
                                <p>SMTP</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.settings.Currency.index') }}" class="nav-link @yield('currency')">
                                <i class="fas fa-dollar-sign nav-icon"></i>
                                <p>Currency</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.settings.MobileApp.index') }}" class="nav-link @yield('mobile_app')">
                                <i class="fas fa-mobile nav-icon"></i>
                                <p>Mobile App Config</p>
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="nav-item"><a href="{{ route('admin.user.index') }}" class="nav-link @yield('admin-user')"><i
                            class=" fa fa-user"></i>
                        {{ __('Admin User & Role') }}</a></li>

                <li class="nav-item"><a href="{{ route('admin.roles.index') }}" class="nav-link @yield('admin-roles')"><i
                            class=" fa fa-user"></i> {{ __('Admin Roles') }}</a>
                </li>

                <li class="nav-item"><a href="{{ route('admin.permissions.index') }}"
                        class="nav-link @yield('admin-permissions')"><i class=" fa fa-user"></i>
                        {{ __('Admin permissions') }}</a></li> --}}


            </ul>
        </nav>
    </div>
</aside>
