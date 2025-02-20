@extends('admin.layouts.master')
@section('promo-book', 'active')
@section('promo-code', 'active menu-open')
@section('title') {{ $title ?? '' }} @endsection
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
.modalBookImg{
    width: 50px;
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
                                <h3 class="card-title" >All Book PromoCode</h3>
                            </div>
                            <div>
                                <a href="{{ route('admin.book-promo.create') }}" class="btn btn-sm btn-light"
                                style="border: 1px solid #F1F1F1">Add New</a>
                            </div>
                        </div>
                    </div>
                    <div class="px-2 table-responsive">
                        <table class="table table-hover dataTables">
                            <thead>
                                <th class="text-center">SL.</th>
                                <th class="text-center">Title</th>
                                <th class="text-center">Code</th>
                                <th class="text-center">Validity</th>
                                <th class="text-center">Valid Date</th>
                                <th class="text-center">User Limit</th>
                                <th class="text-center">Used Count</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </thead>
                            <tbody>
                            @foreach($promoCode as $promo)
                                <tr class="text-center">
                                    <td> {{ $loop->iteration }} </td>
                                    <td> {{ $promo->title }} </td>
                                    <td> {{ $promo->code }} </td>
                                    <td> {{ $promo->validity }} Days</td>
                                    <td> {{ date('d M Y', strtotime($promo->valid_date)) }} </td>
                                    <td> {{ $promo->user_limit }}</td>
                                    <td> {{ $promo->used_count }}</td>
                                    <td>
                                        @if($promo->status == 1)
                                            <span class="text-success"> &#9679; Active</span>
                                        @else
                                            <span class="text-danger"> &#9679; Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#viewBooksModal" data-book-ids="{{ json_encode($promo->books()) }}"
                                           class="btn btn-sm btn-primary" title="View Books" >
                                            <i class="fas fa-eye" style="color: #ffffff;"></i>
                                        </a>

                                        <a href="{{ route('admin.book-promo.edit', $promo->id) }}"
                                            class="btn btn-sm btn-info" title="Edit">
                                            <i class="fas fa-edit" style="color: #ffffff;"></i>
                                        </a>
                                        <a href="{{ route('admin.book-promo.delete', $promo->id) }}" onclick="return confirm('Are your sure to delete package?')"
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

    <div class="modal fade" id="viewBooksModal" tabindex="-1" role="dialog" aria-labelledby="viewBooksModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewBooksModalLabel">Books Associated with Promo Code</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped dataTables" id="booksTable">
                        <thead>
                        <tr>
                            <th>SL</th>
                            <th>Thumbnail</th>
                            <th>Title</th>
                        </tr>
                        </thead>
                        <tbody id="tableData">

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push("script")
    <script>
        // Function to populate the list of books in the modal
        function populateBooksList(books) {
            var booksTable = $('#tableData');

            // Clear existing content
            booksTable.empty();

            // Use AJAX or other methods to fetch book details based on books
            // For simplicity, let's assume you have a route to fetch book details by ID
            $.each(books, function(index, book) {
                var thumbUrl = '{{ asset('') }}';
                var count = index + 1 ;
                booksTable.append(
                    '<tr>' +
                    '<td>' + count + '</td>' +
                    '<td><img src="' + thumbUrl + book.thumb + '" alt="' + book.title + '" class="img-thumbnail modalBookImg"></td>' +
                    '<td>' + book.title + '</td>' +
                    '</tr>'
                );
            });
        }

        // Event listener for the modal show event
        $('#viewBooksModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var books = button.data('book-ids');
            populateBooksList(books);
        });
    </script>
@endpush
