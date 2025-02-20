@extends('frontend.layouts.app')
@section('title')
    {{ $title ?? 'Forum' }}

@endsection

@push('style')
    <style>
        .forumReply {
            display: inline-block;
            padding: 5px 10px;
            font-size: 12px;
            font-weight: 700;
            background: #8000802b;
            color: #800080;
            border-radius: 2px;
            -webkit-transition: 0.2s opacity;
            transition: 0.2s opacity;
            min-width: 74px;
            margin-bottom: 2px;
            text-align: center;
            transition: all 0.4s ease-in-out;
            -webkit-transition: all 0.4s ease-in-out;
            -moz-transition: all 0.4s ease-in-out;
            -ms-transition: all 0.4s ease-in-out;
            -o-transition: all 0.4s ease-in-out;
        }

        .forumReply:hover {
            background: #800080;
            color: #fff;
        }

        .likedislike.active {
            background-color: #800080 !important;
            color: #fff !important;
        }

        .widget-tags li a {
            color: #323232;
            text-transform: capitalize;
            padding: 8px 10px;
            font-size: 14px;
            border-radius: 4px;
            text-align: center;
            display: block;
            margin: 3px;
            font-family: 'Poppins', sans-serif;
            border: 1px solid #CCC;
            font-weight: 400;
        }

        .widget-tags li {
            display: inline-block;
        }

        .sbBtn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }

        .recent_topic ul li.active a {
            color: #800080;
        }

        .reportBtn {
            margin-top: 4px !important;
            margin-left: 4px !important;
        }
    </style>
@endpush

