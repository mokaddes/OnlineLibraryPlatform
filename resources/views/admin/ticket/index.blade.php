@extends('admin.layouts.master')
@section('ticket', 'active')
@section('title') {{ $data['title'] ?? '' }} @endsection
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
    <div class="content-wrapper mt-3" >
        <div class="content">
            <div class="container-fluid">
                <div class="card" >
                    <div class="card-header" >
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="card-title" >All Tickets</h3>
                            </div>
                        </div>
                    </div>
                    <div class="px-2 table-responsive">
                        <table class="table table-hover dataTables">
                            <thead>
                                <th class="text-center">Serial No.</th>
                                <th>Subject</th>
                                <th class="text-center">Priority</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Last Update</th>
                                <th class="text-center">Action</th>
                            </thead>
                            <tbody>
                                @foreach ($data['rows'] as $key => $row)
                                    <tr>
                                        <td class="text-center">{{ $key + 1 }}</td>
                                        <td>{{ $row->subject }}</td>
                                        <td class="text-center">
                                            @if ($row->priority == 1)
                                                <span>Low</span>
                                            @elseif ($row->priority == 2)
                                                <span>Medium</span>
                                            @elseif ($row->priority == 3)
                                                <span>High</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($row->status == 1)
                                                <span class="text-secondary"> &#9679; Open</span>
                                            @elseif ($row->status == 2)
                                                <span class="text-success"> &#9679; Answered</span>
                                            @elseif ($row->status == 3)
                                                <span class="text-info"> &#9679; Replied</span>
                                            @else
                                                <span class="text-danger"> &#9679; Closed</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ date('d-M-y H:i:s', strtotime($row->updated_at)) }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.ticket.reply', $row->pk_no) }}" class="btn btn-sm" title="Reply"
                                                style="background: #4D1DD4">
                                                @if($row->unread_count >0)<span class="badge badge-light">{{$row->unread_count}}</span>@endif
                                                <i class="fas fa-share" style="color: #ffffff;"></i>
                                            </a>
                                            @if ($row->status == 0)
                                                <a href="{{ route('admin.ticket.reopen', $row->pk_no) }}" title="Open"
                                                    onclick="return confirm('{{ __('Are you sure want to reopen this item') }}')"
                                                    class="btn btn-sm" style="background: #05e3a1">
                                                    <i class="fas fa-check" style="color: #ffffff;"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('admin.ticket.close', $row->pk_no) }}" title="Close"
                                                    onclick="return confirm('{{ __('Are you sure want to close this item') }}')"
                                                    class="btn btn-sm" style="background: #5c5a41">
                                                    <i class="fas fa-close" style="color: #ffffff;"></i>
                                                </a>
                                            @endif
                                            <a href="{{ route('admin.ticket.delete', $row->pk_no) }}" title="Delete"
                                                onclick="return confirm('{{ __('Are you sure want to delete this item') }}')"
                                                class="btn btn-sm" style="background: #EC2626">
                                                <i class="fas fa-trash-alt" style="color: #ffffff;"></i>
                                            </a>
                                        </td>
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
@push('script')
@endpush
