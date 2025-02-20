@extends('admin.layouts.master')
@section('admin-institute', 'active')
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

        th {
            font-weight: normal !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__display{
            color: #111111;
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
                                <h3 class="card-title"> All Institute</h3>
                            </div>
                            <div>
                                <a href="{{ route('admin.institute.create') }}" class="btn btn-sm btn-light"
                                   style="border: 1px solid #F1F1F1">Add Institution</a>
                            </div>
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
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                            </thead>
                            <tbody>
                            @foreach ($data['rows'] as $key => $row)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td class="text-center">
                                        @if($row->logo)
                                            <img src="{{ asset($row->image) }}" alt="{{ $row->name }}"
                                                 width="50" height="50" style="border-radius: 20%;">
                                        @else
                                            <img src="{{ asset('assets/images/default-user.png') }}"
                                                 alt="{{ $row->name }}"
                                                 width="35" height="35" style="border-radius: 50%;">
                                        @endif
                                    </td>
                                    <td>{{$row->name ?? 'N/A'}}</td>
                                    <td>{{$row->email ?? 'N/A'}}
                                        @if(!empty($row->email_verified_at))
                                            &nbsp; <i class="fas fa-check text-success" style="font-size:16px;"></i>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $row->role_id == 1 ? 'Reader' : ($row->role_id == 2 ? 'Author' :
                                    ($row->role_id == 3 ? 'Institution' : 'N/A'))}}</td>
                                    <td class="{{ $row->status == 1 ? 'text-success' : 'text-danger' }} text-center">
                                        &#9679; {{ $row->status == 1 ? 'Active' : 'Inactive'}}
                                    </td>
                                    @php
                                        $role = ($row->role_id == 2) ? 'Author' : (($row->role_id == 3) ? 'Institution' : 'User');
                                    @endphp
                                    <td class="text-center">
                                        <a href="{{ route('admin.institute.view',['id' => $row->id,]) }}"
                                           class="btn btn-sm" style="background: #9C9C9C" title="View">
                                            <i class="far fa-eye" style="color: #ffffff;"></i>
                                        </a>
                                        <a href="{{ route('admin.institute.edit', ['id' => $row->id]) }}"
                                           class="btn btn-sm" style="background: #4D1DD4" title="Edit">
                                            <i class="fas fa-pen" style="color: #ffffff;"></i>
                                        </a>
                                        <a href="{{ route('admin.institute.destroy', $row->id ) }}" title="Delete"
                                           onclick="return confirm('{{ __('Are you sure want to delete this item') }}')"
                                           class="btn btn-sm" style="background: #EC2626">
                                            <i class="fas fa-trash-alt" style="color: #ffffff;"></i>
                                        </a>
                                        <a href="javascript:void(0)" title="Assign Books"
                                           class="btn btn-sm btn-success" data-toggle="modal" data-target="#myModal{{ $key }}">
                                            Assigned Books
                                            <span class="badge badge-light">{{ $row->products_count }}</span>
                                        </a>
                                    </td>
                                </tr>

                                <div class="modal fade" id="myModal{{ $key }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="myModalLabel">{{ $row->name }} {{ $row->last_name }}</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('admin.institute.assignBook', $row->id) }}" method="post">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="select2">Assign Books</label>
                                                        <select class="form-control select2" name="books[]"
                                                                multiple="multiple" required>
                                                            @foreach($books as $book)
                                                                <option value="{{ $book->id }}" {{ in_array($book->id , $row->borrowed()->where('is_valid', 1)->pluck('product_id')->toArray()) ? 'selected' : '' }} >{{ $book->title }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close
                                                    </button>
                                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
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
    <script>
        $(document).ready(function () {
            $('#select2').select2({
                placeholder: "Select books",
                allowClear: true,
            });
        });
    </script>
@endpush
