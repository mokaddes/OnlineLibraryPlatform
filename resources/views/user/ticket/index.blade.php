@extends('admin.layouts.user')
@section('ticket', 'active')
@section('title') {{ $title ?? '' }} @endsection
@push('style')
    <style>
    </style>
@endpush
@section('content')
    <div class="content-wrapper mt-3 pb-4" >
        <div class="content">
            <div class="container-fluid">
                <div class="row px-2 mb-4">
                    <h4>{{ $title }}</h4>
                </div>
                <div class="card mt-4" style="border: 1px solid#E6EDFF;">
                    <div class="card-header" >
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="card-title" >All Tickets</h3>
                            </div>
                            <div>
                                <a href="{{ route('user.ticket.create') }}" class="btn btn-sm" id="custom_btn">Create New Ticket</a>
                            </div>
                        </div>
                    </div>
                    <div class="px-2 table-responsive">
                        <table class="table  dataTables">
                            <thead>
                                <th>SL</th>
                                <th>Subject</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Last update</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                @foreach ($tickets as $key => $row)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $row->subject }}</td>
                                        <td>
                                            @if ($row->priority == 1)
                                                <span >Low</span>
                                            @elseif ($row->priority == 2)
                                                <span >Medium</span>
                                            @elseif ($row->priority == 3)
                                                <span>High</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($row->status == 1)
                                                <span class="badge badge-pill badge-secondary">Open</span>
                                            @elseif ($row->status == 2)
                                                <span class="badge badge-pill badge-success">Answered</span>
                                            @elseif ($row->status == 3)
                                                <span class="badge badge-pill badge-info">Replied</span>
                                            @else
                                                <span class="badge badge-pill badge-danger">Closed</span>
                                            @endif
                                        </td>
                                        <td>{{ date('d-M-y H:i:s', strtotime($row->updated_at)) }}</td>
                                        <td>
                                            <a href="{{ route('user.ticket.view', $row->pk_no) }}" class="btn btn-sm"
                                                style="background: #4D1DD4">
                                                @if($row->unread_count > 0)
                                                    <span class="badge badge-light">{{$row->unread_count}}</span>
                                                @endif
                                                <i class="fas fa-share" style="color: #ffffff;"></i>
                                            </a>
                                            <a href="{{ route('user.ticket.delete', $row->pk_no) }}"
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
