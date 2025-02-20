@extends('admin.layouts.user')
@section('borrowed_book', 'active')
@section('books', 'active menu-open')
@section('title') {{ $title ?? 'Library' }} @endsection
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
    .pe-none {
        pointer-events: none !important;
    }
    .content-color {
    background-color: #fff !important;
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
                <h4>@if ($user->role_id == 3) All Books @else Borrowed Books @endif</h4>
            </div>

            <div class="row px-3  @if($books->count() > 0) row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 @else d-flex justify-content-center @endif">
                @forelse ($books as $borrowed)
                    <div class="col mb-3">
                        <div class="card text-center position-relative">
                            <div class="card-icon position-absolute">
                                <ul>
                                    <li><a href="#" class="pe-none icon-link" title="Unlock"><i class="icon fas fa-unlock"></i></a></li>
                                    <li><a href="{{route('user.book.details', $borrowed->book->slug)}}" class="icon-link" title="View"><i class="icon fas fa-eye"></i></a></li>
                                    <li>
                                        <a href="{{ route('user.book.favorite.store', $borrowed->product_id) }}" title="Favourite"
                                        class="icon-link">
                                            <i class="icon fas fa-heart {{ is_favorite($borrowed->product_id) ? 'text-danger' : '' }}"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="book_img">
                                <a href="{{ route('user.book.details', $borrowed->book->slug) }}">
                                    <img src="{{ asset(file_exists($borrowed->book->thumb) ? $borrowed->book->thumb : 'assets/default.svg') }}" class="book-image img-fluid"
                                        alt="">
                                </a>
                            </div>
                            <div class="book_content">
                                <h4 class="py-1">
                                    <a href="{{ route('user.book.details', $borrowed->book->slug) }}">{{ textLimit($borrowed->book->title) }}</a>
                                </h4>
                                @if($borrowed->is_institution == 0)
                                    <p class="badge badge-info"> Valid till - {{ $borrowed->is_bought == 1 ? 'Lifetime' : date('d M Y', strtotime($borrowed->borrowed_enddate)) }} </p>
                                @endif
                            </div>
                            <div class="text-center review mt-3">
                                <div class="comment-text">
                                        <span class="username">
                                            <span class="text-muted">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $borrowed->book->AvgReview())
                                                        <span><i class="fa fa-star text-warning"></i></span>
                                                    @else
                                                        <span><i class="far fa-star text-warning"></i></span>
                                                    @endif
                                                @endfor
                                                ({{  $borrowed->book->reviews()->count() }})
                                            </span>
                                        </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center mt-sm-5">
                        <img class="img-fluid empty" src="{{ asset('assets/frontend/images/nodata.jpg')}}" alt="no-data">
                    {{-- <p class="no-data">Sorry, we couldn't find any data.</p>  --}}
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
@push("script")
<script>
</script>
@endpush
