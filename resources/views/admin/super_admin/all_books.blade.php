@extends('admin.layouts.master')
@section('books', 'active')
@section('library_menu', 'active menu-open')
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
                                <h3 class="card-title" >All Books</h3>
                            </div>
                            <div>
                                <a href="{{ route('admin.book.create') }}" class="btn btn-sm btn-light"
                                style="border: 1px solid #F1F1F1">Add New</a>
                            </div>
                        </div>
                    </div>
                    <div class="px-2 table-responsive">
                        <table class="table table-hover dataTables">
                            <thead>
                                <th>SL</th>
                                <th>ISBN</th>
                                <th>Book</th>
                                <th>Author</th>
                                <th>Publisher</th>
                                <th>Publish Year</th>
                                <th>Status</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                @foreach ($data['rows'] as $key => $row)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>ISBN-10: {{$row->isbn10 ?? 'N/A'}} <br>
                                            ISBN-13: {{$row->isbn13 ?? 'N/A'}}
                                        </td>
                                        <td>{{$row->title ?? 'N/A'}}</td>
                                        <td>                      
                                            @php $foundMatch = false @endphp
                                            @foreach ($data['authors'] as $author)
                                                @if ($row->user_id == $author->id)
                                                    {{ $author->name }} 
                                                    @php $foundMatch = true @endphp
                                                @endif
                                            @endforeach
                                            @if (!$foundMatch)
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{$row->publisher ?? 'N/A'}}</td>
                                        <td>{{$row->publisher_year ?? 'N/A'}}</td>
                                        <td class="{{ $row->status == 10 ? 'text-success' : ($row->status == 0 ? 'text-warning' : 'text-danger' ) }}">
                                            &#9679; {{ 
                                                $row->status == 10 ? 'Published' : 
                                                ($row->status == 0 ? 'Pending' : 
                                                ($row->status == 20 ? 'Unpublished' : 
                                                ($row->status == 30 ? 'Rejected' : 'Expired')))
                                            }}
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.book.edit',$row->id) }}" class="btn btn-sm" style="background: #4D1DD4">
                                                <i class="fas fa-pen" style="color: #ffffff;"></i>
                                            </a>
                                            <a href="{{ route('admin.book.delete', $row->id ) }}" 
                                                onclick="return confirm('{{ __('Are you sure want to delete this item') }}')"
                                                class="btn btn-sm" style="background: #EC2626">
                                                <i class="fas fa-trash-alt" style="color: #ffffff;"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                {{-- <tr>
                                    <td>02</td>
                                    <td>#1250294</td>
                                    <td>1984 by George Orwell</td>
                                    <td>Frank Murlo</td>
                                    <td>Amazon</td>
                                    <td>Dec 1, 2023</td>
                                    <td class="text-warning">&#9679; Pending</td>
                                    <td>                                        
                                        <a href="{{ route('admin.book.edit',2) }}" class="btn btn-sm" style="background: #4D1DD4">
                                            <i class="fas fa-pen" style="color: #ffffff;"></i>
                                        </a>
                                        <button class="btn btn-sm" style="background: #EC2626">
                                            <i class="fas fa-trash-alt" style="color: #ffffff;"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>03</td>
                                    <td>#1250294</td>
                                    <td>1984 by George Orwell</td>
                                    <td>Frank Murlo</td>
                                    <td>Amazon</td>
                                    <td>Dec 1, 2023</td>
                                    <td class="text-danger">&#9679; Inactive</td>
                                    <td>
                                        <a href="{{ route('admin.book.edit',3) }}" class="btn btn-sm" style="background: #4D1DD4">
                                            <i class="fas fa-pen" style="color: #ffffff;"></i>
                                        </a>
                                        <button class="btn btn-sm" style="background: #EC2626">
                                            <i class="fas fa-trash-alt" style="color: #ffffff;"></i>
                                        </button>
                                    </td>
                                </tr> --}}
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