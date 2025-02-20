@extends('admin.layouts.user')
@section('club', 'active')
@section('title') {{ $title ?? '' }} @endsection
@push('style')
<style>
    p {
        margin-bottom: 0px;
    }
    .card-header
    {
        border: none !important;
    }
    .form-label
    {
        font-size: 18px;
    }  
    .attachment {
        padding: 4px !important;
        border-radius: 5px !important;
        color: #5f4f4f !important;
    } 
    .card-body
    {
        padding-top: 0px;
        padding-left: 0.8rem;
    }
    #col-div{
        display: flex;
        justify-content: center;
        align-items: center;
    }
    #card{
        background: #F8F8F8;
        border-radius: 12px;
        border: 1px solid #F0F0F0;
        box-shadow: none;
    }
    #suggestion-text{
        font-size: 12px;
        color: #777777;
    }

</style>
@endpush
@php
    $extension = pathinfo($row->image, PATHINFO_EXTENSION);
@endphp
@section('content')
<div class="content-wrapper mt-3 pb-4" >
    <div class="content">
        <div class="container-fluid card">
            <div class="row mb-4 mt-2 p-2 d-flex align-items-center justify-content-between">
                <div class="col-md-6"><h4>{{$row->title}}</h4><a href="{{ route('user.club.joinclub', $row->club_id) }}"
                    style="font-size: 14px;color:#990099;">{{$row->club->title}}</a></div>

                <div>
                    <a href="{{route('user.club.question.ask', $row->club_id)}}" class="btn text-light" id="custom_btn">Start new topic</a>
                    <button onclick="focusAndScroll()" class="btn text-light" id="custom_btn">Reply</button>
                    <a href="{{route('user.club.clubPosts', $row->club_id)}}" class="btn text-light" id="custom_btn">Back</a>
                </div>
            </div>
            <div class="row p-4">
                {!! $row->descriptions !!}
            </div>
            @if(!empty($row->image))
            <div class="row px-4">
               <a href="{{asset($row->image)}}" target="_blank" class="btn btn-sm btn-warning attachment" rel="noopener noreferrer">Attached-file.{{$extension}}</a> 
            </div>
            @endif  
            <div class="row mt-5 mb-5 offset-lg-1">
                <div class="col-lg-11">
                    @forelse ($comments as $key => $comment)
                        <div class="card" id="card">
                            <div class="card-header">
                                <div class="row py-2 d-flex justify-content-between align-items-center">     
                                    <div>
                                        <img src="{{ getProfile($comment->user->image) }}" width="22px" class="rounded-circle mr-2 img-fluid">
                                        <span id="suggestion-text">{{ $comment->user->name }}&nbsp;{{ $comment->user->last_name }}</span>&emsp;
                                        <i class="far fa-calendar" id="suggestion-text"></i>&nbsp; 
                                        <span id="suggestion-text">{{\Carbon\Carbon::parse($comment->created_at)->format('d F Y')}}</span>
                                    </div>
                                    @if($comment->user->id == auth()->user()->id)
                                    <div class="d-flex align-items-center">
                                        <a href="javascript:void(0)" id="reply-edit" title="Edit" data-comment-id="{{ $comment->id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24">
                                                <g fill="none" stroke="#666666" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                                    <path d="M7 7H6a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2v-1" />
                                                    <path d="M20.385 6.585a2.1 2.1 0 0 0-2.97-2.97L9 12v3h3zM16 5l3 3" />
                                                </g>
                                            </svg>
                                        </a>
                                        <form action="{{ route('user.club.reply.delete', ['id' => $comment->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <a title="Delete" style="cursor: pointer;" onclick="event.preventDefault(); 
                                                if(confirm('{{ __('Are you sure want to delete this comment ?') }}')) { this.parentNode.submit(); }">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                                    <g fill="none">
                                                        <path d="M24 0v24H0V0zM12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035c-.01-.004-.019-.001-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427c-.002-.01-.009-.017-.017-.018m.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093c.012.004.023 0 .029-.008l.004-.014l-.034-.614c-.003-.012-.01-.02-.02-.022m-.715.002a.023.023 0 0 0-.027.006l-.006.014l-.034.614c0 .012.007.02.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01z" />
                                                        <path fill="#df1111" d="M14.28 2a2 2 0 0 1 1.897 1.368L16.72 5H20a1 1 0 1 1 0 2l-.003.071l-.867 12.143A3 3 0 0 1 16.138 22H7.862a3 3 0 0 1-2.992-2.786L4.003 7.07A1.01 1.01 0 0 1 4 7a1 1 0 0 1 0-2h3.28l.543-1.632A2 2 0 0 1 9.721 2zm3.717 5H6.003l.862 12.071a1 1 0 0 0 .997.929h8.276a1 1 0 0 0 .997-.929zM10 10a1 1 0 0 1 .993.883L11 11v5a1 1 0 0 1-1.993.117L9 16v-5a1 1 0 0 1 1-1m4 0a1 1 0 0 1 1 1v5a1 1 0 1 1-2 0v-5a1 1 0 0 1 1-1m.28-6H9.72l-.333 1h5.226z" />
                                                    </g>
                                                </svg>
                                            </a>
                                        </form>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body" id="reply-box" data-comment-id="{{ $comment->id }}">                
                                {!! $comment->comments !!}
                            </div>
                            <div class="card-body d-none" id="reply-edit-form" data-comment-id="{{ $comment->id }}">
                                <form action="{{ route('user.club.reply.update', ['id' => $comment->id]) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row p-2">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <textarea name="msg" id="summernote" class="summernote form-control"
                                                cols="30" rows="5">{{ $comment->comments }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row px-3">
                                        <button type="submit" class="btn text-light px-5 mb-2" id="custom_btn">Update</button>
                                    </div>
                                </form>
                            </div>
                            
                        </div>
                    @empty
                        <p class="text-center fw-bold">No comments available</p>
                    @endforelse
                </div>
            </div>
            <form action="{{ route('user.club.question.reply') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row mt-5 p-2">
                    <div class="col-12">
                        <div class="form-group">
                            <input type="hidden" name="club_post_id" value="{{$row->id}}">
                            <label for="msg" class="form-label"><i class="fas fa-reply-all"></i> &nbsp; {{$row->title}} </label>
                            <textarea name="msg" id="summernote" class="summernote form-control"
                            cols="30" rows="5">{{ old('msg') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row px-3">
                    <button type="submit" class="btn text-light px-5 mb-2" id="custom_btn">Send</button>
                </div>
            </form>
        </div> 
    </div>
</div>
@endsection
@push("script")
<script>
    function focusAndScroll() {
        $('#summernote').summernote('focus');
        document.getElementById('summernote').scrollIntoView({ behavior: 'smooth' });
    }


    var replyEditButtons = document.querySelectorAll('#reply-edit');
    replyEditButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var commentId = $(this).data("comment-id");
            var replyBox = $("#reply-box[data-comment-id='" + commentId + "']");
            var replyEditForm = $("#reply-edit-form[data-comment-id='" + commentId + "']");
            
            if (replyEditForm.hasClass("d-none")) {
                replyBox.addClass("d-none");
                replyEditForm.removeClass("d-none");
            } else {
                replyBox.removeClass("d-none");
                replyEditForm.addClass("d-none");
            }
        });
    });
</script>
@endpush