@extends('admin.layouts.master')
@section('library_menu', 'menu-open')
@section('favourite_book', 'active')

@section('title') {{ $data['title'] ?? '' }} @endsection
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
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">
                    @for ($i = 0; $i < 5; $i++)
                        <div class="col mb-3">
                            <div class="card_book text-center">
                                <div class="book_img pb-3">
                                    <a href="{{ route('admin.user.book.details') }}">
                                        <img src="{{ asset('assets/images/books/1.png') }}" class="img-fluid rounded"
                                            alt="">
                                    </a>
                                </div>
                                <div class="book_content">
                                    <div class="mb-2">
                                        <a href="#" class="book_icon" data-toggle="tooltip" data-placement="top"
                                            title="Unlock">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="#800080" stroke-width="1"
                                                stroke-linecap="round" stroke-linejoin="bevel">
                                                <rect x="3" y="11" width="18" height="11" rx="2"
                                                    ry="2"></rect>
                                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                            </svg>
                                        </a>
                                        <a href="#" class="book_icon" data-toggle="tooltip" data-placement="top"
                                            title="View">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="#800080" stroke-width="1"
                                                stroke-linecap="round" stroke-linejoin="bevel">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </a>
                                        <a href="#" class="book_icon" data-toggle="tooltip" data-placement="top" title="Add Favourite">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="#800080" stroke-width="1"
                                                stroke-linecap="round" stroke-linejoin="bevel">
                                                <path
                                                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                                </path>
                                            </svg>
                                        </a>
                                    </div>
                                    <h4><a href="{{ route('admin.user.book.details') }}">The Big Short (1998)</a></h4>
                                    <a href="{{ route('admin.user.borrow.book') }}" class="btn btn-outline-danger btn-sm">Remove Borrow</a>
                                </div>
                            </div>
                        </div>
                    @endfor

                </div>



            </div>
        </div>
    </div>
@endsection
