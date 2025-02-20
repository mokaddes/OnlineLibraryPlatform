<div class="card">
    <div class="card-body">
        <div class="card-footer card-comments rounded">
            @foreach ($reviews as $item)
                <div class="d-flex justify-content-between  align-items-center">
                    <div class="card-comment pt-3 pb-3">
                        <img class="img-circle img-sm" src="{{ asset(file_exists($item->user->image) ? $item->user->image : 'assets/images/default-user.png') }}" alt="User Image">
                        <div class="comment-text">
                        <span class="username">
                            {{ $item->user->name }} {{ $item->user->last_name }}
                            <span class="text-muted">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $item->rating)
                                        <span><i class="fa fa-star text-warning"></i></span>
                                    @else
                                        <span><i class="far fa-star text-warning"></i></span>
                                    @endif
                                @endfor
                            </span>
                        </span>
                            {!! $item->review !!}
                        </div>
                    </div>
                    @if($item->user_id == Auth::user()->id)
                        <div class="text-right">
                            <a href="javascript:void(0)" class="btn btn-sm btn-info editReview"><i class="fa fa-edit"></i></a>
                            <a href="{{ route('user.book.review.delete', $item->id) }}" onclick="return confirm('Are you sure, you want to delete?')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>


    </div>
</div>
