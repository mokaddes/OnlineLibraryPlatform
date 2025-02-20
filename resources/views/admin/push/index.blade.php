@extends('admin.layouts.master')
@section('push', 'active')
@section('title') {{ $title ?? '' }} @endsection
@push('style')
<style>
.status-badge {
    font-size: 0.3rem;
    font-weight: 300;
    padding: 0 2px;
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
                                <h3 class="card-title" >All Push Notifications</h3>
                            </div>
                            <div>
                                <a href="{{ route('admin.push-notification.create') }}" class="btn btn-sm btn-light"
                                style="border: 1px solid #F1F1F1">Add New</a>
                            </div>
                        </div>
                    </div>
                    <div class="px-2 table-responsive">
                        <table class="table table-hover dataTables">
                            <thead>
                                <th class="text-center">SL.</th>
                                <th class="text-center">Title</th>
                                <th class="text-center">Message</th>
                                <th class="text-center">Url</th>
                                <th class="text-center">Success Rate</th>
                                <th class="text-center">Total Resend</th>
                                <th class="text-center">Total User</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </thead>
                            <tbody>
                            @foreach($notifications as $notify)
                                <tr class="text-center">
                                    <td> {{ $loop->iteration }} </td>
                                    <td> {{ $notify->title }} </td>
                                    <td> {{ $notify->body }} </td>
                                    <td> {{ $notify->url }} </td>
                                    <td> {{ $notify->total_success/count($notify->user_ids) }}</td>
                                    <td> {{ $notify->total_send }} </td>
                                    <td> {{ count($notify->user_ids) }} </td>
                                    <td>
                                        @if($notify->status == 1)
                                            <span class="text-success"> &#9679; Active</span>
                                        @else
                                            <span class="text-danger"> &#9679; Inactive</span>
                                        @endif
                                    </td>
                                    <td>

                                        <a href="{{ route('admin.push-notification.send', $notify->id) }}"
                                            class="btn btn-sm btn-success" title="Send Notification">
                                            <i class="fas fa-paper-plane" style="color: #ffffff;"></i>
                                        </a>

                                        <a href="{{ route('admin.push-notification.view', $notify->id) }}"
                                            class="btn btn-sm btn-primary" title="View Notification">
                                            <i class="fas fa-eye" style="color: #ffffff;"></i>
                                        </a>
                                        <a href="{{ route('admin.push-notification.edit', $notify->id) }}"
                                            class="btn btn-sm btn-info" title="Edit">
                                            <i class="fas fa-edit" style="color: #ffffff;"></i>
                                        </a>
                                        <a href="{{ route('admin.push-notification.delete', $notify->id) }}" onclick="return confirm('Are your sure to delete notification?')"
                                            class="btn btn-sm btn-danger" title="Delete">
                                            <i class="fas fa-trash" style="color: #ffffff;"></i>
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
@push("script")
@endpush
