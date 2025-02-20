@extends('admin.layouts.master')
@section('books', 'active')
@section('library_menu', 'active menu-open')
@section('title')
    {{ $title ?? '' }}
@endsection
@push('style')
    <style>

        .data-container {
            background-color: #f4f4f4;
            padding: 10px;
            border: 1px solid #ddd;
            overflow-x: auto;
            min-height: 100px; /* Set your desired minimum height */
        }

        .no-data-message {
            text-align: center;
            font-size: 18px;
            color: #555;
        }
    </style>
@endpush
@section('content')
    <div class="content-wrapper mt-3">
        <div class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="card-title">{{$heading ?? 'MARC Data'}}</h3>
                            </div>
                            <div>
                                <a href="{{ route('admin.book.index') }}" class="btn btn-sm btn-light"
                                   style="border: 1px solid #F1F1F1">Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="px-2 data-container">
                        @if (empty($marcData))
                           <div class="mt-3">
                               <p class="no-data-message">No data available</p>
                           </div>
                        @else
<pre>
   {{ json_encode(json_decode($marcData), JSON_PRETTY_PRINT) }}
</pre>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push("script")
    <script>
        $(document).ready(function () {
            $('.statusSwitch').change(function () {
                var status = $(this).prop('checked') ? 1 : 0;
                var route = $(this).data('route');
                console.log(route);
                var confirmTxt = 'At a time only one book active to book of the month. Are you sure you to want?'
                if (confirm(confirmTxt)) {
                    window.location.href = route;
                }
                $(this).prop('checked', false);
                return false;

            });
        });
    </script>
@endpush
