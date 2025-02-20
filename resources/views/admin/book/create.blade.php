@extends('admin.layouts.master')
@section('books', 'active')
@section('library_menu', 'active menu-open')
@section('title')
    {{ $data['title'] ?? '' }}
@endsection
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/yearpicker.css') }}"/>
    <style>
        .select2-container .select2-search--inline .select2-search__field {
            margin-top: 10px !important;
            height: 25px;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__display {
            padding-left: 10px !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            margin-top: 8px !important;
        }
        .select2-container--default .select2-selection--multiple {
            min-height: 43px !important;
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
                                <h3 class="card-title">Add New</h3>
                            </div>
                            <div>
                                <a href="{{ route('admin.book.index') }}" class="btn btn-sm btn-light"
                                   style="border: 1px solid #F1F1F1">Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.book.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="file_type" class="form-label">Book Type <span
                                                class="text-danger">*</span></label>
                                        <select name="file_type" id="file_type" class="form-select form-control select2"
                                                required>
                                            <option value="" class="d-none">Select</option>
                                            <option value="pdf" {{ old('file_type') == 'pdf' ? 'selected' : '' }}>Pdf
                                            </option>
                                            <option value="audio" {{ old('file_type') == 'audio' ? 'selected' : '' }}>
                                                Audio
                                            </option>
                                            <option value="video" {{ old('file_type') == 'video' ? 'selected' : '' }}>
                                                Video
                                            </option>
                                            <option value="url" {{ old('file_type') == 'url' ? 'selected' : '' }}>Link
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 pdf book {{ old('file_type') == 'pdf' ? '' : 'd-none' }}">
                                    <div class="form-group">
                                        <label for="pdf_book" class="form-label">Book (In Pdf) <span
                                                class="text-danger">*</span></label>
                                        <input type="file" name="pdf_book" id="pdf_book" accept="application/pdf"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6 audio book {{ old('file_type') == 'audio' ? '' : 'd-none' }}">
                                    <div class="form-group">
                                        <label for="audio_file" class="form-label">Audio File <span class="text-danger">*</span></label>
                                        <input type="file" name="audio_book" id="audio_book" accept="audio/*"
                                               class="form-control">
                                    </div>

                                </div>


                                <div class="col-md-6 video book {{ old('file_type') == 'video' ? '' : 'd-none' }}">
                                    <div class="form-group">
                                        <label for="video_file" class="form-label">Video File <span
                                                class="text-danger">*</span></label>
                                        <input type="file" name="video_book" id="video_book" accept="video/*"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6 url book {{ old('file_type') == 'url' ? '' : 'd-none' }}">
                                    <div class="form-group">
                                        <label for="url_book" class="form-label">Book URL <span
                                                class="text-danger">*</span></label>
                                        <input type="url" name="url_book" id="url_book" value="{{ old('url_book') }}"
                                               placeholder="Enter youtube url" class="form-control">
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="thumb" class="form-label">Thumbnail [<span class="text-danger">
                                                Recommended size : 300 x 350 </span>]<span
                                                class="text-danger">*</span></label>
                                        <input type="file" name="thumb" id="thumb" accept="image/*" class="form-control"
                                               required>
                                    </div>
                                    @error('thumb')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="category_id" class="form-label">Category <span
                                                class="text-danger">*</span></label>
                                        <select name="category_id[]" id="category_id"
                                                class="form-select form-control select_multiple" multiple>
                                            <option value="" class="d-none">Select</option>
                                            @foreach ($data['categories'] as $row)
                                                <option
                                                    value="{{ $row->id }}" {{ in_array($row->id, old('category_id', [])) ? 'selected' : '' }}>{{ $row->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title" class="form-label">Title <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="title" value="{{ old('title') }}" id="title"
                                               class="form-control" required
                                               placeholder="Enter your title">
                                    </div>
                                    {{-- @error('title')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror --}}
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sub_title" class="form-label">Sub Title</label>
                                        <input type="text" name="sub_title" value="{{ old('sub_title') }}"
                                               id="sub_title" class="form-control"
                                               placeholder="Enter your sub title">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="isbn10" class="form-label">ISBN Number <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="isbn10" value="{{ old('isbn10') }}" id="isbn10" class="form-control" required
                                               placeholder="Enter ISBN Number">
                                    </div>
                                    @error('isbn10')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="publisher" class="form-label">Publisher <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="publisher" value="{{ old('publisher') }}"
                                               id="publisher" class="form-control"
                                               required placeholder="Enter publisher">
                                    </div>
                                    @error('publisher')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="user_id" class="form-label">Select Author <span
                                                class="text-danger">*</span></label>
                                        <select name="user_id" id="user_id" class="form-select form-control select2">
                                            <option value="" class="d-none">Select</option>
                                            @foreach ($data['authors'] as $row)
                                                <option
                                                    value="{{ $row->id }}" {{ old('user_id') == $row->id ? 'selected' : '' }} data-user="{{ $row->name }} {{ $row->last_name }}">{{ $row->name }} {{ $row->last_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('author')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="author" class="form-label">Associate Author's Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="author" id="author" class="form-control"
                                               value="{{ old('author') }}"
                                               placeholder="Enter author name">
                                    </div>
                                    @error('author')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="edition" class="form-label">Edition</label>
                                        <input type="text" name="edition" value="{{ old('edition') }}" id="edition"
                                               class="form-control" placeholder="Enter edition">
                                    </div>
                                    @error('edition')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                {{-- <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="binding" class="form-label">Binding</label>
                                        <select name="binding" id="binding" class="form-select form-control">
                                            <option value="" class="d-none">Select</option>
                                            <option value="hardcover">Hardcover</option>
                                            <option value="paperback">Paperback</option>
                                            <option value="spiral">Spiral</option>
                                        </select>
                                    </div>
                                </div> --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="publisher_year" class="form-label">Publisher Year <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="publisher_year" value="{{ old('publisher_year') }}"
                                               id="yearpicker" value=""
                                               class="form-control" required placeholder="Enter publisher year"
                                               readonly>
                                    </div>
                                    {{-- @error('publisher_year')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror --}}
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pages" class="form-label">Pages</label>
                                        <input type="number" name="pages" value="{{ old('pages') }}" id="pages"
                                               class="form-control" placeholder="Enter number of pages">
                                    </div>

                                </div>

                                {{-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="physicalForm" class="form-label">Physical Form</label>
                                        <select name="physicalForm" id="physicalForm" class="form-select form-control">
                                            <option value="" class="d-none">Select</option>
                                            <option value="hardcover">Hardcover</option>
                                            <option value="paperback">Paperback</option>
                                            <option value="ebook">Ebook</option>
                                        </select>
                                    </div>
                                </div> --}}

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="size" class="form-label">Size</label>
                                        <select name="size" id="size" class="form-select form-control">
                                            <option value="" class="d-none">Select</option>
                                            <option value="small" {{ old('size') == 'small' ? 'selected' : '' }} >
                                                Small
                                            </option>
                                            <option value="medium" {{ old('size') == 'medium' ? 'selected' : '' }} >
                                                Medium
                                            </option>
                                            <option value="large" {{ old('size') == 'large' ? 'selected' : '' }} >
                                                Large
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="is_highlight" class="form-label">Highlight</label>
                                        <select name="is_highlight" id="is_highlight" class="form-select form-control">
                                            <option value="0">No</option>
                                            <option value="1" {{ old('is_highlight') == 1 ? 'selected' : '' }} >Yes
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="is_paid" class="form-label">Purchase Status</label>
                                        <select name="is_paid" id="is_paid" class="form-select form-control">
                                            <option value="0">Free</option>
                                            <option value="1" {{ old('is_paid') == 1 ? 'selected' : '' }} >Premium
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6 pdf book {{ old('file_type') == 'pdf' ? '' : 'd-none' }}">
                                    <div class="form-group">
                                        <label for="reading_time" class="form-label">Reading Time (In Hours)  <span class="text-danger">*</span> </label>
                                        <input type="number" step="any" name="reading_time" value="{{ old('reading_time') }}"
                                               id="reading_time" class="form-control"
                                               placeholder="Enter reading time in hours">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="book_for" class="form-label">Book Distribution</label>
                                        <select name="book_for" id="book_for" class="form-select form-control">
                                            <option value="library" {{ old('book_for') == 'library' ? 'selected' : '' }}>Book For Library</option>
                                            <option value="sale" {{ old('book_for') == 'sale' ? 'selected' : '' }}>Book For Sale</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 {{ old('book_for') == 'sale' ? '' : 'd-none' }}" id="book_price_div">
                                    <div class="form-group">
                                        <label for="book_price" class="form-label">Price</label>
                                        <input type="number" name="book_price" id="book_price" step="0.01" class="form-control" value="{{ old('book_price') }}" placeholder="Price of the book">
                                        <small style="color: #930193;">Platform commission 
                                            <span id="commission_percentage">{{ getSetting()->commission }}%</span> 
                                            <span class="d-none" id="commission_cal"><span id="commission_price"></span> and you will get <span id="calculation"></span></span>
                                        </small>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                        <textarea name="description" id="description" class="form-control" required
                                                  placeholder="Enter description"
                                                  rows="4">{{ old('description') }}</textarea>
                                    </div>
                                    @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="text-center col-12">
                                    <button type="submit" class="btn text-light px-5" id="custom_btn">Add Book</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('assets/js/yearpicker.js') }}"></script>
    <script>
        $("#yearpicker").yearpicker({
            endYear: new Date().getFullYear()
        });
        var publisherYear = "{{ old('publisher_year') }}";
        $("#yearpicker").val(publisherYear);

        $('#file_type').change(function () {
            $('.book').addClass('d-none');
            $('#video_type').val('');
            $('#audio_type').val('');
            const selectedType = $(this).val();
            $(`.${selectedType}.book`).removeClass('d-none');
        })
        $('#user_id').change(function () {
            const selectedUser = $(this).find(':selected').data('user');
            $('#author').val(selectedUser);
        });
        $(document).ready(function () {
            $('.select2').select2();
            $('.select_multiple').select2({
                multiple: true,
                placeholder: 'Select'
            });
        });
    </script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var bookForSelect = document.getElementById('book_for');
                var bookPriceDiv = document.getElementById('book_price_div');
                bookForSelect.addEventListener('change', function () {
                    if (this.value === 'sale') {
                        bookPriceDiv.classList.remove('d-none');
                    } else {
                        bookPriceDiv.classList.add('d-none');
                    }
                });
                bookForSelect.dispatchEvent(new Event('change'));
            });
            document.addEventListener('DOMContentLoaded', function () {
                var bookPriceInput = document.getElementById('book_price');
                var commissionPercentageSpan = document.getElementById('commission_percentage');
                var commissionCalSpan = document.getElementById('commission_cal');
                var commissionPriceSpan = document.getElementById('commission_price');
                var calculationSpan = document.getElementById('calculation');
        
                bookPriceInput.addEventListener('input', function () {
                    var bookPrice = parseFloat(this.value);
                    var commissionPercentage = parseFloat(commissionPercentageSpan.innerText);
                    var commission = (bookPrice * commissionPercentage) / 100;
                    var calculation = bookPrice - commission;
        
                    commissionPriceSpan.innerText = commission.toFixed(2) + '$';
                    calculationSpan.innerText = calculation.toFixed(2) + '$';
        
                    if (isNaN(bookPrice)) {
                        commissionCalSpan.classList.add('d-none');
                        commissionPercentageSpan.classList.remove('d-none');
                    } else {
                        commissionCalSpan.classList.remove('d-none');
                        commissionPercentageSpan.classList.add('d-none');
                    }
                });
            });
        </script>
@endpush
