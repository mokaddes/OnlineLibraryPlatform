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
                                <h3 class="card-title">Analytics of {{ $view->book->title }}</h3>
                            </div>
                            <div>
                                <h3 class="card-title">User: {{ $view->user->name }} {{ $view->user->last_name }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row d-flex justify-content-between">
                            <div class="col-12 col-sm-4 col-md-2">
                                <div class="info-box" id="box_two">
                                    <div class="info-box-content text-center">
                                        <span class="info_number"
                                              style="color:#584af8;">{{ $view->total_page ?? 0 }}</span>
                                        <span class="">Total Page</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4 col-md-2">
                                <div class="info-box" id="box_zero">
                                    <div class="info-box-content text-center">
                                        <span class="info_number"
                                              style="color:#af13ca;">{{ $view->page_views()->count() }}</span>
                                        <span class="">Reading Page</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4 col-md-2">
                                <div class="info-box" id="box_one">
                                    <div class="info-box-content text-center">
                                        <span class="info_number"
                                              style="color:#0be06e;">{{ $view->page_views()->where('status', 1)->count() }}</span>
                                        <span class="">Completed Page</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4 col-md-2">
                                <div class="info-box" id="box_two">
                                    <div class="info-box-content text-center">
                                        <span class="info_number"
                                              style="color:#584af8;">{{ timeToDisplay($view->total_time) }}</span>
                                        <span class="">Total Time</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4 col-md-2">
                                <div class="info-box" id="box_zero">
                                    <div class="info-box-content text-center">
                                        <span class="info_number"
                                              style="color:#af13ca;">{{ timeToDisplay($view->stay_time) }}</span>
                                        <span class="">Spend Time</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4 col-md-2">
                                <div class="info-box" id="box_one">
                                    <div class="info-box-content text-center">
                                        <span class="info_number"
                                              style="color:#0be06e;">{{ $view->progress }}%</span>
                                        <span class="">Progress</span>
                                    </div>
                                </div>
                            </div>

                        </div>



                        <div class="card mt-4" style="border: 1px solid#E6EDFF;">
                            <div class="card-header">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h3 class="card-title">Viewed pages</h3>
                                    </div>
                                    <div>
                                        <form action="{{ route('admin.book.analytic.status', $view->id) }}" method="post">
                                            @csrf

                                            <div class="">
                                                <div class="input-group mb-3">
                                                    <label class="col-form-label mr-2 " title="You can make complete all page in a time if stay time percent is more than Page completion percent">Update status
                                                        (Please give stay percentage & click update button. Above all read count status will be completed)
                                                    </label>
                                                    <input type="number" step="any" name="page_complete_percent" id="page_complete_percent" aria-label="Recipient's username"
                                                           placeholder="Page completion percent" aria-describedby="button-addon2"
                                                           class="form-control" required> <!-- Add form-control class to the input field -->
                                                    <div class="input-group-append">
                                                        <button class="input-group-text" type="submit" id="button-addon2">Update</button> <!-- Use btn classes for the button -->
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                            <div class="px-2 table-responsive">
                                <table class="table  dataTables">
                                    <thead>
                                    <th>Page No</th>
                                    <th>Total Time</th>
                                    <th>Page Stay time</th>
                                    <th>Stay Percent</th>
                                    <th>Total View</th>
                                    <th class="text-center">Status</th>

                                    </thead>
                                    <tbody>
                                    @if(isset($view->page_views) && $view->page_views()->count() > 0)
                                        @foreach ($view->page_views()->oldest('page_no')->get() as $viewed)
                                            <tr>
                                                <td>{{ $viewed->page_no }}</td>
                                                <td>{{ timeToDisplay($viewed->page_total_time) }} </td>
                                                <td>{{ timeToDisplay($viewed->page_stay_time) }} </td>
                                                <td>{{ number_format(($viewed->page_stay_time / $viewed->page_total_time)*100, 2) }}
                                                    %
                                                </td>
                                                <td>{{ $viewed->total_view }}</td>
                                                <td class="text-center">
                                                    <div class="dropdown">
                                                        <button
                                                            class="btn btn-sm btn-{{ $viewed->status == 1 ? 'success' : 'warning ' }} dropdown-toggle"
                                                            type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false">
                                                            @if($viewed->status == 1)
                                                                Completed
                                                            @else
                                                                In Progress
                                                            @endif
                                                        </button>
                                                        <div class="dropdown-menu p-0"
                                                             aria-labelledby="dropdownMenuButton">
                                                            @if($viewed->status != 1)
                                                                <a class="dropdown-item text-success"
                                                                   href="{{ route('admin.book.page.status', ['id' => $viewed->id, 'status' => 1]) }}"
                                                                   onclick="return confirm('Are you sure to change view status?')">Complete</a>
                                                            @endif
                                                            <a class="dropdown-item text-danger"
                                                               href="{{ route('admin.book.page.status', ['id' => $viewed->id, 'status' => 0]) }}"
                                                               onclick="return confirm('Are you sure you want to delete?')">Delete</a>
                                                        </div>
                                                    </div>


                                                </td>
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
        </div>
    </div>
@endsection
@push("script")
@endpush
