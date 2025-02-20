<div class="card card-info reviewForm" style="display: {{ $auth_review ? 'none' : 'block' }}">
    <div class="card-header">
        <div class="card-title align-items-center">
            <img class="img-circle mr-2 img-sm"
                 src="{{ asset(file_exists($user->image) ? $user->image : 'assets/images/default-user.png') }}" alt="User Image">
            <span>{{ $user->name }} {{ $user->last_name }}</span>
        </div>
    </div>
    <form action="{{ route('user.book.review', ['id' => $item->id]) }}" method="post">
        @csrf
        <input type="hidden" name="rating" id="rating" value="{{ $auth_review->rating ?? '' }}" class="rating">
        <div class="card-body">
            <div class="mb-3">
                <div id="rateYo"></div>
            </div>
            <div class="input-group">
                <textarea aria-label="comment" name="review" id="comment" class="form-control" cols="30" rows="6"
                          placeholder="Write you comments" required>{{ old('review',$auth_review->review ?? '') }}</textarea>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>

    </form>
</div>
