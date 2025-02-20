@extends('admin.layouts.user')
@section('user', 'active')
@section('title')
    Analytic Book
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
                    <h4>Analytics of {{ $product->title }}</h4>
                </div>
                <div class="row d-flex justify-content-between">
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box" id="box_one">
                            <div class="info-box-content text-center">
                                <span class="info_number" style="color:#38E769;">{{ $product->borrowedBooks()->count() }}</span>
                                <span class="">Borrowed Books</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box" id="box_two">
                            <div class="info-box-content text-center">
                                <span class="info_number" style="color:#584af8;">{{ $product->productViews()->where('progress', 100)->count() }}</span>
                                <span class="">Completed Views</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box" id="box_zero">
                            <div class="info-box-content text-center">
                                <span class="info_number" style="color:#584af8;">{{ $product->productViews()->sum('total_view') }}</span>
                                <span class="">Total Views</span>
                            </div>
                        </div>
                    </div>

                </div>


                <div class="card mt-4" style="border: 1px solid#E6EDFF;">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="card-title">Viewed books</h3>
                            </div>
                        </div>
                    </div>
                    <div class="px-2 table-responsive">
                        <table class="table  dataTables">
                            <thead>
                            <th>SL</th>
                            <th>Viewed By</th>
                            <th>Total Views</th>
                            <th>Last View At</th>
                            <th>Completion</th>
                            </thead>
                            <tbody>
                            @if(isset($product->productViews) && $product->productViews()->count() > 0)
                                @foreach ($product->productViews()->latest('updated_at')->get() as $viewed)
                                    @if(isset($viewed->book))
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $viewed->user->name ?? 'N/A' }} {{ $viewed->user->last_name ?? '' }}</td>
                                            <td>{{ $viewed->total_view ?? 0 }}</td>
                                            <td>{{ date('d M, Y \a\t H:i A', strtotime($viewed->updated_at)) }}</td>
                                            <td>{{ $viewed->progress ?? 0 }}%</td>
                                        </tr>
                                    @endif
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
@push('script')
@endpush
