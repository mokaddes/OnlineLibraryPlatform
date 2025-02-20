@extends('admin.layouts.master')
@section('user', 'active')
@section('title')
    {{ $data['title'] ?? '' }}
@endsection
@php
    $countries = Config::get('app.countries');
@endphp
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

        .filter {
            background: rgba(92, 36, 130, 0.075);
            border: 1px solid #F1F1F1
        }

        .filter-active {
            padding: 8px;
            border: 1px solid #10101036;
            background: #ab043124;
        }

        span.select2.select2-container {
            display: block;
            margin-right: 10px !important;
        }

        span.select2.select2-container.select2-container--default {
            width: 170px !important;
        }

        .select2-container--default .select2-selection--single {
            height: 30px !important;
            padding: 2px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 30px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 28px !important;
        }

        @media screen and (max-width: 850px) {
            span.select2.select2-container {
                display: none;
            }
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
                                <h3 class="card-title"> @if($data['type'] == 'author')
                                        All Author
                                    @elseif($data['type'] == 'reader')
                                        All Reader
                                    @else
                                        All Users
                                    @endif</h3>
                            </div>
                            <div class="d-none d-sm-block">
                                <a href="{{ route('admin.user.index') }}"
                                   class="btn btn-sm btn-light filter @if($data['type'] == 'all') filter-active  @endif">All
                                    ({{$data['user_count']}})</a>
                                <a href="{{ route('admin.user.index', ['type' => 'author']) }}"
                                   class="btn btn-sm btn-light filter @if($data['type'] == 'author') filter-active  @endif">Author
                                    ({{$data['author_count']}})</a>
                                <a href="{{ route('admin.user.index', ['type' => 'reader']) }}"
                                   class="btn btn-sm btn-light filter @if($data['type'] == 'reader') filter-active  @endif">Reader
                                    ({{$data['reader_count']}})</a>
                            </div>
                            <div class="d-flex align-items-center">
                                <select class="form-control form-select select2 mb-2 d-none d-sm-block" name="country"
                                        id="country">
                                    <option value="All">All</option>
                                    @foreach ($countries as $key => $value)
                                        <option value="{{$value}}"
                                                @if(Request::get('country') == $value) selected @endif>{{$value}}</option>
                                    @endforeach
                                </select>
                                <a href="{{ route('admin.user.create', ['role' => 'Author']) }}"
                                   class="btn btn-sm btn-light mr-2"
                                   style="border: 1px solid #F1F1F1;width: 115px;">Add Author</a>
                                <a href="{{ route('admin.user.create', ['role' => 'Reader']) }}" class="btn btn-sm btn-light"
                                   style="border: 1px solid #F1F1F1">Add User</a>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-center mt-3 d-block d-sm-none">
                            <a href="{{ route('admin.user.index') }}"
                               class="btn btn-sm btn-light filter @if($data['type'] == 'all') filter-active  @endif">All
                                ({{$data['user_count']}})</a>
                            <a href="{{ route('admin.user.index', ['type' => 'author']) }}"
                               class="btn btn-sm btn-light filter @if($data['type'] == 'author') filter-active  @endif">Author
                                ({{$data['author_count']}})</a>
                            <a href="{{ route('admin.user.index', ['type' => 'reader']) }}"
                               class="btn btn-sm btn-light filter @if($data['type'] == 'reader') filter-active  @endif">Reader
                                ({{$data['reader_count']}})</a>
                        </div>
                    </div>
                    <div class="px-2 table-responsive">
                        <table class="table table-hover dataTables">
                            <thead>
                            <th class="text-center">Serial No.</th>
                            <th class="text-center">Image</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th class="text-center">User Type</th>
                            <th class="text-center">Country</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                            </thead>
                            <tbody>
                            @foreach ($data['rows'] as $key => $row)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td class="text-center">
                                        @if($row->image)
                                            <img src="{{ asset($row->image) }}" alt="{{ $row->name }}"
                                                 width="50" height="50" style="border-radius: 20%;">
                                        @else
                                            <img src="{{ asset('assets/images/default-user.png') }}"
                                                 alt="{{ $row->name }}"
                                                 width="35" height="35" style="border-radius: 50%;">
                                        @endif
                                    </td>
                                    <td>{{$row->name ?? 'N/A'}} {{$row->last_name ?? ''}}
                                        @if($row->country)
                                            @php
                                                $countryCode = array_search($row->country, $countries);
                                            @endphp
                                            <img class="img-fluid ml-2" style="width: 20px;"
                                                 src="https://flagcdn.com/48x36/{{$countryCode}}.png">
                                        @endif
                                    </td>
                                    <td>{{$row->email ?? 'N/A'}}
                                        @if(!empty($row->email_verified_at))
                                            &nbsp; <i class="fas fa-check text-success" style="font-size:16px;"></i>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $row->role_id == 1 ? 'Reader' : ($row->role_id == 2 ? 'Author' :
                                    ($row->role_id == 3 ? 'Institution' : 'N/A'))}}</td>
                                    <td>{{ $row->country ?? 'N/A' }}</td>
                                    <td class="{{ $row->status == 1 ? 'text-success' : 'text-danger' }} text-center">
                                        &#9679; {{ $row->status == 1 ? 'Active' : 'Inactive'}}
                                    </td>
                                    @php
                                        $role = ($row->role_id == 2) ? 'Author' : (($row->role_id == 3) ? 'Institution' : 'User');
                                    @endphp
                                    <td class="text-center">
                                        <a href="{{ route('admin.user.view',['id' => $row->id, 'role' => $role]) }}"
                                           class="btn btn-sm" style="background: #9C9C9C" title="View">
                                            <i class="far fa-eye" style="color: #ffffff;"></i>
                                        </a>
                                        <a href="{{ route('admin.user.edit', ['id' => $row->id, 'role' => $role]) }}"
                                           class="btn btn-sm" style="background: #4D1DD4" title="Edit">
                                            <i class="fas fa-pen" style="color: #ffffff;"></i>
                                        </a>
                                        <a href="{{ route('admin.user.destroy', $row->id ) }}" title="Delete"
                                           onclick="return confirm('{{ __('Are you sure want to delete this item') }}')"
                                           class="btn btn-sm" style="background: #EC2626">
                                            <i class="fas fa-trash-alt" style="color: #ffffff;"></i>
                                        </a>
                                        @if($row->role_id == 1)
                                            <a href="{{ route('admin.book.index', ['user' => $row->id, 'books' => 'borrowed']) }}"
                                               class="btn btn-sm btn-info">
                                                Borrowed Books <span
                                                    class="badge badge-light">{{ $row->borrowed_count }}</span>
                                            </a>
                                        @elseif($row->role_id == 2 or $row->role_id == 3)
                                            <a href="{{ route('admin.book.index', ['user' => $row->id, 'books' => 'published']) }}"
                                               class="btn btn-sm btn-success">
                                                Published Books <span
                                                    class="badge badge-light">{{ $row->products_count }}</span>
                                            </a>
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
    {{-- <script>
        $(document).ready(function() {
            var select = $('#country');
            select.change(function() {
                var selectedValue = select.val();
                var redirectRoute = '{{ route("admin.user.index") }}';
                var redirectUrl = redirectRoute + '?country=' + selectedValue;
                @if(isset($data['type']) && !empty($data['type']))
                    redirectUrl += '&type={{ $data['type'] }}';
                @endif
                window.location.href = redirectUrl;
            });
        });
    </script> --}}
    <script>
        $(document).ready(function () {
            var select = $('#country');
            select.change(function () {
                var selectedValue = select.val();
                var redirectRoute = '{{ route("admin.user.index") }}';
                var redirectUrl = redirectRoute + '?country=' + selectedValue;
                window.location.href = redirectUrl;
            });
        });
    </script>
@endpush