@section('content')
    <!-- ======================= breadcrumb start  ============================ -->
    <div class="breadcrumb-sec pt-4 pb-4">
        <div class="container">
            <div class="breadcrumb-item text-center">
                <h4>Forum</h4>
                <img src="{{ asset('assets/frontend/images/breadcrumb_shape.svg') }}" alt="">
            </div>
        </div>
    </div>
    <!-- ======================= breadcrumb end  ============================ -->

    <!-- ======================= forum start  ============================ -->
    <div class="forum-sec pb-5 mb-5">
        <div class="container">
            <div class="row gy-4 gy-lg-0 gx-0 gx-lg-5">
                <div class="col-lg-8">
                    <div class="forums_details">
                        <div class="forums_question border-bottom pb-4">
                            <div class="user_details d-flex position-relative mb-4">
                                @if ($forumDetails->getUser->image)
                                    <img src="{{ asset($forumDetails->getUser->image) }}" width="80"
                                         class="flex-shrink-0 me-3 rounded-circle shadow-sm"
                                         alt="{{ $forumDetails->getUser->name }}">
                                @else
                                    <img src="{{ asset('assets/images/default-user.png') }}" width="50"
                                         class="flex-shrink-0 me-3 rounded-circle shadow-sm"
                                         alt="{{ $forumDetails->getUser->name }}">
                                @endif
                                <div>
                                    <h5>{{ $forumDetails->getUser->name }}</h5>
                                    <span><i class="fa fa-calendar-alt"></i>
                                        {{  date('F j, Y \a\t g:i A', strtotime($forumDetails->created_at)) }}</span>
                                    <br>
                                    @if(Auth::check())
                                        @if(is_reported($forumDetails->getUser->id, Auth::user()->id))
                                            <button type="button" class="badge bg-success reportBtn">
                                                Reported
                                            </button>
                                        @elseif($forumDetails->getUser->id !== Auth::user()->id)
                                            <button type="button" class="badge bg-primary reportBtn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#reportUserModal">
                                                Report
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <h2><span>Q:</span> {{ $forumDetails->title }}</h2>
                            <div class="py-5 ">
                                {!! $forumDetails->descriptions !!}
                            </div>
                        </div>
                        <div class="replies_title pt-5 pb-5">
                            <h3>{{ $total_comments }} Comments</h3>
                        </div>
                        <!-- ans list -->
                        @foreach($comments as $item)
                            <div class="forums_ans_wrapper forumMedia mb-5">
                                <div class="user d-flex position-relative mb-4">
                                    <img src="{{ asset($item->getUser->image ?? 'assets/images/default-user.png') }}"
                                         width="50"
                                         class="flex-shrink-0 me-3 rounded-circle shadow-sm"
                                         alt="{{ $forumDetails->getUser->name }}">
                                    <div>
                                        <h5>{{ $item->getUser->name ?? 'N/A' }}</h5>
                                        <span>  <i class="fa fa-calendar-alt"></i>{{ \Carbon\Carbon::parse($item->created_at)->format('F j, Y \a\t g:i A') }}</span>
                                    </div>
                                </div>
                                <p>
                                    {!! $item->comments !!}
                                </p>
                                @auth
                                    <form action="{{ route('user.forumevent.commentstore') }}" method="post"
                                          id="comment">
                                        @csrf
                                        <input type="hidden" name="comment_parent_id" value="{{ $item->id }}">
                                        <input type="hidden" name="forum_id" value="{{ $forumDetails->id }}">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <textarea id="replyBox_{{ $item->id }}"
                                                              class="custom_form form-control mt-2 d-none"
                                                              name="comments" rows="5" placeholder="Reply"
                                                              required>{{ old('comments') }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <button type="submit" data-commentid="{{ $item->id }}"
                                                        class="btn btn_primary sbBtn d-none">Submit
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="mt-3">
                                        <a href="javascript:void(0)" class="forumReply forumcomment"
                                           data-commentid="{{ $item->id }}"> <i
                                                class="fa fa-reply-all"></i> Reply</a>
                                        <a href="javascript:void(0)"
                                           class="rowclass_{{ $item->id }} forumReply likedislike {{ getForumLike($item)['like']  ? 'active' : '' }}"
                                           data-like="1" data-commentid="{{ $item->id }}"
                                           data-forumid="{{ $forumDetails->id }}"> <i
                                                class="fa fa-thumbs-up"></i> Like</a>
                                        <a href="javascript:void(0)"
                                           class="rowclass_{{ $item->id }} forumReply likedislike {{ getForumLike($item)['dislike'] ? 'active' : '' }}"
                                           data-like="0" data-commentid="{{ $item->id }}"
                                           data-forumid="{{ $forumDetails->id }}"> <i
                                                class="fa fa-thumbs-down"></i> Dislike</a>
                                    </div>
                                @endauth
                                {{-- 2nd loop --}}

                                @if (isset($item->replies) && $item->replies()->count() > 0)
                                    @foreach ($item->replies as $rep)
                                        <div class="media media-author forumMedia mt-5" style="margin-left:50px">
                                            <div class="media-left">
                                                @if ($rep->getUser->image)
                                                    <img src="{{ asset($rep->getUser->image) }}" width="50"
                                                         class="flex-shrink-0 me-3 rounded-circle shadow-sm"
                                                         alt="{{ $rep->getUser->name }}">
                                                @else
                                                    <img src="{{ asset('assets/images/default-user.png') }}" width="50"
                                                         class="flex-shrink-0 me-3 rounded-circle shadow-sm"
                                                         alt="{{ $rep->getUser->name }}">
                                                @endif
                                            </div>
                                            <div class="media-body">
                                                <div class="mb-2">
                                                    <h6>{{ $rep->getUser->name ?? 'N/A' }} </h6>
                                                    <span
                                                        class="time">{{ Carbon\Carbon::parse($rep->created_at)->diffForHumans() }}</span>
                                                </div>
                                                <p>{{ $rep->comments }}</p>
                                                @auth
                                                    <form action="{{ route('user.forumevent.commentstore') }}"
                                                          method="post" id="comment">
                                                        @csrf
                                                        <input type="hidden" name="comment_parent_id"
                                                               value="{{ $rep->id }}">
                                                        <input type="hidden" name="forum_id"
                                                               value="{{ $forumDetails->id }}">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group mb-3">
                                                                  <textarea style="height: 122px;width: 45rem;"
                                                                            id="replyBox_{{ $rep->id }}"
                                                                            class="custom_form form-control d-none"
                                                                            name="comments" rows="5" placeholder="Reply"
                                                                            required>{{ old('comments') }}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <button type="submit" data-commentid="{{ $rep->id }}"
                                                                        class="btn btn_primary sbBtn d-none">Submit
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <div class="mt-3">
                                                        <a href="javascript:void(0)" class="forumReply forumcomment"
                                                           data-commentid="{{ $rep->id }}"> <i
                                                                class="fa fa-reply-all"></i>
                                                            Reply</a>
                                                        <a href="javascript:void(0)"
                                                           class="rowclass_{{ $rep->id }} forumReply likedislike {{ getForumLike($rep)['like'] ? 'active' : '' }}"
                                                           data-like="1" data-commentid="{{ $rep->id }}"
                                                           data-forumid="{{ $forumDetails->id }}"> <i
                                                                class="fa fa-thumbs-up"></i> Like</a>
                                                        <a href="javascript:void(0)"
                                                           class="rowclass_{{ $rep->id }} forumReply likedislike {{ getForumLike($rep)['dislike'] ? 'active' : '' }}"
                                                           data-like="0" data-commentid="{{ $rep->id }}"
                                                           data-forumid="{{ $forumDetails->id }}"> <i
                                                                class="fa fa-thumbs-down"></i> Dislike</a>
                                                    </div>
                                                @endauth

                                                {{-- 3rd loop --}}

                                                @if (isset($rep->replies) && $rep->replies()->count() > 0)
                                                    @foreach ($rep->replies as $rep3)
                                                        <div class="media media-author forumMedia mt-5"
                                                             style="margin-left:50px">
                                                            <div class="media-left">
                                                                @if ($rep3->getUser->image)
                                                                    <img src="{{ asset($rep3->getUser->image) }}"
                                                                         width="50"
                                                                         class="flex-shrink-0 me-3 rounded-circle shadow-sm"
                                                                         alt="{{ $rep3->getUser->name }}">
                                                                @else
                                                                    <img
                                                                        src="{{ asset('assets/images/default-user.png') }}"
                                                                        width="50"
                                                                        class="flex-shrink-0 me-3 rounded-circle shadow-sm"
                                                                        alt="{{ $rep3->getUser->name }}">
                                                                @endif
                                                            </div>
                                                            <div class="media-body">
                                                                <div class="media-heading">
                                                                    <h6>{{ $rep3->getUser->name }}</h6>
                                                                    <span
                                                                        class="time">{{ Carbon\Carbon::parse($rep3->created_at)->diffForHumans() }}</span>
                                                                </div>
                                                                <p>{{ $rep3->comments }}</p>
                                                                @auth
                                                                    <form
                                                                        action="{{ route('user.forumevent.commentstore') }}"
                                                                        method="post"
                                                                        id="comment">
                                                                        @csrf
                                                                        <input type="hidden" name="comment_parent_id"
                                                                               value="{{ $rep3->id }}">
                                                                        <input type="hidden" name="forum_id"
                                                                               value="{{ $forumDetails->id }}">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <div class="form-group mb-3">
                                                                              <textarea id="replyBox_{{ $rep3->id }}"
                                                                                        class="custom_form form-control d-none" name="comments" rows="5"
                                                                                        placeholder="Reply" required>{{ old('comments') }}</textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <button type="submit"
                                                                                        data-commentid="{{ $rep3->id }}"
                                                                                        class="btn btn_primary sbBtn d-none">
                                                                                    Submit
                                                                                </button>

                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                    <div class="mt-3">
                                                                        <a href="javascript:void(0)"
                                                                           class="rowclass_{{ $rep3->id }} forumReply likedislike {{ getForumLike($rep3)['like'] ? 'active' : '' }}"
                                                                           data-like="1"
                                                                           data-commentid="{{ $rep3->id }}"
                                                                           data-forumid="{{ $forumDetails->id }}"> <i
                                                                                class="fa fa-thumbs-up"></i> Like</a>
                                                                        <a href="javascript:void(0)"
                                                                           class="rowclass_{{ $rep3->id }} forumReply likedislike {{ getForumLike($rep3)['dislike'] ? 'active' : '' }}"
                                                                           data-like="0"
                                                                           data-commentid="{{ $rep3->id }}"
                                                                           data-forumid="{{ $forumDetails->id }}"> <i
                                                                                class="fa fa-thumbs-down"></i>
                                                                            Dislike</a>
                                                                    </div>
                                                                @endauth
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        @endforeach
                        @auth
                            <div class="section-row mt-5">
                                <div class="section-title mb-3">
                                    <h3 class="title">Leave a reply</h3>
                                </div>
                                <form action="{{ route('user.forumevent.commentstore') }}" method="post" id="comment">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="forum_id" value="{{ $forumDetails->id }}">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <textarea class="custom_form form-control" name="comments" rows="5"
                                                          placeholder="Message"
                                                          required>{{ old('comments') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn_primary">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endauth
                        @guest
                            <a href="javascript:void(0)" id="login" class="btn btn-outline-danger">Please login to
                                comment and
                                reply</a>
                        @endguest

                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="forums-sidebar">
                        <div class="forums_widget mb-5">
                            <div class="forums-heading mb-3">
                                <h3>Forums</h3>
                            </div>
                            <div class="forums-category">
                                <ul>
                                    @foreach ($categoryWiseForum as $item)
                                        <li>
                                            <a href="{{ route('frontend.forum', ['category' => $item->slug]) }}">{{ $item->name }}
                                                <span>{{ $item->forums_count }}</span></a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="forums_widget mb-5">
                            <div class="forums-heading mb-3">
                                <h3>Recent Questions</h3>
                            </div>
                            <div class="recent_topic">
                                @foreach ($recentQuestions as $item)
                                    <ul>
                                        <li class="{{ request('slug') == $item->slug ? 'active' : '' }}">
                                            <a href="{{ route('frontend.forum.details', ['slug' => $item->slug]) }}"><i
                                                    class="fa fa-comment-dots"></i> {{ $item->title }}</a>
                                        </li>
                                    </ul>
                                @endforeach

                            </div>
                        </div>
                        <div class="forums_widget mb-5">
                            <div class="forums-heading mb-3">
                                <h3>Forum Tags</h3>
                            </div>
                            <div class="widget">
                                <div class="widget-body">
                                    <ul class="widget-tags">
                                        @foreach ($forumTags as $item)
                                            <li>
                                                <a href="{{ route('frontend.forum') }}?tag={{ $item->slug }}">{{ $item->name }} </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ======================= forum end  ============================ -->
    @include('frontend.loginModal')
    <!-- Report User Modal -->
    <div class="modal fade" id="reportUserModal" tabindex="-1" role="dialog" aria-labelledby="reportUserModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportUserModalLabel">Report User</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Report User Form -->
                    <form action="{{ route('frontend.forum.report') }}" method="post">
                        @csrf
                        <input type="hidden" name="forum_id" value="{{ $forumDetails->id }}">
                        <input type="hidden" name="reported_id" value="{{ $forumDetails->getUser->id }}">
                        <div class="form-group">
                            <label for="reportMessage">Report Message</label>
                            <textarea class="form-control" id="reportMessage" name="message" rows="4"
                                      required></textarea>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-danger mt-3">Submit Report</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.forumcomment').click(function () {
                var commentId = $(this).data('commentid');
                var replyBox = $('#replyBox_' + commentId);
                var submitButton = $('button[data-commentid="' + commentId + '"]');
                replyBox.toggleClass('d-none');
                submitButton.toggleClass('d-none');
                $(this).find('i').toggleClass('fa-reply-all fa-regular fa-circle-xmark');
            });

            $(document).on('click', '.likedislike', function (e) {
                e.preventDefault();
                var el = $(this);
                var commentid = $(this).data('commentid');
                var like = $(this).data('like');
                var forumid = $(this).data('forumid');
                $.ajax({
                    type: 'POST',
                    url: "{{ route('user.forumevent.commentLike') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        commentid,
                        like,
                        forumid
                    },
                    success: function (data) {
                        if (data.status == true) {
                            toastr.success(data.message, 'Success', {
                                closeButton: true,
                                progressBar: true,
                            });
                            $('.rowclass_' + commentid).removeClass('active');
                            if (data.record == 1) {
                                el.addClass('active');
                            }
                        } else if (data.status == 'warning') {
                            toastr.warning(data.message, 'Warning', {
                                closeButton: true,
                                progressBar: true,
                            });
                        } else {
                            toastr.error(data.message, 'Error', {
                                closeButton: true,
                                progressBar: true,
                            });
                        }
                    }
                });
            });

            $(document).on('click', '#login', function () {
                $('#viewLoginModal').modal('show');
            });
            $(document).ready(function () {
                $("#formLogin").submit(function (e) {
                    e.preventDefault();
                    $.ajax({
                        type: "POST",
                        url: "{{ route('user.modalLogin.submit') }}",
                        data: {
                            _token: $("input[name='_token']").val(),
                            email: $("#email").val(),
                            password: $("#password").val(),
                        },
                        success: function (data) {
                            if (data.success) {
                                toastr.success(data.message, 'Success', {
                                    closeButton: true,
                                    progressBar: true,
                                });
                                $("#viewLoginModal").hide();
                                location.reload();
                            } else {
                                toastr.error(data.message, 'Error', {
                                    closeButton: true,
                                    progressBar: true,
                                });
                            }
                        },
                        error: function (error) {
                            console.error(error);
                        },
                    });
                });
            });
        });
    </script>

@endpush
