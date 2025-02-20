@extends('admin.layouts.user')
@section('user', 'active')
@section('title')
    {{ $title ?? '' }}
@endsection
@push('style')
    <style>
    </style>
@endpush
@php
    $plan = $user->currentUserPlan->package->title ?? null;
@endphp
@section('content')
    <div class="content-wrapper mt-3 pb-4">
        <div class="content">
            <div class="container-fluid">
                <div class="row px-2 mb-4">
                    <h4>Dashboard</h4>
                </div>
                <div class="row d-flex justify-content-between">
                    @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 3)
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box" id="box_one">
                                <div class="info-box-content text-center">
                                    <span class="info_number" style="color:#38E769;">{{ $total_borrowed_books }}</span>
                                    <span
                                        class="">{{ auth()->user()->role_id == 1 ? 'Borrowed' : 'Issued'}} Books</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box" id="box_two">
                                <div class="info-box-content text-center">
                                    <span class="info_number" style="color:#584af8;">{{ $total_viewed_books }}</span>
                                    <span class="">Book Views</span>
                                </div>

                            </div>
                        </div>
                        @if(Auth::user()->role_id == 3)
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box" id="box_one">
                                    <div class="info-box-content text-center">
                                        <span class="info_number" style="color:#38E769;">{{ $total_fav_books }}</span>
                                        <span class="">Favourite Books</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box" id="{{ ($subscription_remaining > 10 && $subscription_remaining <= 365) ? 'box_one' : 'box_three' }}">
                                    <div class="info-box-content text-center">
                                        @if($plan != null && $subscription_remaining <= 365)
                                        <span class="info_number {{ ($subscription_remaining > 10 && $subscription_remaining <= 365) ? 'text-success' : 'text-danger' }}">
                                                    {{ $subscription_remaining }}
                                            </span>
                                            <span class="">@if($user->currentUserPlan->expired_date > now())
                                                    Subscription Remaining
                                                    ({{ $user->currentUserPlan->package->title ?? '' }})
                                                @else
                                                    Subscription Expired
                                                    ({{ $user->currentUserPlan->package->title ?? '' }})
                                                @endif
                                            </span>
                                        @else
                                            <span class="info_number text-danger" style="font-size: 24px !important;">
                                                Free Subscription
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @elseif(auth()->user()->role_id == 2)
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box" id="box_zero">
                                <div class="info-box-content text-center">
                                    <span class="info_number" style="color:#800080;">{{ $my_books->count() }}</span>
                                    <span class="">My Books</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box" id="box_one">
                                <div class="info-box-content text-center">
                                    <span class="info_number"
                                          style="color:#38E769;">{{ $pending_books->count() }}</span>
                                    <span class="">Pending Books</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box" id="box_two">
                                <div class="info-box-content text-center">
                                    <span class="info_number"
                                          style="color:#584af8;">{{ $decline_books->count() }}</span>
                                    <span class="">Declined Books</span>
                                </div>

                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box" id="box_three">
                                <div class="info-box-content text-center">
                                    <span class="info_number" style="color:#ff0000;">{{ $readers_count }}</span>
                                    <span class="">My Readers</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>


                @if (auth()->user()->role_id == 1)
                    <div class="card mt-4" style="border: 1px solid#E6EDFF;">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h3 class="card-title">Last viewed books</h3>
                                </div>
                            </div>
                        </div>
                        <div class="px-2 table-responsive">
                            <table class="table  dataTables">
                                <thead>
                                <th>Book</th>
                                <th>Publisher</th>
                                <th>Publisher Year</th>
                                <th>ISBN</th>
                                <th>Completion</th>
                                </thead>
                                <tbody>
                                @if(isset($last_viewed_books) && $last_viewed_books->count() > 0)
                                    @foreach ($last_viewed_books as $viewed)
                                        @if(isset($viewed->book))
                                            <tr>
                                                <td>
                                                    <a href="{{ route('user.book.details', $viewed->book->slug) }}">
                                                        <img
                                                            src="{{ asset(file_exists($viewed->book->thumb) ? $viewed->book->thumb : 'assets/default.svg') }}"
                                                            alt="{{$viewed->book->title}}" style="width: 50px;">
                                                    </a>
                                                </td>
                                                <td>{{ $viewed->book->publisher ?? 'N/A' }}</td>
                                                <td>{{ $viewed->book->publisher_year ?? 'N/A'}}</td>
                                                <td>ISBN-10: {{$viewed->book->isbn10 ?? 'N/A'}} <br>
                                                    ISBN-13: {{$viewed->book->isbn13 ?? 'N/A'}}
                                                </td>
                                                <td>{{ $viewed->progress ?? 0 }}%</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card mt-4">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h3 class="card-title">Last borrowed books</h3>
                                </div>
                                <div>
                                    <a href="{{ route('user.book.borrowed') }}" class="btn btn-sm" id="custom_btn">View
                                        All</a>
                                </div>
                            </div>
                        </div>
                        <div class="px-2 table-responsive">
                            <table class="table  dataTables">
                                <thead>
                                <th>Book</th>
                                <th>Publisher</th>
                                <th>Publisher Year</th>
                                <th>ISBN</th>
                                </thead>
                                <tbody>
                                @if(isset($last_borrowed_books) && $last_borrowed_books->count() > 0)
                                    @foreach ($last_borrowed_books as $bor)
                                        @if(isset($bor->book))
                                            <tr>
                                                <td>
                                                    <a href="{{ route('user.book.details', $bor->book->slug) }}">
                                                        <img
                                                            src="{{ asset(file_exists($bor->book->thumb) ? $bor->book->thumb : 'assets/default.svg') }}"
                                                            alt="{{$bor->book->title}}" style="width: 50px;">
                                                    </a>
                                                </td>
                                                <td>{{ $bor->book->publisher ?? 'N/A' }}</td>
                                                <td>{{ $bor->book->publisher_year ?? 'N/A'}}</td>
                                                <td>ISBN-10: {{$bor->book->isbn10 ?? 'N/A'}} <br>
                                                    ISBN-13: {{$bor->book->isbn13 ?? 'N/A'}}
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                @elseif(auth()->user()->role_id == 2)
                    <div class="card mt-4" style="border: 1px solid#E6EDFF;">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h3 class="card-title">Latest Books</h3>
                                </div>
                                <div>
                                    <a href="{{ route('author.books.index') }}" class="btn btn-sm" id="custom_btn">View
                                        All</a>
                                </div>
                            </div>
                        </div>
                        <div class="px-2 table-responsive">
                            <table class="table  dataTables">
                                <thead>
                                <th>Book</th>
                                <th>Publisher</th>
                                <th>Publisher Year</th>
                                <th>ISBN</th>
                                <th>Status</th>
                                <th>Action</th>
                                </thead>
                                <tbody>
                                @if(isset($my_books) && $my_books->count() > 0)
                                    @foreach ($my_books as $latestbooks)
                                        <tr>
                                            <td>
                                                <a href="{{ route('user.book.details', $latestbooks->slug) }}">
                                                    <img
                                                        src="{{ asset(file_exists($latestbooks->thumb) ? $latestbooks->thumb : 'assets/default.svg') }}"
                                                        alt="{{$latestbooks->title}}" style="width: 50px;">
                                                </a>
                                            </td>
                                            <td>{{ $latestbooks->publisher ?? 'N/A' }}</td>
                                            <td>{{ $latestbooks->publisher_year ?? 'N/A'}}</td>
                                            <td>ISBN-10: {{$latestbooks->isbn10 ?? 'N/A'}} <br>
                                                ISBN-13: {{$latestbooks->isbn13 ?? 'N/A'}}
                                            </td>
                                            <td class="{{ $latestbooks->status == 10 ? 'text-success' : ($latestbooks->status == 0 ? 'text-warning' : 'text-danger' ) }}">
                                                &#9679; {{
                                                    $latestbooks->status == 10 ? 'Published' :
                                                    ($latestbooks->status == 0 ? 'Pending' :
                                                    ($latestbooks->status == 20 ? 'Unpublished' :
                                                    ($latestbooks->status == 30 ? 'Rejected' : 'Expired')))
                                                }}
                                            </td>
                                            <td>
                                                <a href="{{ route('author.books.edit',$latestbooks->id) }}"
                                                   class="btn btn-sm" style="background: #4D1DD4">
                                                    <i class="fas fa-pen" style="color: #ffffff;"></i>
                                                </a>
                                                <a href="{{ route('author.books.delete', $latestbooks->id ) }}"
                                                   onclick="return confirm('{{ __('Are you sure want to delete this item') }}')"
                                                   class="btn btn-sm" style="background: #EC2626">
                                                    <i class="fas fa-trash-alt" style="color: #ffffff;"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h3 class="card-title">Top Readers</h3>
                                </div>
                                <div>
                                    <a href="{{ route('author.books.readers') }}" class="btn btn-sm" id="custom_btn"> View
                                        All
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="px-2 table-responsive">
                            <table class="table  dataTables">
                                <thead>
                                <th>Book Cover</th>
                                <th>Book Title</th>
                                <th>Reader Name</th>
                                <th>Reader Email</th>
                                <th>Last Read At</th>
                                <th>Total views</th>
                                </thead>
                                <tbody>
                                @if(isset($top_readers) && $top_readers->count() > 0)
                                    @foreach ($top_readers as $reader)
                                        <tr>
                                            <td>
                                                <img
                                                    src="{{ asset(file_exists($reader->book->thumb) ? $reader->book->thumb : 'assets/default.svg') }}"
                                                    alt="{{$reader->book->title}}" style="width: 50px;">
                                            </td>
                                            <td>{{ $reader->book->title }}</td>
                                            <td>{{ $reader->user->name ?? '' }}{{ $reader->user->last_name ?? '' }}</td>
                                            <td>{{ $reader->user->email ?? '' }}</td>
                                            <td>{{ date('d, M Y H:i A', strtotime($reader->updated_at)) }}</td>
                                            <td>{{ $reader->total_view }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="card mt-4" style="border: 1px solid#E6EDFF;">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h3 class="card-title">Last viewed books</h3>
                                </div>
                            </div>
                        </div>
                        <div class="px-2 table-responsive">
                            <table class="table  dataTables">
                                <thead>
                                <th>Book</th>
                                <th>Publisher</th>
                                <th>Publisher Year</th>
                                <th>ISBN</th>
                                </thead>
                                <tbody>
                                @if(isset($last_viewed_books) && $last_viewed_books->count() > 0)
                                    @foreach ($last_viewed_books as $viewed)
                                        @if(isset($viewed->book))
                                            <tr>
                                                <td>
                                                    <a href="{{ route('user.book.details', $viewed->book->slug) }}">
                                                        <img
                                                            src="{{ asset(file_exists($viewed->book->thumb) ? $viewed->book->thumb : 'assets/default.svg') }}"
                                                            alt="{{$viewed->book->title}}" style="width: 50px;">
                                                    </a>
                                                </td>
                                                <td>{{ $viewed->book->publisher ?? 'N/A' }}</td>
                                                <td>{{ $viewed->book->publisher_year ?? 'N/A'}}</td>
                                                <td>ISBN-10: {{$viewed->book->isbn10 ?? 'N/A'}} <br>
                                                    ISBN-13: {{$viewed->book->isbn13 ?? 'N/A'}}
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@push('script')

@endpush
