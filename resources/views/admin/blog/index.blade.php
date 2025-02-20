@extends('admin.layouts.master')
@section('blog', 'active')
@section('blog_menu', 'active menu-open')
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
    <div class="content-wrapper mt-3">
        <div class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="card-title">All Blog</h3>
                            </div>
                            <div class="">
                                <div class="d-flex align-items-center">
                                    <span class="mr-2">Posted By:</span>
                                    <form id="filterForm" action="{{ route('admin.blog.index') }}">
                                        <select name="posted_by" id="posted_by" class="form-control">
                                            <option value="all" {{ Request::has('posted_by') ? 'all' : 'selected' }}>All
                                            </option>
                                            <option value="0"
                                                {{ Request::input('posted_by') == '0' ? 'selected' : '' }}>Admin</option>
                                            <option value="1"
                                                {{ Request::input('posted_by') == '1' ? 'selected' : '' }}>Users</option>
                                        </select>

                                    </form>
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('admin.blog.create') }}" class="btn btn-sm btn-light"
                                    style="border: 1px solid #F1F1F1">Add New</a>
                            </div>
                        </div>
                    </div>
                    <div class="px-2 table-responsive">
                        <table class="table table-hover dataTables">
                            <thead>
                                <th class="text-center">Serial No.</th>
                                <th class="text-center" width="10%">Image</th>
                                <th>Title</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Total Reply</th>
                                <th class="text-center">Action</th>
                            </thead>
                            <tbody>
                                @foreach ($data['rows'] as $key => $row)
                                    <tr>
                                        <td class="text-center">{{ $key + 1 }}</td>
                                        <td class="text-center">
                                            @if ($row->image)
                                                <img src="{{ asset($row->image) }}" alt="{{ $row->title }}" width="50"
                                                    height="50" style="border-radius: 20%;">
                                            @else
                                                <span class="badge badge-info">N/A</span>
                                            @endif
                                        </td>
                                        <td><a
                                                href="{{ route('frontend.blogs.details', ['slug' => $row->slug]) }}">{{ $row->title }}</a>
                                        </td>
                                        <td class="{{ $row->status == 1 ? 'text-success' : 'text-danger' }} text-center">
                                            <div>
                                                <span class="mr-1">&#9679;
                                                    {{ $row->status == 1 ? 'Active' : 'Inactive' }}</span>
                                                @if ($row->status == 1)
                                                    <a href="{{ route('admin.blog-post.inactive', $row->id) }}"
                                                        data-id="{{ $row->id }}" data-status="0"
                                                        class="statusUpdate text-danger btn btn-sm btn-light"
                                                        title="Deactivated" onclick="return confirm('Are you sure to Deactivated this Blog ?')">
                                                        <i class="fa fa-thumbs-down"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ route('admin.blog-post.active', $row->id) }}"
                                                        data-id="{{ $row->id }}" data-status="1"
                                                        class="statusUpdate text-success btn btn-sm btn-light"
                                                        title="Activated" onclick="return confirm('Are you sure to Activated this Blog ?')">
                                                        <i class="fa fa-thumbs-up"></i>
                                                    </a>
                                                @endif

                                            </div>

                                        </td>
                                        <td class="text-center">{{ $row->comments_count }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.blog.edit', $row->id) }}" title="Edit"
                                                class="btn btn-sm" style="background: #4D1DD4">
                                                <i class="fas fa-pen" style="color: #ffffff;"></i>
                                            </a>
                                            <a href="{{ route('admin.blog.delete', $row->id) }}" title="Delete"
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
    <script type="text/javascript">
        $(document).ready(function() {
            $('.statusUpdate').click(function() {
                var postId = $(this).data('id');
                var status = $(this).data('status');
                var url = $(this).attr('href');
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: postId
                    },
                    success: function(data) {
                        window.location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });

        $(document).ready(function() {
            $('#posted_by').change(function() {
                $('#filterForm').submit();
            });
        });
    </script>
@endpush
