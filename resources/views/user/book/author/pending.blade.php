@extends('admin.layouts.user')
@section('pending_books', 'active')
@section('books', 'active menu-open')
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
                    <div class="d-flex align-items-center">
                        <div>
                            <h3 class="card-title" >Pending Books</h3>
                        </div>
                    </div>
                </div>
                <div class="px-2 table-responsive">
                    <table class="table  dataTables">
                        <thead>
                        <th>SL</th>
                        <th>Book</th>
                        <th>Publisher</th>
                        <th>Publisher Year</th>
                        <th>ISBN</th>
                        <th>Status</th>
                        <th>Actions</th>
                        </thead>
                        <tbody>
                        @foreach ($books as $key => $row)
                            <tr>
                                <td>{{$key+1}}</td>

                                <td>{{$row->title ?? 'N/A'}}</td>
                                <td>{{$row->publisher ?? 'N/A'}}</td>
                                <td>{{$row->publisher_year ?? 'N/A'}}</td>
                                <td>ISBN-10: {{$row->isbn10 ?? 'N/A'}} <br>
                                    ISBN-13: {{$row->isbn13 ?? 'N/A'}}
                                </td>
                                <td class="{{ $row->status == 10 ? 'text-success' : ($row->status == 0 ? 'text-warning' : 'text-danger' ) }}">
                                    &#9679; {{
                                        $row->status == 10 ? 'Published' :
                                        ($row->status == 0 ? 'Pending' :
                                        ($row->status == 20 ? 'Unpublished' :
                                        ($row->status == 30 ? 'Rejected' : 'Expired')))
                                    }}
                                </td>
                                <td>
                                    <a href="{{ route('author.books.edit',$row->id) }}" class="btn btn-sm" style="background: #4D1DD4">
                                        <i class="fas fa-pen" style="color: #ffffff;"></i>
                                    </a>
                                    <a href="{{ route('author.books.delete', $row->id ) }}"
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
@push("script")
@endpush
