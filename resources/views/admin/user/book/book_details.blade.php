@extends('admin.layouts.master')
@section('library_menu', 'menu-open')
@section('all_book', 'active')
@section('title') {{ $data['title'] ?? '' }} @endsection
@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">
@endpush
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ $data['title'] ?? 'Page Header' }}</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="tg-postbook text-center">
                            <img src="{{ asset('assets/images/books/1.png') }}" class="img-fluid" alt="">
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="tg-productcontent">
                            <ul class="tg-bookscategories">
                                <li><a href="javascript:void(0);">Art &amp; Photography</a></li>
                            </ul>
                            <div class="tg-booktitle">
                                <h3>Drive Safely, No Bumping</h3>
                            </div>
                            <span class="tg-bookwriter">By: <a href="javascript:void(0);">Angela Gunning</a></span> <br>
                            <span class="tg-addreviews">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="#ffc107" stroke="#ffc107" stroke-width="1"
                                        stroke-linecap="round" stroke-linejoin="bevel">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg>
                                </span>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="#ffc107" stroke="#ffc107" stroke-width="1"
                                        stroke-linecap="round" stroke-linejoin="bevel">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg>
                                </span>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="#ffc107" stroke="#ffc107" stroke-width="1"
                                        stroke-linecap="round" stroke-linejoin="bevel">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg>
                                </span>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="#ffc107" stroke="#ffc107" stroke-width="1"
                                        stroke-linecap="round" stroke-linejoin="bevel">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg>
                                </span>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="#ddd" stroke-width="1"
                                        stroke-linecap="round" stroke-linejoin="bevel">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg>
                                </span>
                                <a href="#">Add Your Review</a>
                            </span>
                            <div class="tg-description mt-4">
                                <p>Consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore etdoloreat magna
                                    aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laborisi nisi ut
                                    aliquip ex ea commodo consequat aute.</p>
                                <p>Arure dolor in reprehenderit in voluptate velit esse cillum dolore fugiat nulla aetur
                                    excepteur sint occaecat cupidatat non proident, sunt in culpa quistan officia serunt
                                    mollit anim id est laborum sed ut perspiciatis unde omnis iste natus</p>
                            </div>
                        </div>
                        <div class="tg-sectionhead">
                            <h2>Product Details</h2>
                        </div>
                        <ul class="tg-productinfo pb-4">
                            <li><span>Format:</span><span>Hardback</span></li>
                            <li><span>Pages:</span><span>528 pages</span></li>
                            <li><span>Dimensions:</span><span>153 x 234 x 43mm | 758g</span></li>
                            <li><span>Publication Date:</span><span>June 27, 2017</span></li>
                            <li><span>Publisher:</span><span>Sunshine Orlando</span></li>
                            <li><span>Language:</span><span>English</span></li>
                            <li><span>Illustrations note:</span><span>b&amp;w images thru-out; 1 x 16pp colour
                                    plates</span></li>
                            <li><span>ISBN10:</span><span>1234567890</span></li>
                            <li><span>ISBN13:</span><span>1234567890000</span></li>
                            <li><span>Other Fomate:</span><span>CD-Audio, Paperback, E-Book</span></li>
                        </ul>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="tg-authorbox">
                                    <figure class="tg-authorimg">
                                        <img src="{{ asset('assets/images/avatar/1.jpg') }}" class="rounded-circle"
                                            width="80" height="80" alt="image description">
                                    </figure>
                                    <div class="tg-authorinfo">
                                        <div class="tg-authorhead">
                                            <div class="tg-leftarea">
                                                <div class="tg-authorname">
                                                    <h2>Kathrine Culbertson</h2>
                                                    <span>Author Since: June 27, 2017</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tg-description">
                                            <p>Laborum sed ut perspiciatis unde omnis iste natus sit voluptatem accusantium
                                                doloremque laudantium totam rem aperiam eaque ipsa quae ab illo inventore
                                                veritatis
                                                etation.</p>
                                        </div>
                                        <a class="tg-btn tg-active" href="javascript:void(0);">View All Books</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pb-5">
                    <div class="bg-white p-4 rounded mt-4">
                        <div class="post-comments border-bottom pb-5">
                            <div class="media">
                                <div class="media-left">
                                    <img src="{{ asset('assets/images/avatar/1.jpg') }}" alt="image">
                                </div>
                                <div class="media-body">
                                    <div class="media-heading">
                                        <h4>Rabin</h4>
                                        <span class="time">5 min ago</span>
                                    </div>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                        tempor
                                        incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis
                                        nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                        consequat.
                                    </p>
                                    <a href="#" class="reply"> <i class="fa fa-reply-all"></i> Reply</a>
                                    <a href="#" class="reply like"> <i class="fa fa-thumbs-up"></i> Like</a>
                                    <a href="#" class="reply dislike"> <i class="fa fa-thumbs-down"></i>
                                        Dislike</a>


                                    <div class="media media-author">
                                        <div class="media-left">
                                            <img src="{{ asset('assets/images/default-user.png') }}" alt="">
                                        </div>
                                        <div class="media-body">
                                            <div class="media-heading">
                                                <h4>Samantha</h4>
                                                <span class="time">5 min ago</span>
                                            </div>
                                            <p>
                                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
                                                eiusmod
                                                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim
                                                veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex
                                                ea
                                                commodo consequat.

                                            </p>
                                            <a href="#" class="reply"> <i class="fa fa-reply-all"></i>
                                                Reply</a>
                                            <a href="#" class="reply like"> <i class="fa fa-thumbs-up"></i>
                                                Like</a>
                                            <a href="#" class="reply dislike"> <i class="fa fa-thumbs-down"></i>
                                                Dislike</a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="media">
                                <div class="media-left">
                                    <img src="{{ asset('assets/images/avatar/3.jpg') }}" alt="">
                                </div>
                                <div class="media-body">
                                    <div class="media-heading">
                                        <h4>Shakib</h4>
                                        <span class="time">5 min ago</span>
                                    </div>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                        tempor
                                        incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis
                                        nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                        consequat.
                                    </p>
                                    <a href="#" class="reply"> <i class="fa fa-reply-all"></i> Reply</a>
                                    <a href="#" class="reply like"> <i class="fa fa-thumbs-up"></i> Like</a>
                                    <a href="#" class="reply dislike"> <i class="fa fa-thumbs-down"></i>
                                        Dislike</a>
                                </div>
                            </div>
                        </div>

                        <form class="pt-5" action="#" method="post">
                            <div class="form-group">
                                <div id="rateYo"></div>
                            </div>
                            <div class="form-group">
                                <textarea name="message" id="message" cols="30" rows="5" class="form-control"
                                    placeholder="Write your message" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Send</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection


@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#rateYo").rateYo({
                starWidth: '30px',
                fullStar: true,
                mormalFill: 'yellow',
                ratedFill: 'orange',
                onSet: function(rating, rateYoInstance) {
                    $('#rating').val(rating);
                }
            });
        });
    </script>
@endpush
