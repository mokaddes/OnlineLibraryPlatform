@extends('admin.layouts.master')
@section('push', 'active')
@section('title')
    {{ $title ?? '' }}
@endsection

@push('style')

    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <style>
        input, select, textarea {
            border-radius: 0 !important;
        }

        @media only screen and (min-width: 768px) {
            #responsive_btn {
                margin-top: 30px;
            }
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__display {
            color: #111111 !important;
        }
        .select2-selection.select2-selection--multiple{
            min-height: 42px !important;
            border-radius: 0 !important;
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
                                <h3 class="card-title">{{ isset($push) ? 'Edit push notification' : 'Add new push notification'}}</h3>
                            </div>
                            <div>
                                <a href="{{ route('admin.push-notification.index') }}" class="btn btn-sm btn-light"
                                   style="border: 1px solid #F1F1F1">Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mt-4">
                        <div class="col-md-8 container-fluid">
                            <form method="POST"
                                  action="{{ isset($push) ? route('admin.push-notification.update', ['id' => $push->id]) : route('admin.push-notification.store') }}">
                                @csrf

                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" id="title" name="title"
                                           value="{{ old('title', $push->title ?? '') }}" required placeholder="Notification title">
                                </div>

                                <div class="form-group">
                                    <label for="body">Body</label>
                                    <input type="text" class="form-control" id="body" name="body"
                                           value="{{ old('body', $push->body ?? '') }}" required placeholder="Notification Body">
                                </div>

                                <div class="form-group">
                                    <label for="url">Action Url</label>
                                    <input type="text" class="form-control" id="url" name="url"
                                           value="{{ old('url', $push->url ?? '') }}" required placeholder="Action Url">
                                </div>


                                <div class="form-group">
                                    <label for="user_ids">Users</label>
                                    <select class="form-control select2" id="user_ids" name="user_ids[]" multiple required>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ in_array($user->id , old('user_ids', $push->user_ids ?? [])) ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $types[$user->role_id] }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option
                                            value="1" {{ old('status', $push->status ?? '') === '1' ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option
                                            value="0" {{ old('status', $push->status ?? '') === '0' ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                    </select>
                                </div>

                                <button type="submit"
                                        class="btn btn-primary">{{ isset($push) ? 'Update' : 'Create' }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        $(document).ready(function () {
            $('#generateCode').on('click', function () {
                var generatedCode = generateRandomCode(8);
                $('#code').val(generatedCode);
            });

            function generateRandomCode(length) {
                var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                var code = '';

                for (var i = 0; i < length; i++) {
                    code += characters.charAt(Math.floor(Math.random() * characters.length));
                }

                return code;
            }
        });
        $( function() {
            $('#valid_date').datepicker({
                dateFormat: 'dd-mm-yy',
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                onSelect: function(selectedDate) {
                    // we can write code here
                }
            });
        } );
    </script>
@endpush
