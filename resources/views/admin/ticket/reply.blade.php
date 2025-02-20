@extends('admin.layouts.master')
@section('ticket', 'active')
@section('title') {{ $data['title'] ?? '' }} @endsection
@push('style')
    <style>
        input,
        select,
        textarea {
            border-radius: 10px !important;
        }

        .direct-chat-text {
            margin: 0;
            display: inline;
        }



        .direct-chat-msg {
            padding: 16px 0px;
        }

        .direct-chat-secondary .right>.direct-chat-text {
            margin: 0;
        }

        .direct-chat-secondary .right>.direct-chat-text {
            float: right;
        }
        .direct-chat-secondary .left>.direct-chat-text {
            float: left;
        }
        .direct-chat-text{
            max-width: 80%;
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
                                <h3 class="card-title">Ticket View -</h3>
                                <h3 class="card-title">&nbsp;{{$data['row']->subject}}</h3>      
                            </div>
                            <div>
                                <a href="{{ route('admin.ticket.index') }}" class="btn btn-sm btn-light"
                                    style="border: 1px solid #F1F1F1">Back</a>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="">
                    <div class="card-outline rounded direct-chat direct-chat-secondary shadow-none">
                        <div class="card" style="max-width:65rem; margin:auto;">
                            <div class="p-4 direct-chat-messages" id="chatbox" style="height:340px;">
                                @foreach ($data['row']->details as $detail)
                                    @if ($detail->from_admin == 0)
                                        {{-- author --}}
                                        <div class="direct-chat-msg left">
                                            <div class="direct-chat-infos clearfix">
                                                <span
                                                    class="direct-chat-name mb-2 float-left">{{ $data['row']->user->name ?? '' }}</span>
                                            </div>
                                            <img class="direct-chat-img" src="{{ getProfile($data['row']->user->image) }}"
                                                alt="{{ $data['row']->user->name ?? '' }}">
                                            <div class="direct-chat-text">
                                                {{ $detail->message ?? '' }}
                                                @if ($detail->file_name_uploaded)
                                                    <div class="attachement mt-3">
                                                        <a href="{{ asset($detail->file_name_uploaded) }}"
                                                           target="_blank" class="badge badge-light float-left">View Attachement</a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        {{-- admin --}}
                                        <div class="direct-chat-msg right">
                                            <div class="direct-chat-infos clearfix">
                                                <span
                                                    class="direct-chat-name mb-2 float-right">{{ $detail->admin->name ?? '' }}</span>
                                            </div>
                                            <img class="direct-chat-img" src="{{ asset('assets/images/default-user.png') }}"
                                                alt="{{ $detail->admin->image ?? '' }}">
                                            <div class="direct-chat-text">
                                                {{ $detail->message ?? '' }}
                                                @if ($detail->file_name_uploaded)
                                                    <div class="attachement mt-3">
                                                        <a href="{{ asset($detail->file_name_uploaded) }}"
                                                            target="_blank" class="badge badge-light float-right">View
                                                            Attachement</a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="card-footer mt-4">
                                @if ($data['row']->status == 0)
                                    <div class="alert alert-danger my-5 p-3 text-center">
                                        This ticket is closed.
                                    </div>
                                @else
                                    <form action="{{ route('admin.ticket.store') }}" method="post"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="ticketid" id="ticketid"
                                            value="{{ $data['row']->pk_no }}">
                                        <div class="">
                                            <div class="container-fluid">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="name" class="form-label">Reply<span
                                                                class="text-danger">*</span></label>
                                                        <textarea class="form-control" name="message" id="message" cols="30" rows="10"
                                                            placeholder="reply ticket message"></textarea>
                                                    </div>
                                                </div>
                                                @error('answer')
                                                    <div class="text-danger">{{ $subject }}</div>
                                                @enderror
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="name" class="form-label">Attachment</label>
                                                        <input type="file" class="form-control" name="attachment"
                                                            id="attachment">
                                                    </div>
                                                </div>
                                                @error('answer')
                                                    <div class="text-danger">{{ $subject }}</div>
                                                @enderror
                                                <div class="col-md-12 text-center">
                                                    <button type="submit" class="btn text-light px-5"
                                                        id="custom_btn">Reply</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>



                {{-- <div class="">

                    <div class="card-body mt-4">
                        <div class="row">
                            <div class="col-md-10 container-fluid">
                                @foreach ($data['row']->details as $index => $detail)
                                    @if ($detail->from_admin == 0)
                                        <div class="author_message w-50 mt-2">
                                            <div class="avatar">
                                                <img width="35" height="35" style="border-radius: 50%"
                                                    src="{{ getProfile($data['row']->user->image) }}"
                                                    alt="{{ $data['row']->user_id ?? '' }}">
                                            </div>
                                            <div class="author">
                                                <p>{{ $data['row']->user->name ?? '' }}</p>
                                            </div>
                                            <div class="msg">
                                                @if ($index == 0) 
                                                <p><strong>Subject: </strong>{{ $data['row']->subject ?? '' }}</p>
                                                @endif
                                                <p>{{ $detail->message ?? '' }}</p>
                                            </div>
                                            @if ($detail->file_name_uploaded)
                                                <div class="attachement mt-2">
                                                    <a href="{{ asset($detail->file_name_uploaded) }}" target="_blank">View
                                                        Attachement</a>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="admin_message w-50 float-right mt-2">
                                            <div class="author_group_element">
                                                <div class="avatar">
                                                    <img width="35" height="35" style="border-radius: 50%"
                                                        src="{{ getProfile($detail->admin->image) }}"
                                                        alt="{{ $detail->admin->name ?? '' }}">
                                                </div>
                                                <div class="author">
                                                    <p>{{ $detail->admin->name ?? '' }}</p>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="msg">
                                                <p>{{ $detail->message ?? '' }}</p>
                                            </div>
                                            @if ($detail->file_name_uploaded)
                                                <div class="attachement mt-2">
                                                    <a href="{{ asset($detail->file_name_uploaded) }}"
                                                        target="_blank">View
                                                        Attachement</a>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <hr>
                        </div>
                        @if ($data['row']->status == 0)
                            <div class="alert alert-danger my-5 p-3 text-center">
                                This ticket is closed.
                            </div>
                        @else
                            <form action="{{ route('admin.ticket.store') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="ticketid" id="ticketid" value="{{ $data['row']->pk_no }}">
                                <div class="row mt-5">
                                    <div class="col-md-6 container-fluid">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name" class="form-label">Reply<span
                                                        class="text-danger">*</span></label>
                                                <textarea class="form-control" name="message" id="message" cols="30" rows="10"
                                                    placeholder="reply ticket message"></textarea>
                                            </div>
                                        </div>
                                        @error('answer')
                                            <div class="text-danger">{{ $subject }}</div>
                                        @enderror
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name" class="form-label">Attachment</label>
                                                <input type="file" class="form-control" name="attachment"
                                                    id="attachment">
                                            </div>
                                        </div>
                                        @error('answer')
                                            <div class="text-danger">{{ $subject }}</div>
                                        @enderror
                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn text-light px-5"
                                                id="custom_btn">Reply</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div> --}}



            </div>
        </div>
    </div>
@endsection



@push('script')
    <script>
        $(document).ready(function() {
            const myDiv = $("#chatbox");

            function scrollToBottom() {
                myDiv.scrollTop(myDiv[0].scrollHeight);
            }
            scrollToBottom();
        });
    </script>
@endpush
