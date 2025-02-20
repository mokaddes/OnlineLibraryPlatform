@php
    $settings = DB::table('settings')->first();
@endphp

    <!-- Main Sidebar Container -->
<aside class="main-sidebar" id="admin_sidebar">
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <img src="{{asset($settings->site_logo)}}" class="img-fluid" style="max-width: 60px;">
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav>
            <ul class="nav nav-pills nav-sidebar flex-column pb-5" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link @yield('dashboard')">
                        <i class="fas fa-home" style="color: #7C8DB5;"></i>
                        {{ __('Dashboard') }}
                    </a>

                </li>
                <li class="nav-item @yield('library_menu')">
                    <a href="#" class="nav-link @yield('library_menu')">
                        <i class="fas fa-book" style="color: #7C8DB5;"></i>
                        <p>{{ __('Library') }} <i class="fas fa-angle-left right"></i></p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.book.index') }}" class="nav-link @yield('books')">
                                &emsp;<p>{{ __('Books') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.category.index') }}" class="nav-link @yield('category')">
                                &emsp;<p>{{ __('Category') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.user.index') }}" class="nav-link @yield('user')">
                        <i class="far fa-user" style="color: #7C8DB5;"></i>
                        {{ __('Users') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.institute.index') }}" class="nav-link @yield('admin-institute')">
                        <i class="far fa-user" style="color: #7C8DB5;"></i>
                        {{ __('Institution') }}
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.ticket.index') }}" class="nav-link @yield('ticket')">
                        <i class="fas fa-receipt" style="color: #7C8DB5;"></i>
                        {{ __('Support Tickets') }}
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.package.index') }}" class="nav-link @yield('package')">
                        <i class="fas fa-gift" style="color: #7C8DB5;"></i>
                        {{ __('Packages') }}
                    </a>
                </li>
                <li class="nav-item @yield('promo-code')">
                    <a href="#" class="nav-link @yield('promo-code')">
                        <i class="fas fa-book" style="color: #7C8DB5;"></i>
                        <p>{{ __('PromoCode') }} <i class="fas fa-angle-left right"></i></p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.book-promo.index') }}" class="nav-link @yield('promo-book')">
                                &emsp;<p>{{ __('Book PromoCode') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.package-promo.index') }}" class="nav-link @yield('promo-package')">
                                &emsp;<p>{{ __('Package PromoCode') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.transaction.index') }}" class="nav-link @yield('transaction')">
                        <i class="fas fa-exchange-alt" style="color: #7C8DB5;"></i>
                        {{ __('Transactions') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.payment.request.index') }}" class="nav-link @yield('paymentRequest')">
                        <i class="fas fa-dollar" style="color: #7C8DB5;"></i>
                        {{ __('Payment Request') }}
                    </a>
                </li>
                <li class="nav-item @yield('pages_menu')">
                    <a href="#" class="nav-link @yield('pages_menu')">
                        <i class="far fa-file" style="color: #7C8DB5;"></i>
                        <p>{{ __('Pages') }} <i class="fas fa-angle-left right"></i></p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.cpage.home') }}" class="nav-link @yield('home')">
                                &emsp;<p>{{ __('Home page') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.cpage.privacy_policy') }}"
                               class="nav-link @yield('privacy_policy')">
                                &emsp;<p>{{ __('Privacy Policy') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.cpage.terms_conditions') }}"
                               class="nav-link @yield('terms_conditions')">
                                &emsp;<p>{{ __('Terms & Conditions') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.club.index') }}" class="nav-link @yield('club')">
                        <i class="fa-solid fa-people-group" style="color: #7C8DB5;"></i>
                        {{ __('Clubs') }}
                    </a>
                </li>

                <li class="nav-item @yield('blog_menu')">
                    <a href="#" class="nav-link @yield('blog_menu')">
                        <i class="fas fa-edit" style="color: #7C8DB5;"></i>
                        <p>{{ __('Blog') }} <i class="fas fa-angle-left right"></i></p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.blog.category.index') }}" class="nav-link @yield('blogCategory')">
                                &emsp;<p>{{ __('Category') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.blog.index') }}" class="nav-link @yield('blog')">
                                &emsp;<p>{{ __('Blog') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.blog.comment.index') }}" class="nav-link @yield('blogComments')">
                                &emsp;<p>{{ __('Comments') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item @yield('forum_menu')">
                    <a href="#" class="nav-link @yield('forum_menu')">
                        <i class="fas fa-edit" style="color: #7C8DB5;"></i>
                        <p>{{ __('Forum') }} <i class="fas fa-angle-left right"></i></p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.forum.category.index') }}"
                               class="nav-link @yield('forumCategory')">
                                &emsp;<p>{{ __('Category') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.forum.index') }}" class="nav-link @yield('forumQuestions')">
                                &emsp;<p>{{ __('Questions') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.forum.comment.index') }}" class="nav-link @yield('forumComments')">
                                &emsp;<p>{{ __('Comments') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.user.report') }}" class="nav-link @yield('forumReport')">
                                &emsp;<p>{{ __('Reports') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item @yield('admin_management')">
                    <a href="#" class="nav-link @yield('admin_management')">
                        <i class="fas fa-user-lock" style="color: #7C8DB5;"></i>
                        <p>{{ __('Admin Management') }} <i class="fas fa-angle-left right"></i></p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.admins.index') }}" class="nav-link @yield('admin-user')">
                                &emsp;<p>{{ __('Admins') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.roles.index') }}" class="nav-link @yield('admin-roles')">
                                &emsp;<p>{{ __('Roles') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.permissions.index') }}" class="nav-link @yield('admin-permissions')">
                                &emsp;<p>{{ __('Permissions') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- <li class="nav-item @yield('admin_management') ">
                    <a href="{{ route('admin.settings') }}" class="nav-link d_nav">
                        <i class="nav_icon fas fa-user-lock" style="margin-left: 5px;"></i>
                        <p>
                            Admin Management
                            <i class="fas fa-angle-left right mt-1"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview @yield('admin_management')">
                        <li class="nav-item">
                            <a href="{{ route('admin.admins.index') }}" class="nav-link @yield('admin-user')">
                                <i class="nav_icon_group fa fa-circle"></i>
                                <p>{{ __('Admins') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.roles.index') }}" class="nav-link @yield('admin-roles')">
                                <i class="nav_icon_group fa fa-circle"></i>
                                <p>{{ __('Roles') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.permissions.index') }}"
                               class="nav-link @yield('admin-permissions')">
                                <i class="nav_icon_group fa fa-circle"></i>
                                <p>{{ __('Permissions') }}</p>
                            </a>
                        </li>
                    </ul>
                </li> --}}

                {{-- <li class="nav-item">
                    <a href="{{ route('admin.blog.index') }}" class="nav-link @yield('blog')">
                        <i class="fas fa-edit" style="color: #7C8DB5;"></i>
                        {{ __('Blog') }}
                    </a>
                </li> --}}

                <li class="nav-item">
                    <a href="{{ route('admin.faq.index') }}" class="nav-link @yield('faq')">
                        <i class="far fa-question-circle" style="color: #7C8DB5;"></i>
                        {{ __('Faqs') }}
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.contact.index') }}" class="nav-link @yield('contact')">
                        <i class="fas fa-address-book" style="color: #7C8DB5;"></i>
                        {{ __('Contact') }}
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.push-notification.index') }}" class="nav-link @yield('push')">
                        <i class="fas fa-globe" style="color: #7C8DB5;"></i>
                        {{ __('Push Notification') }}
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.settings') }}" class="nav-link @yield('setting')">
                        <i class="fas fa-cog" style="color: #7C8DB5;"></i>
                        {{ __('Settings') }}
                    </a>
                </li>

                {{-- @if (Auth::user()->can('admin.contact.index'))
                    <li class="nav-item">
                        <a href="{{ route('admin.contact.index') }}" class="nav-link  @yield('contact')">
                            <i class="fa fa-address-book"></i>
                            {{ __('Contact') }}
                        </a>
                    </li>
                @endif --}}

            </ul>
        </nav>
    </div>
</aside>
