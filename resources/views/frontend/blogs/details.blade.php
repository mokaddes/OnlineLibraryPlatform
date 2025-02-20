@extends('frontend.layouts.app')
@section('title')
  {{ $title ?? 'Blog Details' }}
@endsection

@section('meta')
  <meta property="og:title" content="" />
  <meta property="og:description" content="" />
  <meta property="og:image" content="" />
@endsection

@push('style')
  <style>
    .likedislike.active {
      background-color: #800080 !important;
      color: #fff !important;
    }

    .sbBtn {
      padding: 0.25rem 0.5rem;
      font-size: 0.875rem;
      line-height: 1.5;
      border-radius: 0.2rem;
    }
    .media-heading {
      min-width: 300px;
    }
  </style>
@endpush

@section('content')
  <!-- ======================= breadcrumb start  ============================ -->
  <div class="breadcrumb_sec pt-4 pb-4">
    <div class="container">
      <div class="breadcrumb-item text-center">
        <h4>Blog Details</h4>
        <img src="{{ asset('assets/frontend/images/breadcrumb_shape.svg') }}" alt="">
      </div>
    </div>
  </div>
  <!-- ======================= breadcrumb end  ============================ -->

  <!-- ======================= blog start  ============================ -->
  <div class="blog_sec mb-5 mt-5">
    <div class="container">
      <div class="row">
        <div class="col-lg-9">
          <div class="blog_details">
            <div class="details_img mb-3">
              <img src="{{ asset($blogDetails->image) }}" class="w-100" alt="img">
            </div>
            <div class="details_content">
              <h2>{{ $blogDetails->title }}</h2>
              <p>{!! $blogDetails->descriptions !!}</p>
            </div>
          </div>
          <div class="section-row mb-5">
            @if ($total_comments > 0)
              <div class="section-title mb-3">
                <h3 class="title">{{ $total_comments }} Comments</h3>
              </div>
            @endif
            <div class="post-comments">
              @foreach ($comments as $item)
                <div class="media">
                  <div class="media-left">
                    @if ($item->getUser->image)
                      <img src="{{ asset($item->getUser->image) }}" alt="{{ $item->getUser->name }}">
                    @else
                      <img src="{{ asset('assets/images/default-user.png') }}" alt="{{ $item->getUser->name }}">
                    @endif
                  </div>
                  <div class="media-body">
                    <div class="media-heading d-flex justify-content-between align-items-center">
                      <div>
                        <h4>{{ $item->getUser->name }}</h4>
                        <span class="time">{{ Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</span>
                      </div>
                      @auth
                        @if($item->getUser->id == auth()->user()->id)
                        <div>
                          <div class="d-flex align-items-center">
                              <a href="javascript:void(0)" id="comment-edit" title="Edit" data-comment-id="{{ $item->id }}">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24">
                                      <g fill="none" stroke="#666666" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                          <path d="M7 7H6a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2v-1" />
                                          <path d="M20.385 6.585a2.1 2.1 0 0 0-2.97-2.97L9 12v3h3zM16 5l3 3" />
                                      </g>
                                  </svg>
                              </a>
                              <form action="{{ route('user.blogevent.commentdelete', ['id' => $item->id]) }}" method="POST">
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
                        </div>
                        @endif
                      @endauth
                    </div>
                    <div class="" id="reply-box" data-comment-id="{{ $item->id }}">
                      <p>{{ $item->comments }}</p>
                    </div>

                    @auth
                    <div class="" id="reply-box" data-comment-id="{{ $item->id }}">
                      <form action="{{ route('user.blogevent.commentstore') }}" method="post" id="comment">
                        @csrf
                        <input type="hidden" name="comment_parent_id" value="{{ $item->id }}">
                        <input type="hidden" name="blog_post_id" value="{{ $blogDetails->id }}">
                        <div class="row">
                          <div class="col-md-12">
                            <div class="form-group mb-3">
                              <textarea style="height: 122px;width: 55rem;" id="replyBox_{{ $item->id }}" class="custom_form form-control mt-2 d-none" name="comments" rows="5"
                                placeholder="Reply" required>{{ old('comments') }}</textarea>
                            </div>
                          </div>
                          <div class="col-md-4">
                            <button type="submit" class="btn btn_primary sbBtn mb-2 d-none"
                              data-commentid="{{ $item->id }}">Submit</button>
                          </div>
                        </div>
                      </form>
                      <a href="javascript:void(0)" class="reply comment" data-commentid="{{ $item->id }}"> <i
                          class="fa fa-reply-all"></i> Reply</a>
                      <a href="javascript:void(0)"
                        class="rowclass_{{ $item->id }} reply likedislike {{ $item->mylike_id == Auth::id() && $item->likedislike == '1' ? 'active' : '' }}"
                        data-like="1" data-commentid="{{ $item->id }}" data-blogid="{{ $blogDetails->id }}"> <i
                          class="fa fa-thumbs-up"></i> Like</a>
                      <a href="javascript:void(0)"
                        class="rowclass_{{ $item->id }} reply likedislike {{ $item->mylike_id == Auth::id() && $item->likedislike == '0' ? 'active' : '' }}"
                        data-like="0" data-commentid="{{ $item->id }}" data-blogid="{{ $blogDetails->id }}"> <i
                          class="fa fa-thumbs-down"></i> Dislike</a>
                    </div>
                    <div class="d-none" id="reply-edit-form" data-comment-id="{{ $item->id }}">
                      <form action="{{ route('user.blogevent.commentupdate', $item->id) }}" method="post"
                        id="comment">
                        @csrf
                        <div class="row">
                          <div class="col-md-12">
                            <div class="form-group mb-3">
                              <textarea class="custom_form form-control" name="comments" rows="5"
                                placeholder="Reply" required>{{ $item->comments }}</textarea>
                            </div>
                          </div>
                          <div class="col-md-4">
                            <button type="submit" class="btn sbBtn btn_primary mb-2">Submit</button>
                          </div>
                        </div>
                      </form>
                    </div>
                    @endauth



                    {{-- 2nd loop --}}
                    @php
                      $reply = getCommentReply($item->id);
                    @endphp
                    @if (isset($reply) && count($reply) > 0)
                      @foreach ($reply as $rep)
                        <div class="media media-author">
                          <div class="media-left">
                            @if ($rep->getUser->image)
                              <img src="{{ asset($rep->getUser->image) }}" alt="{{ $rep->getUser->name }}">
                            @else
                              <img src="{{ asset('assets/images/default-user.png') }}" alt="{{ $rep->getUser->name }}">
                            @endif
                          </div>
                          <div class="media-body">
                            <div class="media-heading d-flex justify-content-between align-items-center">
                              <div>
                                <h4>{{ $rep->getUser->name }}</h4>
                                <span class="time">{{ Carbon\Carbon::parse($rep->created_at)->diffForHumans() }}</span>
                              </div>
                              @auth
                                @if($rep->getUser->id == auth()->user()->id)
                                <div>
                                  <div class="d-flex align-items-center">
                                      <a href="javascript:void(0)" id="comment-edit" title="Edit" data-comment-id="{{ $rep->id }}">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24">
                                              <g fill="none" stroke="#666666" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                                  <path d="M7 7H6a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2v-1" />
                                                  <path d="M20.385 6.585a2.1 2.1 0 0 0-2.97-2.97L9 12v3h3zM16 5l3 3" />
                                              </g>
                                          </svg>
                                      </a>
                                      <form action="{{ route('user.blogevent.commentdelete', ['id' => $rep->id]) }}" method="POST">
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
                                </div>
                                @endif
                              @endauth
                            </div>
                            <div class="" id="reply-box" data-comment-id="{{ $rep->id }}">
                              <p>{{ $rep->comments }}</p>
                            </div>
                            @auth
                            <div class="" id="reply-box" data-comment-id="{{ $rep->id }}">
                              <form action="{{ route('user.blogevent.commentstore') }}" method="post" id="comment">
                                @csrf
                                <input type="hidden" name="comment_parent_id" value="{{ $rep->id }}">
                                <input type="hidden" name="blog_post_id" value="{{ $blogDetails->id }}">
                                <div class="row">
                                  <div class="col-md-12">
                                    <div class="form-group mb-3">
                                      <textarea style="height: 122px;width: 45rem;" id="replyBox_{{ $rep->id }}" class="custom_form form-control d-none" name="comments" rows="5"
                                        placeholder="Reply" required>{{ old('comments') }}</textarea>
                                    </div>
                                  </div>
                                  <div class="col-md-4">
                                    <button type="submit" data-commentid="{{ $rep->id }}"
                                      class="btn btn_primary sbBtn mb-2 d-none">Submit</button>
                                  </div>
                                </div>
                              </form>
                              <a href="javascript:void(0)" class="reply comment" data-commentid="{{ $rep->id }}">
                                <i class="fa fa-reply-all"></i>
                                Reply</a>
                              <a href="javascript:void(0)"
                                class="rowclass_{{ $rep->id }} reply likedislike {{ $rep->mylike_id == Auth::id() && $rep->likedislike == '1' ? 'active' : '' }}"
                                data-like="1" data-commentid="{{ $rep->id }}"
                                data-blogid="{{ $blogDetails->id }}"> <i class="fa fa-thumbs-up"></i> Like</a>
                              <a href="javascript:void(0)"
                                class="rowclass_{{ $rep->id }} reply likedislike {{ $rep->mylike_id == Auth::id() && $rep->likedislike == '0' ? 'active' : '' }}"
                                data-like="0" data-commentid="{{ $rep->id }}"
                                data-blogid="{{ $blogDetails->id }}"> <i class="fa fa-thumbs-down"></i> Dislike</a>
                            </div>
                            <div class="d-none" id="reply-edit-form" data-comment-id="{{ $rep->id }}">
                              <form action="{{ route('user.blogevent.commentupdate', $rep->id) }}" method="post"
                                id="comment">
                                @csrf
                                <div class="row">
                                  <div class="col-md-12">
                                    <div class="form-group mb-3">
                                      <textarea class="custom_form form-control" name="comments" rows="5"
                                        placeholder="Reply" required>{{ $rep->comments }}</textarea>
                                    </div>
                                  </div>
                                  <div class="col-md-4">
                                    <button type="submit" class="btn sbBtn btn_primary mb-2">Submit</button>
                                  </div>
                                </div>
                              </form>
                            </div>
                            @endauth

                            {{-- 3rd loop --}}
                            @php
                              $reply3 = getCommentReply($rep->id);
                            @endphp
                            @if (isset($reply3) && count($reply3) > 0)
                              @foreach ($reply3 as $rep3)
                                <div class="media media-author">
                                  <div class="media-left">
                                    @if ($rep3->getUser->image)
                                      <img src="{{ asset($rep3->getUser->image) }}" alt="{{ $rep3->getUser->name }}">
                                    @else
                                      <img src="{{ asset('assets/images/default-user.png') }}"
                                        alt="{{ $rep3->getUser->name }}">
                                    @endif
                                  </div>
                                  <div class="media-body">
                                    <div class="media-heading d-flex justify-content-between align-items-center">
                                      <div>
                                        <h4>{{ $rep3->getUser->name }}</h4>
                                        <span class="time">{{ Carbon\Carbon::parse($rep3->created_at)->diffForHumans() }}</span>
                                      </div>
                                      @auth
                                        @if($rep3->getUser->id == auth()->user()->id)
                                        <div>
                                          <div class="d-flex align-items-center">
                                              <a href="javascript:void(0)" id="comment-edit" title="Edit" data-comment-id="{{ $rep3->id }}">
                                                  <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24">
                                                      <g fill="none" stroke="#666666" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                                          <path d="M7 7H6a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2v-1" />
                                                          <path d="M20.385 6.585a2.1 2.1 0 0 0-2.97-2.97L9 12v3h3zM16 5l3 3" />
                                                      </g>
                                                  </svg>
                                              </a>
                                              <form action="{{ route('user.blogevent.commentdelete', ['id' => $rep3->id]) }}" method="POST">
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
                                        </div>
                                        @endif
                                      @endauth
                                    </div>
                                    <div class="" id="reply-box" data-comment-id="{{ $rep3->id }}">
                                      <p>{{ $rep3->comments }}</p>
                                    </div>
                                    @auth
                                    <div class="" id="reply-box" data-comment-id="{{ $rep3->id }}">
                                      <form action="{{ route('user.blogevent.commentstore') }}" method="post"
                                        id="comment">
                                        @csrf
                                        <input type="hidden" name="comment_parent_id" value="{{ $rep3->id }}">
                                        <input type="hidden" name="blog_post_id" value="{{ $blogDetails->id }}">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <div class="form-group mb-3">
                                              <textarea id="replyBox_{{ $rep3->id }}" class="custom_form form-control d-none" name="comments" rows="5"
                                                placeholder="Reply" required>{{ old('comments') }}</textarea>
                                            </div>
                                          </div>
                                          <div class="col-md-4">
                                            <button type="submit" data-commentid="{{ $rep3->id }}"
                                              class="btn btn_primary sbBtn mb-2 d-none">Submit</button>
                                          </div>
                                        </div>
                                      </form>
                                      <a href="javascript:void(0)"
                                        class="rowclass_{{ $rep3->id }} reply likedislike {{ $rep3->mylike_id == Auth::id() && $rep3->likedislike == '1' ? 'active' : '' }}"
                                        data-like="1" data-commentid="{{ $rep3->id }}"
                                        data-blogid="{{ $blogDetails->id }}"> <i class="fa fa-thumbs-up"></i> Like</a>
                                      <a href="javascript:void(0)"
                                        class="rowclass_{{ $rep3->id }} reply likedislike {{ $rep3->mylike_id == Auth::id() && $rep3->likedislike == '0' ? 'active' : '' }}"
                                        data-like="0" data-commentid="{{ $rep3->id }}"
                                        data-blogid="{{ $blogDetails->id }}"> <i class="fa fa-thumbs-down"></i>
                                        Dislike</a>
                                    </div>
                                    <div class="d-none" id="reply-edit-form" data-comment-id="{{ $rep3->id }}">
                                      <form action="{{ route('user.blogevent.commentupdate', $rep3->id) }}" method="post"
                                        id="comment">
                                        @csrf
                                        <div class="row">
                                          <div class="col-md-12">
                                            <div class="form-group mb-3">
                                              <textarea class="custom_form form-control" name="comments" rows="5"
                                                placeholder="Reply" required>{{ $rep3->comments }}</textarea>
                                            </div>
                                          </div>
                                          <div class="col-md-4">
                                            <button type="submit" class="btn sbBtn btn_primary mb-2">Submit</button>
                                          </div>
                                        </div>
                                      </form>
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
                </div>
              @endforeach
            </div>
          </div>
          @auth
            <div class="section-row">
              <div class="section-title mb-3">
                <h3 class="title">Leave a comment</h3>
              </div>
              <form action="{{ route('user.blogevent.commentstore') }}" method="post" id="comment">
                @csrf
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                <input type="hidden" name="blog_post_id" value="{{ $blogDetails->id }}">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group mb-3">
                      <textarea class="custom_form form-control" name="comments" rows="5" placeholder="Message" required>{{ old('comments') }}</textarea>
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
            <a href="javascript:void(0)" id="login" class="btn btn-outline-danger">Please login to comment and
              reply</a>
          @endguest
          @if ($blogs->count() > 0)
            <!-- related post -->
            <div class="related_post blog_wrap mt-5">
              <div class="title mb-5">
                <h3>You may also like...</h3>
              </div>
              <!-- blog item -->
              @foreach ($blogs as $item)
                <div class="blog_list mb-4">
                  <div class="row bg-light position-relative align-items-center p-2 rounded">
                    <div class="col-md-3 mb-3 mb-md-0">
                      <div class="blog_img text-center text-md-start">
                        <a href="{{ route('frontend.blogs.details', ['slug' => $item->slug]) }}">
                          <img src="{{ asset($item->image) }}" class="flex-shrink-0 me-md-3 img-fluid" alt="">
                        </a>
                      </div>
                    </div>
                    <div class="col-md-9">
                      <div class="blog_article">
                        <h5>
                          <a
                            href="{{ route('frontend.blogs.details', ['slug' => $item->slug]) }}">{{ $item->title }}</a>
                        </h5>
                        <p>{!! $item->short_descriptions !!}</p>
                        <a href="{{ route('frontend.blogs.details', ['slug' => $item->slug]) }}" class="btn">Read
                          More</a>
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
            <!-- related post -->
          @endif
        </div>
        <div class="col-lg-3">
          <div class="blog_sidebar position-sticky" style="top:1rem;">
            <div class="heading text-center">
              <h3>Categories</h3>
            </div>
            <div class="widget-body">
              <ul class="widget-list">
                @foreach ($blogCategories as $item)
                  <li><a href="{{ route('frontend.blogs', ['category' => $item->slug]) }}">{{ $item->name }}
                      <span>({{ $item->blogs_count }})</span></a>
                  </li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    {{-- View modal --}}
    @include('frontend.loginModal')
  </div>
  <!-- ======================= blog end  ============================ -->
@endsection

@push('script')
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {
      var baseurl = $('#baseurl').val();

      $('.comment').click(function() {
        var commentId = $(this).data('commentid');
        var replyBox = $('#replyBox_' + commentId);
        var submitButton = $('button[data-commentid="' + commentId + '"]');
        replyBox.toggleClass('d-none');
        submitButton.toggleClass('d-none');
        $(this).find('i').toggleClass('fa-reply-all fa-regular fa-circle-xmark');
      });

      $(document).on('click', '.likedislike', function(e) {
        e.preventDefault();
        // alert(likedislike);
        var el = $(this);
        var commentid = $(this).data('commentid');
        var like = $(this).data('like');
        var blogid = $(this).data('blogid');
        // var url = baseurl + '/user/blogevent/commentLike';
        $.ajax({
          type: 'POST',
        //   url: url,
          url: "{{ route('user.blogevent.commentLike') }}",
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data: {
            commentid,
            like,
            blogid
          },
          success: function(data) {
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
            }
            else {
              toastr.success(data.message, 'Error', {
                closeButton: true,
                progressBar: true,
              });
            }
          }
        });
      });


      $(document).on('click', '#login', function() {
        $('#viewLoginModal').modal('show');
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
    });

    var commentEditButtons = document.querySelectorAll('#comment-edit');
    commentEditButtons.forEach(function(button) {
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
