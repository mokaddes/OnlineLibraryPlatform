@extends('admin.layouts.user')
@section('my_readers', 'active')
@section('books', 'active menu-open')
@section('title')
    {{ $title ?? '' }}
@endsection
@push('style')
    <style>
    </style>
@endpush
@section('content')
    <div class="content-wrapper mt-3 pb-4">
        <div class="content">
            <div class="container-fluid">
                <div class="row px-2 mb-4">
                    <h4>{{ $title }}</h4>
                </div>
                <div class="card mt-4" style="border: 1px solid#E6EDFF;">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div>
                                <h3 class="card-title">All Readers</h3>
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
                            @if(isset($readers) && $readers->count() > 0)
                                @foreach ($readers as $reader)
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
            </div>
        </div>
    </div>
@endsection
@push("script")
@endpush
