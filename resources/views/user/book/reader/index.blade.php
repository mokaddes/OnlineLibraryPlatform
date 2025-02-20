@extends('admin.layouts.user')
@section('all_book', 'active')
@section('books', 'active menu-open')
@section('title')
    {{ $title ?? 'Library' }}
@endsection
@push('style')
    <style>
        .page-item.active .page-link {
            width: 40px;
            height: 40px;
            background-color: #7c027ccc;
            border-radius: 100px !important;
            text-align: center;
            line-height: 24px;
        }

        .page-item:first-child .page-link {
            width: 40px;
            height: 40px;
            border-radius: 100px !important;
            text-align: center;
            line-height: 24px;
            background: #88008880;
            color: white;
        }

        .page-item.disabled .page-link {
            width: 40px;
            height: 40px;
            border-radius: 100px !important;
            text-align: center;
            line-height: 24px;
            background-color: #987b99e6;
        }

        .page-item.disabled:last-child .page-link {
            background-color: #987b99e6;
        }

        .page-item:last-child .page-link {
            background: #88008880;
            color: white;
        }

        li.page-item {
            padding: 5px;
        }

        a.page-link {
            width: 40px;
            height: 40px;
            border-radius: 100px !important;
            text-align: center;
            line-height: 24px;
            color: black;
        }

        span.select2.select2-container.select2-container--default {
            margin-bottom: 10px !important;
        }

        .book_content {
            height: 75px;
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

        .content-color, .card-footer {
            background-color: #fff !important;
        }

        .filter_btn {
            padding: 5px 5px !important;
        }

        .pointer {
            cursor: default !important;
        }

        span.select2.select2-container {
            margin-right: 10px !important;
        }

        .select2-container--default .select2-selection--single {
            height: 40px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 30px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 37px !important;
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
                <div class="row px-2 mb-5 d-flex justify-content-between">
                    <h4 class="d-flex align-items-center">All
                        Books @if(!isset($user->currentUserPlan->package_id) || $user->currentUserPlan->package_id == 1)
                            &nbsp; <a href="{{route('user.book.index', ['content' => 'free'])}}"
                                      class="badge badge-primary">Free</a>
                        @endif</h4>
                    <form action="{{route('user.book.index')}}" method="get">
                        <div class="d-md-flex align-items-center">
                            <input type="text" name="keyword" class="form-control mb-2 mr-2" placeholder="keyword">
                            <select class="form-control form-select select2 mb-2" name="category">
                                <option class="d-none" value="">Select Category</option>
                                @foreach ($categories as $cat)
                                    <option value="{{$cat->id}}"
                                            @if(Request::get('category') == $cat->id) selected @endif>{{$cat->name}}</option>
                                @endforeach
                            </select>
                            <select class="form-control form-select select2 mb-2" name="author">
                                <option class="d-none" value="">Select Author</option>
                                @foreach ($authors as $author)
                                    <option value="{{$author->id}}"
                                            @if(Request::get('author') == $author->id) selected @endif>{{$author->name}} {{$author->last_name}}</option>
                                @endforeach
                            </select>
                            <input type="submit" class="btn btn-sm px-2 btn-primary mr-2"
                                   style="background-color: #800080 !important; margin-bottom: 10px;" value="Search">
                            @if(Request::get('category') || Request::get('author'))
                                <a href="{{route('user.book.index')}}" class="btn btn-sm px-2 btn-danger mr-2"
                                   style="margin-bottom: 10px;">Reset</a>
                            @endif
                        </div>
                    </form>
                </div>
                <div
                    class="row px-3  @if($books->count() > 0) row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 @else d-flex justify-content-center @endif">
                    @forelse ($books as $book)
                        @php
                            if($book->isBorrowed)
                            {
                                $nextdate = $book->borrowedBooks->where('is_valid', '1')->where('user_id',$user->id)->first()->borrowed_nextdate;
                                $enddate  = $book->borrowedBooks->where('is_valid', '1')->where('user_id',$user->id)->first()->borrowed_enddate;
                            }
                            else {
                                $nextdate =0;
                            }
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
                                        @if($book->isBorrowed && $enddate > now())
                                            <li><a href="{{route('user.book.details', $book->slug)}}" class="icon-link"
                                                   title="View"><i class="icon fas fa-eye"></i></a></li>
                                        @else
                                            <li><a href="{{route('user.book.details', $book->slug)}}" class="icon-link"
                                                   title="View"><i class="icon fas fa-eye"></i></a></li>
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
                                                @if($book->isBorrowed && $book->isBorrowedValid)
                                                    <button class="btn btn-sm pe-none dark">Borrowed</button>
                                                @elseif($book->isBorrowed && $book->next_date >= now())
                                                    <button title="This book will be available for access on {{$nextdate}}."
                                                            class="pointer btn btn-sm bg-secondary">Locked
                                                    </button>
                                                @else
                                                    <a href="{{ route('user.book.borrowed.store', $book->id) }}"
                                                    onclick="return confirm('Are you sure you want to borrow this book?')"
                                                    class="btn btn-sm bg-secondary">Borrow</a>
                                                @endif
                                            @else
                                                <a href="{{ route('user.book.borrowed.store', $book->id) }}" title="Borrow"
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
                </div>
                <div class="row d-flex justify-content-center">
                    {{ $books->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $('.select1').select2();
        $('.select2').select2();
    </script>
@endpush
