@extends('admin.layouts.master')
@section('club', 'active')
@section('title')
    {{ $data['title'] ?? '' }}
@endsection
@push('style')
    <style>
        .status-badge {
            font-size: 0.3rem;
            font-weight: 300;
            padding: 0px 2px;
        }

        .btn {
            width: 80px;
            margin-top: 10px;
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
                                <h3 class="card-title" style="font-size: 1.3rem;">All Clubs</h3>
                            </div>
                            <div>
                                <a href="{{ route('admin.club.create') }}" class="btn btn-sm btn-light"
                                   style="border: 1px solid #F1F1F1">Add New</a>
                            </div>
                        </div>
                    </div>
                    <div class="px-2 table-responsive">
                        <table class="table table-hover dataTables">
                            <thead>
                            <th class="text-center">Serial No.</th>
                            <th class="text-center">Image</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Created By</th>
                            <th class="text-center">Created At</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                            </thead>
                            <tbody>
                            @foreach ($data['rows'] as $key => $row)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td class="text-center">
                                        @if($row->profile_photo)
                                            <a href="{{asset($row->profile_photo)}}"><img
                                                    src="{{ asset($row->profile_photo) }}" alt="{{ $row->title }}"
                                                    width="50" height="50" style="border-radius: 20%;">
                                            </a>
                                        @else
                                            <span class="badge badge-info">N/A</span>
                                        @endif
                                    </td>
                                    <td>{{ $row->title }}</td>
                                    <td class="text-center">
                                        {{ $row->clubAdmin->name ?? '' }}
                                    </td>
                                    <td class="text-center">{{ $row->created_at }}</td>

                                    <td class="text-center {{ $row->status == '1' ? 'text-success' : ($row->status == '0' ? 'text-warning' : 'text-danger' ) }}">
                                        &#9679; {{
                                                $row->status == '1' ? 'Active' :
                                                ($row->status == '0' ? 'Pending' :
                                                ($row->status == '2' ? 'Deactivated' : 'Rejected'))
                                            }}
                                    </td>
                                    <td class="text-center">
                                        @if($row->status == '1')
                                            <a href="{{ route('admin.club.status.change', ['id' => $row->id, 'status' => '2']) }}"
                                               onclick="return confirm('Are your sure to change?')"
                                               class="btn btn-sm btn-danger">Deactive</a>
                                            <a href="{{ route('admin.club.view', ['id' => $row->id]) }}"
                                                class="btn btn-sm btn-info">View</a>
                                        @elseif($row->status == '0')
                                            <a href="{{ route('admin.club.status.change', ['id' => $row->id, 'status' => '1']) }}"
                                               onclick="return confirm('Are your sure to change?')"
                                               class="btn btn-sm btn-success">Approve</a>
                                            <a href="{{ route('admin.club.status.change', ['id' => $row->id, 'status' => '3']) }}"
                                               onclick="return confirm('Are your sure to change?')"
                                               class="btn btn-sm btn-danger">Reject</a>
                                        @else
                                            <a href="{{ route('admin.club.status.change', ['id' => $row->id, 'status' => '1']) }}"
                                               onclick="return confirm('Are your sure to change?')"
                                               class="btn btn-sm btn-success">Active</a>
                                        @endif
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
