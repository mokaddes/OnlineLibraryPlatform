@extends('admin.layouts.master')
@section('books', 'active')
@section('library_menu', 'active menu-open')
@section('title')
    {{ $data['title'] ?? 'Analytic Book' }}
@endsection
@push('style')

@endpush
@section('content')
    <div class="content-wrapper mt-3 pb-4">
        <div class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="card-title">Analytics of {{ $product->title }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row d-flex justify-content-between">
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box" id="box_one">
                                    <div class="info-box-content text-center">
                                        <span class="info_number"
                                              style="color:#38E769;">{{ $product->borrowedBooks()->count() }}</span>
                                        <span class="">Borrowed Books</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box" id="box_two">
                                    <div class="info-box-content text-center">
                                        <span class="info_number"
                                              style="color:#584af8;">{{ $product->productViews()->where('progress', 100)->count() }}</span>
                                        <span class="">Completed Views</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box" id="box_zero">
                                    <div class="info-box-content text-center">
                                        <span class="info_number"
                                              style="color:#584af8;">{{ $product->productViews()->sum('total_view') }}</span>
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
{{--                                    <div>--}}
{{--                                        <form action="{{ route('admin.book.analytic.status', $view->id) }}" method="post">--}}
{{--                                            @csrf--}}

{{--                                            <div class="">--}}
{{--                                                <div class="input-group mb-3">--}}
{{--                                                    <label class="col-form-label mr-2 text-info" title="You can make complete all view in a time if stay time percent is more than completion percent">Update status *</label> <!-- Add col-form-label class to the label and margin class to add space -->--}}
{{--                                                    <input type="number" name="complete_percent" id="complete_percent" aria-label="Recipient's username"--}}
{{--                                                           placeholder="Completion percent" aria-describedby="button-addon2"--}}
{{--                                                           class="form-control" required> <!-- Add form-control class to the input field -->--}}
{{--                                                    <div class="input-group-append">--}}
{{--                                                        <button class="input-group-text" type="submit" id="button-addon2">Update</button> <!-- Use btn classes for the button -->--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </form>--}}
{{--                                    </div>--}}
                                </div>
                            </div>
                            <div class="px-2 table-responsive">
                                <table class="table  dataTables">
                                    <thead>
                                    <th>SL</th>
                                    <th>Viewed By</th>
                                    <th>Total Views</th>
                                    <th>Last View At</th>
                                    <th>Progress</th>
                                    @if($product->file_type == 'pdf')
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    @endif

                                    </thead>
                                    <tbody>
                                    @if(isset($product->productViews) && $product->productViews()->count() > 0)
                                        @foreach ($product->productViews()->latest('last_view')->get() as $viewed)
                                            @if(isset($viewed->book))
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $viewed->user->name ?? 'N/A' }} {{ $viewed->user->last_name ?? '' }}</td>
                                                    <td>{{ $viewed->total_view ?? 0 }}</td>
                                                    <td>{{ date('d M, Y \a\t H:i A', strtotime($viewed->last_view ?? $viewed->updated_at)) }}</td>
                                                    <td>{{ $viewed->progress ?? 0 }}%</td>
                                                    @if($product->file_type == 'pdf')
                                                        <td class="text-center">
                                                            <div class="dropdown">
                                                                <button
                                                                    class="btn btn-sm btn-{{ $viewed->status == 1 ? 'success' : 'warning' }} dropdown-toggle"
                                                                    type="button" id="dropdownMenuButton"
                                                                    data-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false">
                                                                    @if($viewed->status == 1)
                                                                        Complete
                                                                    @else
                                                                        In Progress
                                                                    @endif
                                                                </button>
                                                                <div class="dropdown-menu p-0"
                                                                     aria-labelledby="dropdownMenuButton">
                                                                    @if($viewed->status == 1)
                                                                        <a class="dropdown-item text-warning"
                                                                           href="{{ route('admin.book.analytic.status', ['id' => $viewed->id, 'status' => 0]) }}"
                                                                           onclick="return confirm('Are you sure to change view status?')">In
                                                                            Progress</a>
                                                                    @else
                                                                        <a class="dropdown-item text-success"
                                                                           href="{{ route('admin.book.analytic.status', ['id' => $viewed->id, 'status' => 1]) }}"
                                                                           onclick="return confirm('Are you sure to change view status?')">Complete</a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="{{ route('admin.book.analytic.details', $viewed->id) }}"
                                                               class="btn btn-info btn-sm">View</a>
                                                        </td>
                                                    @endif
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
        </div>
    </div>
@endsection
@push("script")
@endpush
