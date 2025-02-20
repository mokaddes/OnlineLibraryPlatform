@extends('frontend.layouts.app')
@section('title')
    {{ $title ?? 'Forum' }}
@endsection
@push('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" rel="stylesheet" />
    <style>
        .bootstrap-tagsinput .tag {
            margin-right: 2px;
            color: white !important;
            background-color: #0d6efd;
            padding: 4px 6px;
            border-radius: 6px;
            font-size: 13px;
        }

        .bootstrap-tagsinput {
            border: none;
            width: 100%;
            font-size: 14px;
            padding: 8px;
            border-radius: 6px;
            color: var(--black);
            border: 1px solid #e8e8e8 !important;
            border-top: none !important;
        }

        .addForum {
            max-width: 50rem;
            margin: auto;
            background: #fff;
            padding: 0px 20px;
            border-radius: 6px;
            border: 1px solid #f7f7f7;
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

        .forums-category ul li.active a {
            color: #800080;
        }

        .widget-tags li.active a {
            background-color: #800080;
            color: #fff;
        }

        .forums-category ul li span.active-bg {
            background-color: #800080;
            color: #fff !important;
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
                    <div
                        class="d-sm-flex mb-5 justify-content-between position-relative overflow-hidden align-items-center answer-action shadow-sm px-3 py-4 rounded">
                        <div class="action-content text-center text-sm-start">
                            <div class="image-wrap position-absolute top-0">
                                <img src="{{ asset('assets/frontend/images/search.gif') }}" class="img-fluid" width="120"
                                    alt="Can’t find an answer?">
                            </div>
                            <div class="content position-relative z-3">
                                <h2 class="ans-title mb-2">Can’t find an answer?</h2>
                                <p>Make use of a qualified tutor to get the answer</p>
                            </div>
                        </div>
                        <div class="text-center pt-3 pt-sm-0 position-relative z-3">
                            <a href="javascript:void(0)" class="btn btn_secondary askques"> Ask a Question</a>
                        </div>
                    </div>
                    <div class="container">
                        <div class="addForum mb-3 {{ old('forum') ? 'd-block' : 'd-none' }}">
                            <form action="{{ route('user.forum.ask.question') }}" method="post"
                                enctype="multipart/form-data" id="askForumFrom">
                                @csrf
                                <input type="hidden" name="forum" value="yes">
                                <div class="form-group mt-3 mb-3">
                                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title" class="custom_form form-control"
                                        placeholder="title" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="descriptions" class="form-label">Description <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="col-md-12">
                                            <textarea style="border-radius: 10px !important;" name="descriptions" id="summernote" class="summernote" rows="5"
                                                class="form-control">{{ old('descriptions') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-3 mb-3">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <div class="form-group">
                                                <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                                <select name="category_id" id="category_id" class="form-select form-control"
                                                    required>
                                                    <option value="">Select category</option>
                                                    @foreach ($data['cats'] as $key => $cat)
                                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="form-group">
                                                <label for="tags" class="form-label">Tag <span class="text-danger">*</span></label>
                                                <input type="text" name="tags" id="tags" class="form-control"
                                                    data-role="tagsinput"
                                                    value="{{ old('tags') ? implode(',', array_wrap(old('tags'))) : '' }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <button type="submit" class="btn btn_primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="forums_table table-responsive {{ old('forum') ? 'd-none' : 'd-block' }}">
                        <table class="table table-hover custom_table">
                            <thead>
                                <th style="width: 60%;">Forum</th>
                                <th style="width: 10%;" class="text-center">Replies</th>
                                <th style="width: 20%;" class="text-end">Last Post</th>
                            </thead>
                            <tbody>
                                @if ($rows->count() > 0)
                                    @foreach ($rows as $key => $row)
                                        <tr class="overflow-hidden position-relative">
                                            <td>
                                                <a href="{{ route('frontend.forum.details', ['slug' => $row->slug]) }}"
                                                    class="stretched-link title">{{ $row->title }}</a>
                                                <p class="info">
                                                    {!! Illuminate\Support\Str::limit($row->descriptions, $limit = 10, $end = '...') !!}
                                                </p>
                                            </td>
                                            <td class="text-center">{{ $row->get_comment_count }}</td>
                                            <td class="text-end">
                                                <span
                                                    class="date text-muted">{{ Carbon\Carbon::parse($row->created_at)->diffForHumans() }}</span>
                                                <div class="user">
                                                    <h4 class="text-sm me-2">{{ $row->getUser->name ?? 'N/A' }}</h4>
                                                    @if (isset($row->getUser->image))
                                                        <img src="{{ asset($row->getUser->image) }}" class="rounded-circle"
                                                            width="30" height="30"
                                                            alt="{{ $row->getUser->name ?? 'N/A' }}">
                                                    @else
                                                        <img src="{{ asset('assets/images/default-user.png') }}"
                                                            class="rounded-circle" width="30" height="30"
                                                            alt="{{ $row->getUser->name ?? 'N/A' }}">
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" class="text-center">No Post yet</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    {{ $rows->withQueryString()->links() }}
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
                                        <li class="{{ request('category') == $item->slug ? 'active' : '' }}"><a
                                                href="{{ route('frontend.forum', ['category' => $item->slug]) }}">
                                                {{ $item->name }}
                                                <span
                                                    class="{{ request('category') == $item->slug ? 'active-bg' : '' }}">{{ $item->forums_count }}</span></a>
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
                                            <li class="{{ request('tag') == $item->slug ? 'active' : '' }}"><a
                                                    href="{{ route('frontend.forum') }}?tag={{ $item->slug }}">{{ $item->name }}
                                                </a>
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

    @include('frontend.loginModal')
    <!-- ======================= forum end  ============================ -->
@endsection

@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
    <script>
        $('.askques').click(function(e) {
            e.preventDefault();
            var isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
            var nearestReplyBox = $(this).closest('.container').find('.addForum');
            if (isAuthenticated) {
                var userPlanPrivilege = {{ userPlanPrivilege()->forum ?? 'null' }};
                if (userPlanPrivilege !== 'null' && userPlanPrivilege == 3) {
                    nearestReplyBox.toggleClass('d-none');
                } else {
                    var msg = (<?= Auth::user() && Auth::user()->role_id != 1 ? "'To access this feature, please log in with a reader/user account'" :
                    "'To access this privilege, please upgrade your plan'" ?>);
                    toastr.warning(msg);
                }
            } else {
                $('#viewLoginModal').modal('show');
            }
        });
        $('#askForumFrom').submit(function (e) {
            let summernote = $('#summernote').val();
            let tags = $('#tags').val();
            if (summernote === '') {
                e.preventDefault();
                toastr.error('Please fill description field.');
            }else if (tags === '') {
                e.preventDefault();
                toastr.error('Please fill tags field.');
            }
        });

        $(document).ready(function() {
            $("#formLogin").submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "{{ route('user.modalLogin.submit') }}",
                    data: {
                        _token: $("input[name='_token']").val(),
                        email: $("#email").val(),
                        password: $("#password").val(),
                    },
                    success: function(data) {
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
                    error: function(error) {
                        console.error(error);
                    },
                });
            });
        });
    </script>
    <script>
        $('.summernote').summernote({
            height: 200,
        })
        $('.select2').select2()
    </script>
@endpush
