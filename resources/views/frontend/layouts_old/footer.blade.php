<!-- ======================= footer start ============================ -->
@if (!Route::is('frontend.request_info'))

<section class="footer_top sticky-bottom text-center text-sm-start">
    <div class="container">
        <div class="sticky_section">
            <div class="row g-3 align-items-center">
                <div class="col-sm-7">
                    <div class="total_pending_request" id="request_franchise">
                        <h4>
                            <span >{{ Cache::has('franchise') ? count(Cache::get('franchise')) : 0 }}</span>
                            Pending Request
                        </h4>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="request_btn float-sm-end">
                        <a href="{{ route('frontend.request_info') }}" class="btn btn-secondary">Send your Request</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
<!-- footer -->
<footer class="footer_section">
    <div class="container">
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">
            <!-- footer widget -->
            <div class="col mb-4">
                <div class="footer_widget">
                    <div class="footer_title mb-4">
                        <h3>Quick Links</h3>
                    </div>
                    <div class="footer_link">
                        <ul>
                            <li><a href="{{ route('frontend.about') }}">About Us</a></li>
                            <li><a href="{{ route('frontend.contact') }}">Contact</a></li>
                            <li><a href="{{ route('frontend.blogs') }}">Blog</a></li>
                            <li><a href="{{ route('privacy-policy') }}">Privacy Policy</a></li>
                            <li><a href="{{ route('frontend.disclaimer') }}">Disclaimer</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- footer widget -->
            <div class="col mb-4">
                <div class="footer_widget">
                    <div class="footer_title mb-4">
                        <h3>BY LOCATION</h3>
                    </div>
                    <div class="footer_link">
                        <ul>
                            <li><a href="{{ route('frontend.franchises') }}">California</a></li>
                            <li><a href="{{ route('frontend.franchises') }}">Texas</a></li>
                            <li><a href="{{ route('frontend.franchises') }}">Georgia</a></li>
                            <li><a href="{{ route('frontend.franchises') }}">Florida</a></li>
                            <li><a href="{{ route('frontend.franchises') }}">See More</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- footer widget -->
            <div class="col mb-4">
                <div class="footer_widget">
                    <div class="footer_title mb-4">
                        <h3>BY INVESTMENT</h3>
                    </div>
                    <div class="footer_link">
                        <ul>
                            <li><a href="{{ route('frontend.franchises') }}">Under $10,000</a></li>
                            <li><a href="{{ route('frontend.franchises') }}">$10,000 – $25,000</a></li>
                            <li><a href="{{ route('frontend.franchises') }}">$25,000 – $50,000</a></li>
                            <li><a href="{{ route('frontend.franchises') }}">Over $50,000</a></li>
                            <li><a href="{{ route('frontend.franchises') }}">See More</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- footer widget -->
            <div class="col mb-4">
                <div class="footer_widget">
                    <div class="footer_title mb-4">
                        <h3>BY INDUSTRY</h3>
                    </div>
                    <div class="footer_link">
                        <ul>
                            <li><a href="{{ route('frontend.franchises') }}">Automotive</a></li>
                            <li><a href="{{ route('frontend.franchises') }}">Home Services</a></li>
                            <li><a href="{{ route('frontend.franchises') }}">Food Franchises</a></li>
                            <li><a href="{{ route('frontend.franchises') }}">Financial Services</a></li>
                            <li><a href="{{ route('frontend.franchises') }}">See More</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- footer widget -->
            <div class="col mb-4">
                <div class="footer_widget">
                    <div class="footer_title mb-4">
                        <h3>BY POPULARITY</h3>
                    </div>
                    <div class="footer_link">
                        <ul>
                            <li><a href="{{ route('frontend.franchises') }}">Best Franchises</a></li>
                            <li><a href="{{ route('frontend.franchises') }}">New Franchises</a></li>
                            <li><a href="{{ route('frontend.franchises') }}">Low-Cost Franchises</a></li>
                            <li><a href="{{ route('frontend.franchises') }}">Florida</a></li>
                            <li><a href="{{ route('frontend.franchises') }}">See More</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer_bottom text-center text-sm-start">
        <div class=" container">
            <div class="row g-3 align-items-center">
                <div class="col-sm-6">
                    <div class="copyright">
                        <p>© 2023 America’s Best Franchises</p>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="social_media float-sm-end">
                        <ul>
                            <li><a href="#" target="_blank"><i class="lab la-facebook-square"></i></a></li>
                            <li><a href="#" target="_blank"><i class="lab la-twitter-square"></i></a></li>
                            <li><a href="#" target="_blank"><i class="lab la-linkedin"></i></a></li>
                            <li><a href="#" target="_blank"><i class="lab la-pinterest-square"></i></a></li>
                            <li><a href="#" target="_blank"><i class="lab la-youtube-square"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

</footer>
<!-- footer -->
<!-- ======================= footer end ============================ -->
