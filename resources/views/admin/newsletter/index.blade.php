@extends('admin.layouts.master')
@section('admin-institute', 'active')
@section('title')
    {{ $data['title'] ?? '' }}
@endsection
@push('style')

@endpush
@section('content')
    <div class="content-wrapper mt-3">
        <div class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="card-title"> All Subscriber</h3>
                            </div>
                        </div>
                    </div>
                    <div class="px-2 table-responsive">
                        <table class="table table-hover dataTables">
                            <thead>
                            <th class="text-center">Serial No.</th>
                            <th class="text-center">ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th class="text-center">User Type</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                            </thead>
                            <tbody>
                            @foreach ($members as $key => $row)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td class="text-center">{{ $row['id'] }}</td>
                                    <td class="text-center">{{ $row['id'] }}</td>
                                    <td class="text-center">{{ $row['id'] }}</td>
                                    <td class="text-center">{{ $row['id'] }}</td>
                                    <td class="text-center">{{ $row['id'] }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.institute.view',['id' => $row->id,]) }}"
                                           class="btn btn-sm" style="background: #9C9C9C" title="View">
                                            <i class="far fa-eye" style="color: #ffffff;"></i>
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

@endpush
