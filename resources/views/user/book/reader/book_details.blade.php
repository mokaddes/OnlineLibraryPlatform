@extends('admin.layouts.user')
@section('favourite', 'active')
@section('books', 'active menu-open')
@section('title')
    {{ $title ?? 'Library' }}
@endsection
@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">
    <style>
        video, audio {
            width: 100%;
            height: auto;
        }


    </style>
@endpush
@section('content')
    <div class="content-wrapper mt-3 pb-4">
        <div class="content">
            <div class="container-fluid">
                <div class="row px-2 mb-4">
                    <h4>{{ $title ?? 'Heading' }}</h4>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4 mb-4 mb-lg-0">
                                <div class="text-center">
                                    @if($book->file_type == 'video')
                                        <h4>Watch Video Book</h4>
                                        <video id="bookMedia" preload="auto" controls onseeking="preventSeeking()" controlsList="nodownload">
                                            <source src="{{ asset($book->file_dir) }}">
                                            Your browser does not support the video tag.
                                        </video>
                                    @elseif ($book->file_type == 'audio')
                                        <h4>Play Audio Book</h4>
                                        <audio id="bookMedia" preload="auto" controls onseeking="preventSeeking()" style="width: 300px; height: 100px"
                                               controlsList="nodownload">
                                            <source src="{{ asset($book->file_dir) }}">
                                            Your browser does not support the audio element.
                                        </audio>
                                    @elseif ($book->file_type == 'url' && !empty($book->file_dir))
                                        <h4>Play Book</h4>
                                        <iframe width="300" height="250" src="{{ $book->file_dir }}" frameborder="0"
                                                allowfullscreen></iframe>

                                    @elseif($book->file_type == 'pdf')
                                        <div class="d-flex flex-column">
                                            <a href="{{ route('user.book.read', $book->slug) }}">
                                                <img
                                                    src="{{ asset(file_exists($book->thumb) ? $book->thumb : 'assets/default.svg') }}"
                                                    alt="Book thumbnail"
                                                    class="img-fluid rounded w-75">
                                            </a>
                                            <a href="{{ route('user.book.read', $book->slug) }}/#{{ $viewed->current_page ?? 1 }}"
                                               class="mt-3 btn btn-success">Read now</a>
                                        </div>
                                    @else

                                        <img
                                            src="{{ asset(file_exists($book->thumb) ? $book->thumb : 'assets/default.svg') }}"
                                            alt="Book thumbnail"
                                            class="img-fluid rounded">
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="single-pro-detail">
                                    <h3 class="pro-title">{{ $book->title }}</h3>
                                    <div class="custom-border mb-3"></div>
                                    @if($book->publisher)
                                        <p class="text-muted mb-2">Publisher: {{ $book->publisher }}</p>
                                    @endif
                                    <p class="text-muted mb-2">
                                        Authors: {{ $book->authors ?? $book->author->name ?? ''  }}</p>
                                    @if($book->publisher_year)
                                        <p class="text-muted mb-2">Publish Years: {{ $book->publisher_year }}</p>
                                    @endif
                                    @if($book->edition)
                                        <p class="text-muted mb-2">Edition: {{ $book->edition }}</p>
                                    @endif
                                    @if($book->pages)
                                        <p class="text-muted mb-2">Pages: {{ $book->pages }}</p>
                                    @endif
                                    @if($book->isbn10)
                                        <p class="text-muted mb-2">ISBN10: {{ $book->isbn10 }}</p>
                                    @endif
                                    @if($book->isbn13)
                                        <p class="text-muted mb-2">ISBN13: {{ $book->isbn13 }}</p>
                                    @endif
                                    <ul class="list-inline mb-2 product-review">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $avg_rating)
                                                <li class="list-inline-item"><i class="fas fa-star text-warning"></i>
                                                </li>
                                            @else
                                                <li class="list-inline-item"><i class="far fa-star text-warning"></i>
                                                </li>
                                            @endif
                                        @endfor
                                        <li class="list-inline-item">{{ $avg_rating }} ({{ $total_review }} Reviews)
                                        </li>
                                    </ul>
                                </div>

                                <div class="mt-5">
                                    {!! $book->description !!}
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


                @include('user.book.reader._reviews', ['reviews' => $reviews, 'user' => $user])
                @include('user.book.reader._review_form', ['item' => $book, 'user' => $user])

            </div>
        </div>
    </div>

@endsection
@push('script')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>
    <script type="text/javascript" src="{{ asset('assets/turn/jquery-ui-1.8.20.custom.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/turn/modernizr.2.5.3.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/turn/hash.js') }}"></script>

    <script>
        $(document).on('click', '.editReview', function () {
            $('.reviewForm').toggle();
        });

        $(document).ready(function () {
            let authRating = '{{ $auth_review->rating ?? 0 }}';
            $("#rateYo").rateYo({
                starWidth: '30px',
                fullStar: true,
                rating: authRating,
                ratedFill: 'orange',
                onSet: function (rating, rateYoInstance) {
                    $('.rating').val(rating);
                }
            });
        });

        var audio = document.getElementById("bookMedia");
        var previousProgress = '{{ $viewed->progress }}'
        let supposedCurrentTime = 0;



        if (audio) {
            audio.addEventListener("timeupdate", function () {
                var currentTime = audio.currentTime;
                if (!audio.seeking) {
                    supposedCurrentTime = audio.currentTime;
                }

                var duration = audio.duration;

                var progress = (currentTime / duration) * 100;
                if (previousProgress < 100 && progress <= 100) {
                    sendAjaxRequest(progress);
                }
            });


            audio.addEventListener("seeking", function() {
                var delta = audio.currentTime - supposedCurrentTime;
                if (Math.abs(delta) > 0.01) {
                    console.log("Seeking is disabled");
                    audio.currentTime = supposedCurrentTime;
                }
            });

            audio.addEventListener('ended', function() {
                supposedCurrentTime = 0; // Reset state for rewind
            });
        }

        function sendAjaxRequest(progress) {
            // Use AJAX to send the current time to the server
            // Replace 'your-update-endpoint' with the actual endpoint in your Laravel application
            // You may need to include a CSRF token in your AJAX request if CSRF protection is enabled
            $.ajax({
                type: 'GET',
                url: '{{ route('user.book.progress', $viewed->id) }}',
                data: {
                    progress: progress,
                },
                success: function (response) {
                    // Handle success if needed
                    console.log('Server updated successfully');
                },
                error: function (error) {
                    // Handle error if needed
                    console.error('Error updating server:', error);
                }
            });
        }



    </script>

@endpush
