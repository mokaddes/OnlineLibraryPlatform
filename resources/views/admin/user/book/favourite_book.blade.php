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
                                        <a href="#" class="book_icon" data-toggle="tooltip" data-placement="top"
                                            title="Remove Favourite">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="#800080" stroke-width="1"
                                                stroke-linecap="round" stroke-linejoin="bevel">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path
                                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                </path>
                                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                            </svg>
                                        </a>
                                    </div>
                                    <h4><a href="{{ route('admin.user.book.details') }}">The Big Short (1998)</a></h4>
                                    <a href="{{ route('admin.user.borrow.book') }}" class="btn btn-light btn-sm">Borrow</a>
                                </div>
                            </div>
                        </div>
                    @endfor

                </div>



            </div>
        </div>
    </div>
@endsection
