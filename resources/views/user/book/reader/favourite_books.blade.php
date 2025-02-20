@extends('admin.layouts.user')
@section('favourite_book', 'active')
@section('books', 'active menu-open')
@section('title')
    {{ $title ?? 'Library' }}
@endsection
@push('style')
    <style>
        .page-item.active .page-link {
            padding-right: 15px !important;
            padding-left: 15px !important;
            background-color: #7c027ccc;
            border-radius: 50px !important;
        }

        .page-item:first-child .page-link {
            padding-right: 15px !important;
            padding-left: 15px !important;
            border-radius: 50px !important;
            background: #88008880;
            color: white;
        }

        .page-item.disabled .page-link {
            padding-right: 15px !important;
            padding-left: 15px !important;
            border-radius: 50px !important;
        }

        .page-item:last-child .page-link {
            background: #88008880;
            color: white;
        }

        li.page-item {
            padding: 5px;
        }

        a.page-link {
            padding-right: 15px !important;
            padding-left: 15px !important;
            border-radius: 50px !important;
            color: black;
        }

        .card {
            box-shadow: none !important;
        }

        .bg-secondary {
            background-color: #800080ad !important;
        }

        .pe-none {
            pointer-events: none !important;
        }

        .book_content {
            height: 75px;
        }

        .content-color, .card-footer {
            background-color: #fff !important;
        }

        .pointer {
            cursor: default !important;
        }
    </style>
@endpush
@php
    $user = auth()->user();
