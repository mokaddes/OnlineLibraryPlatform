@extends('admin.layouts.master')
@section('books', 'active')
@section('library_menu', 'active menu-open')
@section('title')
    {{ $data['title'] ?? '' }}
@endsection
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
    <div class="content-wrapper mt-3">
        <div class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="card-title">{{$data['heading']}}</h3>
                            </div>
                            @if($data['add_button'] == '1')
                                <div>
                                    <a href="{{ route('admin.book.create') }}" class="btn btn-sm btn-light"
                                       style="border: 1px solid #F1F1F1">Add New</a>
                                </div>
                            @else
                                <div>
                                    <a href="@if($data['type'] == 'borrowed') {{ route('admin.user.index', ['type' => 'reader']) }}
                                        @else {{ route('admin.user.index', ['type' => 'author']) }} @endif"
                                       class="btn btn-sm btn-light" style="border: 1px solid #F1F1F1">Back</a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="px-2 table-responsive">
                        <table class="table table-hover dataTables">
                            <thead>
                            <th class="text-center">Serial No.</th>
                            <th>ISBN</th>
                            <th>Book</th>
                            <th>Author</th>
                            <th class="text-center">Publisher</th>
                            <th class="text-center">Publish Year</th>
                            @if($data['type'] == 'borrowed')
                                <th class="text-center">Remaining Days</th>
                            @else
                                <th class="text-center">Book of month</th>
                            @endif
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                            </thead>
                            <tbody>
                            @foreach ($data['rows'] as $key => $row)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td>ISBN-10: {{$row->isbn10 ?? 'N/A'}} <br>
                                        ISBN-13: {{$row->isbn13 ?? 'N/A'}}
                                    </td>
                                    <td>{{$row->title ?? 'N/A'}} ({{ $row->productViews()->count() }})</td>
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
                                    <td class="text-center">
                                        @if($data['type'] == 'borrowed')
                                            {{ $row->remaining_days }}
                                        @else
                                            <div class="custom-control custom-switch">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input statusSwitch"
                                                           data-route="{{ route('admin.book.month_book',['id'=>$row->id, 'month_book' => $row->is_book_of_month ? 0 : 1]) }}"
                                                           id="statusSwitch{{$row->id}}" {{ intval($row->is_book_of_month) == 1 ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="statusSwitch{{$row->id}}"
                                                           id="statusLabel">{{ intval($row->is_book_of_month) == 1 ? 'Yes' : 'No' }}</label>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    @if($data['type'] == 'borrowed')
                                        <td class="{{ $row->borrowed_valid == 1 && $row->remaining_days >= 0 ? 'text-success' : 'text-danger' }} text-center">
                                            {{ $row->borrowed_valid == 1 && $row->remaining_days >= 0 ? 'Valid' : 'Invalid' }}
                                        </td>
                                         @else
                                    <td class="{{ $row->status == 10 ? 'text-success' : ($row->status == 0 ? 'text-warning' : 'text-danger' ) }} text-center">
                                        &#9679; {{
                                                $row->status == 10 ? 'Published' :
                                                ($row->status == 0 ? 'Pending' :
                                                ($row->status == 20 ? 'Unpublished' :
                                                ($row->status == 30 ? 'Rejected' : 'Expired')))
                                            }}
                                    </td>
                                    @endif
                                    <td class="text-center">
                                        @if($data['type'] == 'borrowed')
                                            <a href="{{ route('admin.book.analytic',$row->id) }}" class="btn btn-sm"
                                               style="background: #19becc" title="Analytic">
                                                <i class="fas fa-globe" style="color: #ffffff;"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="btn btn-edit btn-sm"
                                               data-book_title="{{$row->title}}"
                                               data-borrowed_valid="{{$row->borrowed_valid}}"
                                               data-borrowed_id="{{$row->borrowed_id}}"
                                               data-remaining_days="{{$row->remaining_days}}"
                                               style="background: #4D1DD4" title="Edit">
                                                <i class="fas fa-pen" style="color: #ffffff;"></i>
                                            </a>

                                        @else
                                            <a href="{{ route('admin.book.analytic',$row->id) }}" class="btn btn-sm"
                                               style="background: #19becc" title="Analytic">
                                                <i class="fas fa-globe" style="color: #ffffff;"></i>
                                            </a>
                                            <a href="{{ route('admin.book.edit',$row->id) }}" class="btn btn-sm"
                                               style="background: #4D1DD4" title="Edit">
                                                <i class="fas fa-pen" style="color: #ffffff;"></i>
                                            </a>
                                            <a href="{{ route('admin.book.delete', $row->id ) }}" title="Delete"
                                               onclick="return confirm('{{ __('Are you sure want to delete this item') }}')"
                                               class="btn btn-sm" style="background: #EC2626">
                                                <i class="fas fa-trash-alt" style="color: #ffffff;"></i>
                                            </a>
                                            <a href="{{ route('admin.book.marc', $row->id ) }}" title="MARC Data"
                                               class="btn btn-sm" style="background: #082797">
                                                <i class="fas fa-eye" style="color: #ffffff;"></i>
                                            </a>
                                        @endif

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

    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- Update the modal title dynamically -->
                    <h5 class="modal-title" id="editModalLabel">Edit Borrowed Date - <span id="bookTitle"></span></h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">X</button>
                </div>
                <form id="editForm" method="post" action="{{ route('admin.book.borrowedChange') }}">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="borrowed_id" id="borrowed_id">
                        <div>
                            <label for="validity" class="form-label">Borrowed Validity</label>
                            <select name="validity" id="validity" class="form-control">
                                <option value="1">Valid</option>
                                <option value="0">Invalid</option>
                            </select>
                        </div>
                        <div>
                            <label for="remainingDays" class="form-label">Remaining Days:</label>
                            <input type="text" id="remainingDays" class="form-control" name="remaining_day">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@push("script")
    <script>
        $(document).on('change', '.statusSwitch', function () {
            var status = $(this).prop('checked') ? 1 : 0;
            var route = $(this).data('route');
            console.log(route);
            var confirmTxt = 'There is currently an active book of the month. Are you sure you to change this to another book?'
            if (confirm(confirmTxt)) {
                window.location.href = route;
            }
            $(this).prop('checked', false);
            return false;

        });

        $(document).ready(function () {
            // Function to handle the click event on the edit button
            $('.btn-edit').click(function () {
                var bookTitle = $(this).data('book_title');
                var borrowed_id = $(this).data('borrowed_id');
                var borrowed_valid = $(this).data('borrowed_valid');
                var remainingDays = $(this).data('remaining_days');
                let validity = 0;
                if (parseInt(borrowed_valid)=== 1 && parseInt(remainingDays) >= 0) {
                    validity = 1;
                }

                $('#bookTitle').text(bookTitle);
                $('#borrowed_id').val(borrowed_id);
                $('#validity').val(validity);
                $('#remainingDays').val(remainingDays);

                $('#editModal').modal('show');
            });


        });

    </script>
@endpush
