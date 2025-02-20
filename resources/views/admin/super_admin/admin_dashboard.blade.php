@extends('admin.layouts.master')
@section('dashboard', 'active')

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
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box">
                            <div class="info-box-content">
                                <span class="info-box-text">{{ str_pad($data['user_count'], 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="info-box-number">Users</span>
                            </div>
                            <div class="info-box-content" style="text-align:center;">
                                <i class="far fa-user" style="color: #800080;"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box">
                            <div class="info-box-content">
                                <span class="info-box-text">{{ str_pad($data['rows']->count(), 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="info-box-number">Books</span>
                            </div>
                            <div class="info-box-content" style="text-align:center;">
                                <i class="fas fa-book" style="color: #800080;"></i>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix hidden-md-up"></div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box">
                            <div class="info-box-content">
                                <span class="info-box-text">{{ str_pad($data['package_count'], 2, '0', STR_PAD_LEFT) }}
                                </span>
                                <span class="info-box-number">Packages </span>
                            </div>
                            <div class="info-box-content" style="text-align:center;">
                                <i class="fas fa-gift" style="color: #800080;"></i>

                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box">
                            <div class="info-box-content">
                                <span class="info-box-text">{{ str_pad($data['transaction_count'], 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="info-box-number">Transactions</span>
                            </div>
                            <div class="info-box-content" style="text-align:center;">
                                <i class="fas fa-exchange-alt" style="color: #800080;"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card" >
                    <div class="card-header" >
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="card-title" >Latest Books</h3>
                            </div>
                            <div>
                                <a href="{{ route('admin.book.index') }}" class="btn btn-sm btn-light"
                                style="border: 1px solid #F1F1F1">View All →</a>
                            </div>
                        </div>
                    </div>
                    <div class="px-2 table-responsive">
                        <table class="table table-hover dataTables">
                            <thead>
                                <th class="text-center">Serial No.</th>
                                <th>ISBN</th>
                                <th>Book</th>
                                <th>Author</th>
                                <th>Publisher</th>
                                <th class="text-center">Publisher Date</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </thead>
                            <tbody>
                                @foreach ($data['rows'] as $key => $row)
                                    <tr>
                                        <td class="text-center" width=10%>{{$key+1}}</td>
                                        <td>ISBN-10: {{$row->isbn10 ?? 'N/A'}} <br>
                                            ISBN-13: {{$row->isbn13 ?? 'N/A'}}
                                        </td>
                                        <td>{{$row->title ?? 'N/A'}}  ({{ $row->productViews()->count() }})</td>
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
                                        <td class="text-center">{{$row->publisher_year ?? 'N/A'}}</td>
                                        <td class="{{ $row->status == 10 ? 'text-success' : ($row->status == 0 ? 'text-warning' : 'text-danger' ) }} text-center">
                                            &#9679; {{
                                                $row->status == 10 ? 'Published' :
                                                ($row->status == 0 ? 'Pending' :
                                                ($row->status == 20 ? 'Unpublished' :
                                                ($row->status == 30 ? 'Rejected' : 'Expired')))
                                            }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.book.edit',$row->id) }}" class="btn btn-sm"
                                                style="background: #4D1DD4" title="Edit">
                                                <i class="fas fa-pen" style="color: #ffffff;"></i>
                                            </a>
                                            <a href="{{ route('admin.book.delete', $row->id ) }}" title="Delete"
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
