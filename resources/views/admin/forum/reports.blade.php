@extends('admin.layouts.master')
@section('forumReport', 'active')
@section('forum_menu', 'active menu-open')
@section('title') {{ $title ?? '' }} @endsection
@push('style')
    <style>
        .status-badge {
            font-size: 0.3rem;
            font-weight: 300;
            padding: 0px 2px;
        }
        th {
            font-weight: normal !important;
        }

    </style>
@endpush
@section('content')
    <div class="content-wrapper mt-3" style="background: #FFFFFF;">
        <div class="content">
            <div class="container-fluid">
                <div class="card" style="border: 1px solid#E6EDFF;">
                    <div class="card-header" style="border-bottom:none !important; background: #ebeefc91;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="card-title" style="font-size: 1.3rem;">User Reports</h3>
                            </div>
                        </div>
                    </div>
                    <div class="px-2 table-responsive">
                        <table class="table table-hover dataTables">
                            <thead>
                            <th class="text-center">SL.</th>
                            <th class="text-center">Reporter User</th>
                            <th class="text-center">Reported User</th>
                            <th class="text-center">Message</th>
                            <th class="text-center">Report Time</th>
                            </thead>
                            <tbody>
                            @foreach($reports as $report)
                                <tr class="text-center">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $report->reporter->name ?? 'N/A' }} {{ $report->reporter->last_name ?? '' }}</td>
                                    <td>{{ $report->reported->name ?? 'N/A' }} {{ $report->reported->last_name ?? '' }}</td>
                                    <td>{{ $report->message }}</td>
                                    <td>{{ date('d M Y \a\t H:i A',strtotime($report->created_at)) }}</td>
                                </tr>
                            @endforeach
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