@endphp
@section('content')
    <div class="content-wrapper @if(!($books->count() > 0)) content-color @endif mt-3 pb-4">
        <div class="content">
            <div class="container-fluid">
                <div class="row px-2 mb-4">
                    <h4>Favourite Books</h4>
                </div>
                <div
                    class="row px-3  @if($books->count() > 0) row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 @else d-flex justify-content-center @endif">
                    @if ($user->role_id != "3")
                        @forelse ($books as $book)
                            @php
                                $borrowedBook = $book->borrowedBooks->where('is_valid', '1')->where('user_id', $user->id)->first();
                                $nextdate = $borrowedBook->borrowed_nextdate ?? null;
                                $enddate = $borrowedBook->borrowed_enddate ?? null;
                            @endphp
                            <div class="col mb-3">
                                <div class="card text-center position-relative">
                                    <div class="card-icon position-absolute">
                                        <ul>
                                            @if($book->is_paid == 0 || (isset($user->currentUserPlan->package_id) && $user->currentUserPlan->package_id != 1))
                                                <li><a href="" class="icon-link pe-none"
                                                       title="{{ $book->isBorrowed && $enddate < now() && $nextdate > now() ? 'Lock' : 'Unlock' }}">
                                                        <i class="icon fas fa-{{ $book->isBorrowed && $enddate < now() && $nextdate > now() ? 'lock' : 'unlock' }}"></i>
                                                    </a></li>
                                            @else
                                                <li><a href="" class="icon-link pe-none" title="Lock"><i
                                                            class="icon fas fa-lock"></i></a></li>
                                            @endif
                                            @if(isset($borrowedBook) && $enddate > now())
                                                <li><a href="{{route('user.book.details', $book->slug)}}"
                                                       class="icon-link" title="View"><i
                                                            class="icon fas fa-eye"></i></a></li>
                                            @else
                                                <li><a href="{{route('user.book.details', $book->slug)}}"
                                                       class="icon-link" title="View"><i
                                                            class="icon fas fa-eye"></i></a></li>
                                            @endif
                                            <li>
                                                <a href="{{ route('user.book.favorite.store', $book->id) }}"
                                                   title="Favourite"
                                                   class="icon-link">
                                                    <i class="icon fas fa-heart {{ is_favorite($book->id) ? 'text-danger' : '' }}"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="book_img">
                                        <a href="{{ route('user.book.details', $book->slug) }}">
                                            <img
                                                src="{{ asset(file_exists($book->thumb) ? $book->thumb : 'assets/default.svg') }}"
                                                class="book-image img-fluid"
                                                alt="">
                                        </a>
                                    </div>
                                    <div class="book_content pt-3 pb-3">
                                        <h4>
                                            <a href="{{ route('user.book.details', $book->slug) }}">{{ textLimit($book->title) }}</a>
                                        </h4>
                                    </div>
                                    <div class="text-center review mt-3">
                                        <div class="comment-text">
                                        <span class="username">
                                            <span class="text-muted">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $book->AvgReview())
                                                        <span><i class="fa fa-star text-warning"></i></span>
                                                    @else
                                                        <span><i class="far fa-star text-warning"></i></span>
                                                    @endif
                                                @endfor
                                                ({{  $book->reviews()->count() }})
                                            </span>
                                        </span>
                                        </div>
                                    </div>
                                    <div class="card-footer text-center">
                                        @if($book->isBought)
                                            <button class="btn btn-sm pe-none dark">Bought</button>
                                        @elseif($book->book_for == 'sale' && $book->file_type != 'url')
                                            <a href="{{ route('user.checkout', ['id' => $book->id, 'flag' => 'book']) }}" class="btn btn-sm bg-secondary" title="Buy this book for lifetime">Buy - {{$book->book_price}}$</a>
                                        @else
                                            @if ($book->file_type == 'url')
                                                <a href="{{ route('user.book.details', $book->slug) }}" title="Watch Now"
                                                class="btn btn_primary">Watch Now</a>
                                            @else
                                                @if($book->is_paid == 0 || (isset($user->currentUserPlan->package_id) && $user->currentUserPlan->package_id != 1))
                                                    @if($book->isBorrowed && $enddate < now() && $nextdate > now())
                                                        <button
                                                            title="This book will be available for access on {{$nextdate}}."
                                                            class="pointer btn btn-sm bg-secondary">Locked
                                                        </button>
                                                    @elseif($book->isBorrowed && $enddate > now())
                                                        <button class="btn btn-sm pe-none dark">Borrowed</button>
                                                    @else
                                                        <a href="{{ route('user.book.borrowed.store', $book->id) }}"
                                                        onclick="return confirm('Are you sure you want to borrow this book?')"
                                                        class="btn btn-sm bg-secondary">Borrow</a>
                                                    @endif
                                                @else
                                                    <a href="{{ route('user.book.borrowed.store', $book->id) }}"
                                                    title="Borrow"
                                                    class="btn btn_primary">Premium</a>
                                                @endif
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center mt-sm-5">
                                <img class="img-fluid empty" src="{{ asset('assets/frontend/images/nodata.jpg')}}"
                                     alt="no-data">
                                {{-- <p class="no-data">Sorry, we couldn't find any data.</p>  --}}
                            </div>
                        @endforelse
                    @else
                        @forelse ($books as $book)
                            <div class="col mb-3">
                                <div class="card text-center position-relative">
                                    <div class="card-icon position-absolute">
                                        <ul>
                                            <li><a href="#" class="pe-none icon-link" title="Unlock"><i
                                                        class="icon fas fa-unlock"></i></a></li>
                                            <li><a href="{{route('user.book.details', $book->slug)}}" class="icon-link"
                                                   title="View"><i class="icon fas fa-eye"></i></a></li>
                                            <li>
                                                <a href="{{ route('user.book.favorite.store', $book->id) }}"
                                                   title="Favourite"
                                                   class="icon-link">
                                                    <i class="icon fas fa-heart {{ is_favorite($book->id) ? 'text-danger' : '' }}"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="book_img">
                                        <a href="{{ route('user.book.details', $book->slug) }}">
                                            <img
                                                src="{{ asset(file_exists($book->thumb) ? $book->thumb : 'assets/default.svg') }}"
                                                class="book-image img-fluid"
                                                alt="">
                                        </a>
                                    </div>
                                    <div class="book_content">
                                        <h4 class="py-1">
                                            <a href="{{ route('user.book.details', $book->slug) }}">{{ textLimit($book->title) }}</a>
                                        </h4>
                                    </div>
                                    <div class="text-center review mt-3">
                                        <div class="comment-text">
                                        <span class="username">
                                            <span class="text-muted">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $book->AvgReview())
                                                        <span><i class="fa fa-star text-warning"></i></span>
                                                    @else
                                                        <span><i class="far fa-star text-warning"></i></span>
                                                    @endif
                                                @endfor
                                                ({{  $book->reviews()->count() }})
                                            </span>
                                        </span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @empty
                            <div class="text-center mt-sm-5">
                                <img class="img-fluid empty" src="{{ asset('assets/frontend/images/nodata.jpg')}}"
                                     alt="no-data">
                            </div>
                        @endforelse
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script></script>
@endpush
