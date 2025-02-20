@extends('admin.layouts.user')
@section('favourite', 'active')
@section('books', 'active menu-open')
@section('title')
    {{ $title ?? 'Read Book' }}
@endsection
@push('style')
    <link type="text/css" href="{{ asset('assets/flip/css/style.css') }}" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="http://fonts.googleapis.com/css?family=Play:400,700">
    <link type="text/css" href="{{ asset('assets/flip/css/font-awesome.min.css') }}" rel="stylesheet">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            overflow: auto !important;
        }

        @media (min-width: 994px) {
            #fb5-ajax[data-template="true"] {
                top: 50px !important;
                left: 250px;
            }
        }

        #fb5-ajax.fullScreen {
            top: 0 !important;
            left: 0 !important;
        }

        #fb5 #fb5-footer #fb5-logo {
            top: 15px !important;
            color: #fff !important;
            font-weight: bold !important;
            font-size: 16px !important;
        }

        #safeTimer {

            border-top-right-radius: 10px;
            font-size: 16px;
            z-index: 9999;
        }

    </style>
@endpush
@section('content')
    <!-- begin flipbook  -->
    <div id="fb5-ajax" data-cat="" data-template="true">

        <!-- BACKGROUND FLIPBOOK -->
        <div class="fb5-bcg-book"></div>

        <!-- BEGIN PRELOADER -->
        <div class="fb5-preloader"></div>
        <!-- END PRELOADER -->

        <!-- BEGIN STRUCTURE HTML FLIPBOOK -->
        <div class="fb5" id="fb5">

            <!-- CONFIGURATION BOOK -->
            <section id="config">
                <ul>
                    <li key="page_width">918</li>                           <!-- width for page -->
                    <li key="page_height">1298</li>                         <!-- height for page -->
                    <li key="gotopage_width">25</li>                        <!-- width for field input goto page -->
                    <li key="zoom_double_click">1</li>                      <!-- value zoom after double click -->
                    <li key="zoom_step">0.06</li>
                    <!-- zoom step ( if click icon zoomIn or zoomOut -->
                    <li key="toolbar_visible">true</li>                        <!-- enabled/disabled toolbar -->
                    <li key="tooltip_visible">true</li>
                    <!-- enabled/disabled tooltip for icon -->
                    <li key="deeplinking_enabled">true</li>                <!-- enabled/disabled deeplinking -->
                    <li key="lazy_loading_pages">false</li>
                    <!-- enabled/disabled lazy loading for pages in flipbook -->
                    <li key="lazy_loading_thumbs">false</li>
                    <!-- enabled/disabled lazdy loading for thumbs -->
                    <li key="double_click_enabled">true</li>
                    <!-- enabled/disabled double click mouse for flipbook -->
                    <li key="rtl">false</li>
                    <!-- enabled/disabled 'right to left' for eastern countries -->
                    <li key="pdf_url">{{ asset($book->file_dir) }}</li>
                    <!-- pathway to a pdf file ( the file will be read live ) -->
                    <li key="pdf_scale">2</li>
                    <!-- to live a pdf file (if you want to have a strong zoom - increase the value) -->
                    <li key="page_mode">single</li>
                    <!-- value to 'single', 'double', or 'auto' -->
                    <li key="sound_sheet"></li>                             <!-- sound for sheet -->
                </ul>
            </section>


            <!-- BEGIN CONTAINER BOOK -->
            <div id="fb5-container-book">

                <!-- BEGIN deep linking -->
                <section id="fb5-deeplinking">
                    <ul>
                        <li data-address="page1" data-page="1"></li>
                        <li data-address="page2-page3" data-page="2"></li>
                        <li data-address="page2-page3" data-page="3"></li>
                        <li data-address="page4-page5" data-page="4"></li>
                        <li data-address="page4-page5" data-page="5"></li>
                        <li data-address="page6-page7" data-page="6"></li>
                        <li data-address="page6-page7" data-page="7"></li>
                        <li data-address="page8-page9" data-page="8"></li>
                        <li data-address="page8-page9" data-page="9"></li>
                        <li data-address="page10-page11" data-page="10"></li>
                        <li data-address="page10-page11" data-page="11"></li>
                        <li data-address="page12" data-page="12"></li>
                    </ul>
                </section>
                <!-- END deep linking -->

                <!-- BEGIN ABOUT -->
                <section id="fb5-about">
                </section>
                <!-- END ABOUT -->


                <!-- BEGIN LINKS -->
                <section id="links">


                </section>
                <!-- END LINKS -->


                <!-- BEGIN PAGES -->
                <div id="fb5-book">

                    <!-- begin page 1 -->
                    <div data-background-image="pages/1.jpg">

                        <!-- container page book -->
                        <div class="fb5-cont-page-book">

                            <!-- gradient for page -->
                            <div class="fb5-gradient-page"></div>

                            <!-- PDF.js -->
                            <canvas id="canv1"></canvas>

                            <!-- description for page -->
                            <div class="fb5-page-book">

                            </div>


                        </div>
                        <!-- end container page book -->


                    </div>
                    <!-- end page 1 -->


                    <!-- begin page 2 -->
                    <div data-background-image="pages/2.jpg">


                        <!-- container page book -->
                        <div class="fb5-cont-page-book">

                            <!-- gradient for page -->
                            <div class="fb5-gradient-page"></div>


                            <!-- PDF.js -->
                            <canvas id="canv2"></canvas>


                            <!-- description for page -->
                            <div class="fb5-page-book">

                            </div>

                        </div> <!-- end container page book -->

                    </div>
                    <!-- end page 2 -->


                    <!-- begin page 3 -->
                    <div data-background-image="pages/3.jpg">


                        <!-- container page book -->
                        <div class="fb5-cont-page-book">

                            <!-- gradient for page -->
                            <div class="fb5-gradient-page"></div>


                            <!-- PDF.js -->
                            <canvas id="canv3"></canvas>


                            <!-- description for page from WYSWIG -->
                            <div class="fb5-page-book">

                            </div>


                        </div> <!-- end container page book -->

                    </div>
                    <!-- end page 3 -->


                    <!-- begin page 4 -->
                    <div data-background-image="pages/4.jpg">


                        <!-- container page book -->
                        <div class="fb5-cont-page-book">

                            <!-- gradient for page -->
                            <div class="fb5-gradient-page"></div>

                            <!-- PDF.js -->
                            <canvas id="canv4"></canvas>

                            <!-- description for page  -->
                            <div class="fb5-page-book">

                            </div>


                        </div> <!-- end container page book -->

                    </div>
                    <!-- end page 4 -->


                    <!-- begin page 5 -->
                    <div data-background-image="pages/5.jpg">


                        <!-- container page book -->
                        <div class="fb5-cont-page-book">

                            <!-- gradient for page -->
                            <div class="fb5-gradient-page"></div>


                            <!-- PDF.js -->
                            <canvas id="canv5"></canvas>


                            <!-- description for page from WYSWIG -->
                            <div class="fb5-page-book">

                            </div>


                        </div> <!-- end container page book -->

                    </div>
                    <!-- end page 5 -->


                    <!-- begin page 6 -->
                    <div data-background-image="pages/6_7.jpg" class="fb5-double fb5-first">


                        <!-- container page book -->
                        <div class="fb5-cont-page-book">

                            <!-- gradient for page -->
                            <div class="fb5-gradient-page"></div>


                            <!-- PDF.js -->
                            <canvas id="canv6"></canvas>


                            <!-- description for page from WYSWIG -->
                            <div class="fb5-page-book">

                            </div>


                        </div>
                        <!-- end container page book -->

                    </div>
                    <!-- end page 6 -->


                    <!-- begin page 7 -->
                    <div data-background-image="pages/6_7.jpg" class="fb5-double fb5-second">


                        <!-- container page book -->
                        <div class="fb5-cont-page-book">

                            <!-- gradient for page -->
                            <div class="fb5-gradient-page"></div>


                            <!-- PDF.js -->
                            <canvas id="canv7"></canvas>


                            <!-- description for page -->
                            <div class="fb5-page-book">

                            </div>

                        </div>
                        <!-- end container page book -->

                    </div>
                    <!-- end page 7 -->


                    <!-- begin page 8 -->
                    <div data-background-image="pages/8_9.jpg" class="fb5-double fb5-first">


                        <!-- container page book -->
                        <div class="fb5-cont-page-book">

                            <!-- gradient for page -->
                            <div class="fb5-gradient-page"></div>


                            <!-- PDF.js -->
                            <canvas id="canv8"></canvas>


                            <!-- description for page -->
                            <div class="fb5-page-book">

                            </div>

                        </div> <!-- end container page book -->

                    </div>
                    <!-- end page 8 -->


                    <!-- begin page 9 -->
                    <div data-background-image="pages/8_9.jpg" class="fb5-double fb5-second">


                        <!-- container page book -->
                        <div class="fb5-cont-page-book">

                            <!-- gradient for page -->
                            <div class="fb5-gradient-page"></div>


                            <!-- PDF.js -->
                            <canvas id="canv9"></canvas>


                            <!-- description for page  -->
                            <div class="fb5-page-book">

                            </div>


                        </div>
                        <!-- end container page book -->

                    </div>
                    <!-- end page 9 -->


                    <!-- begin page 10 -->
                    <div data-background-image="pages/10.jpg">


                        <!-- container page book -->
                        <div class="fb5-cont-page-book">

                            <!-- gradient for page -->
                            <div class="fb5-gradient-page"></div>


                            <!-- PDF.js -->
                            <canvas id="canv10"></canvas>


                            <!-- description for page -->
                            <div class="fb5-page-book">

                            </div>


                        </div> <!-- end container page book -->

                    </div>
                    <!-- end page 10 -->


                    <!-- begin page 11 -->
                    <div data-background-image="pages/11.jpg">


                        <!-- container page book -->
                        <div class="fb5-cont-page-book">

                            <!-- gradient for page -->
                            <div class="fb5-gradient-page"></div>


                            <!-- PDF.js -->
                            <canvas id="canv11"></canvas>


                            <!-- description for page -->
                            <div class="fb5-page-book">

                            </div>

                        </div> <!-- end container page book -->
                    </div>
                    <!-- end page 11 -->

                    <!-- begin page 12 -->
                    <div data-background-image="pages/12.jpg">


                        <!-- container page book -->
                        <div class="fb5-cont-page-book">

                            <!-- gradient for page -->
                            <div class="fb5-gradient-page"></div>


                            <!-- PDF.js -->
                            <canvas id="canv12"></canvas>


                            <!-- description for page -->
                            <div class="fb5-page-book">

                            </div>


                        </div> <!-- end container page book -->

                    </div>
                    <!-- end page 12 -->
                </div>
                <!-- END PAGES -->
            </div>
            <!-- END CONTAINER BOOK -->
            <!-- BEGIN FOOTER -->
            <div id="fb5-footer">
                <div class="fb5-bcg-tools"></div>
                <a id="fb5-logo" target="_blank" href="javascript:void(0)">
                    {{ wordLimit($book->title, 25) }}
                </a>

                <div class="fb5-menu" id="fb5-center">
                    <ul>

                        <!-- icon_home -->
                        <li>
                            <a title="show home page" class="fb5-home"><i class="fa fa-home"></i></a>
                        </li>

                        <!-- icon arrow left -->
                        <li>
                            <a title="prev page" class="fb5-arrow-left"><i class="fa fa-chevron-left"></i>
                            </a>
                        </li>


                        <!-- icon arrow right -->
                        <li>
                            <a title="next page" class="fb5-arrow-right"><i class="fa fa-chevron-right"></i>
                            </a>
                        </li>


                        <!-- icon_zoom_in -->
                        <li>
                            <a title="zoom in" class="fb5-zoom-in"><i class="fa fa-search-plus"></i></a>
                        </li>


                        <!-- icon_zoom_out -->
                        <li>
                            <a title="zoom out" class="fb5-zoom-out"><i class="fa fa-search-minus"></i></a>
                        </li>


                        <!-- icon_zoom_auto -->
                        <li>
                            <a title="zoom auto" class="fb5-zoom-auto"><i class="fa fa-search"></i></a>
                        </li>


                        <!-- icon_allpages -->
                        <li>
                            <a title="show all pages" class="fb5-show-all"><i class="fa fa-list"></i></a>
                        </li>


                        <!-- icon fullscreen -->
                        <li>
                            <a title="full/normal screen" class="fb5-fullscreen"><i class="fa fa-expand"></i></a>
                        </li>
                        <!-- icon back -->
                        <li>

                            <a href="{{ route('user.book.details', $book->slug) }}" title="Back"
                               class="btn btn-sm btn-primary"><i class="fa fa-arrow-left"></i></a>
                        </li>


                    </ul>
                </div>

                <div class="fb5-menu" id="fb5-right">
                    <ul>
                        <!-- icon page manager -->
                        <li class="fb5-goto">
                            <label for="fb5-page-number" id="fb5-label-page-number"></label>
                            <input type="text" id="fb5-page-number" style="width: 25px;">
                            <span id="fb5-page-number-two"></span>

                        </li>
                    </ul>
                </div>


            </div>
            <!-- END FOOTER -->

            <!-- BEGIN ALL PAGES -->
            <div id="fb5-all-pages" class="fb5-overlay">

                <section class="fb5-container-pages">

                    <div id="fb5-menu-holder">

                        <ul id="fb5-slider">

                            <!-- thumb 1 -->
                            <li class="1">
                                <img alt="" data-src="pages/1_.jpg">

                            </li>

                            <!-- thumb 2 -->
                            <li class="2">
                                <img alt="" data-src="pages/2_.jpg">
                            </li>


                            <!-- thumb 3 -->
                            <li class="3">
                                <!-- img -->
                                <img alt="" data-src="pages/3_.jpg">

                            </li>


                            <!-- thumb 4 -->
                            <li class="4">
                                <!-- img -->
                                <img alt="" data-src="pages/4_.jpg">

                            </li>


                            <!-- thumb 5 -->
                            <li class="5">
                                <!-- img -->
                                <img alt="" data-src="pages/5_.jpg">

                            </li>

                            <!-- thumb 6 and 7 -->
                            <li class="6">
                                <!-- img -->
                                <img alt="" data-src="pages/6_7_.jpg">

                            </li>


                            <!-- thumb 8 and 9 -->
                            <li class="8">
                                <!-- img -->
                                <img alt="" data-src="pages/8_9_.jpg">

                            </li>


                            <!-- thumb 10 -->
                            <li class="10">
                                <!-- img -->
                                <img alt="" data-src="pages/10_.jpg">

                            </li>

                            <!-- thumb 11 -->
                            <li class="11">
                                <!-- img -->
                                <img alt="" data-src="pages/11_.jpg">

                            </li>


                            <!-- thumb 12 -->
                            <li class="12">
                                <!-- img -->
                                <img alt="" data-src="pages/12_.jpg">

                            </li>


                        </ul>

                    </div>

                </section>

            </div>
            <!-- END ALL PAGES -->

            <!-- BEGIN SOUND FOR SHEET  -->
            <audio preload="auto" id="sound_sheet"></audio>
            <!-- END SOUND FOR SHEET -->

            <!-- BEGIN CLOSE LIGHTBOX  -->
            <div id="fb5-close-lightbox">
                <i class="fa fa-times pull-right"></i>
            </div>
            <!-- END CLOSE LIGHTBOX -->


        </div>
        <!-- END STRUCTURE HTML FLIPBOOK -->


    </div>
    <!-- end flipbook -->

    {{--    <div id="safeTimer">Reading Time: <span id="safeTimerDisplay">00:00:00</span></div>--}}
@endsection
@push('script')
    <script>
        var oldProgress = '{{  $viewed->progress }}';
        let currentPage = '{{  $current_page }}';
        var progressRoute = '{{ route('user.book.progress', $viewed->id) }}';
        var reading_time = '{{ $reading_time }}';
        var page_stay_time = '{{ $viewed->page_stay_time }}';
        var timer;
        // var pageTurnedWithinInterval = false;
        var readingCompleteStatus = '{{ $status }}';
        console.log('Reading Complete Status:', readingCompleteStatus);
        var interval;

        function timeToDisplay(clock_time) {
            var hours = parseInt(clock_time / 3600, 10);
            var minutes = parseInt((clock_time % 3600) / 60, 10);
            var seconds = parseInt(clock_time % 60, 10);

            hours = hours < 10 ? "0" + hours : hours;
            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            return hours + ":" + minutes + ":" + seconds;
        }

        function savePageStayTime(stay_time, page1) {
            $.ajax({
                type: 'GET',
                url: progressRoute,
                data: {
                    page_stay_time: stay_time,
                    page: page1,
                    type : 'set'
                },
                success: function (response) {
                    console.log('Server updated successfully');
                },
                error: function (error) {
                    // Handle error if needed
                    console.error('Error updating server:', error);
                }
            });
        }

        function startTimer(duration, display, page1, page, totalLength) {
            timer = duration;
            console.log('Timer:', parseInt(timer));
            interval = setInterval(function () {
                display.textContent = timeToDisplay(timer);
                let pageTime = reading_time / totalLength;
                let halfTime = Math.floor(pageTime / 2);
                if(timer % halfTime === 0 || timer === parseInt(pageTime)){
                    savePageStayTime(timer, page1);

                }


                timer++;

            }, 1000);
        }

        $('body').click(function () {
            console.log('Body Clicked:' + timer);
        });



    </script>

    <script src="{{ asset('assets/flip/js/turn.js') }}"></script>
    <script src="{{ asset('assets/flip/js/wait.js') }}"></script>
    <script src="{{ asset('assets/flip/js/jquery.mousewheel.js') }}"></script>
    <script src="{{ asset('assets/flip/js/jquery.fullscreen.js') }}"></script>
    <script src="{{ asset('assets/flip/js/jquery.address-1.6.min.js') }}"></script>
    <script src="{{ asset('assets/flip/js/pdf.js') }}"></script>
    <script src="{{ asset('assets/flip/js/onload.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function () {

            $('.nav-link[data-widget="pushmenu"]').click(function () {
                var sidebar = $('#fb5-ajax[data-template="true"]');
                var windowWidth = $(window).width();

                if (windowWidth > 994) {
                    // If the window width is more than 994 pixels
                    if ($('body').hasClass('sidebar-collapse')) {
                        sidebar.stop().animate({
                            left: '250px',
                        }, 450);
                    } else {
                        sidebar.stop().animate({
                            left: '73px',
                        }, 300, function () {
                            // Animation complete callback
                        });
                    }
                }
            });
        })

        function updateFooterTop() {
            var windowWidth = $(window).width();

            if (windowWidth >= 994) {
                $('#fb5 #fb5-footer').css('top', '0');
            } else {
                // Reset to the default value if the window width is less than 994px
                $('#fb5 #fb5-footer').css('top', '56px');
            }
        }

        updateFooterTop();

        $(window).resize(function () {
            updateFooterTop();
        });


    </script>
@endpush
