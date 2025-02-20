<?php

namespace App\Http\Controllers\User;

use App\Models\ProductPageView;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\BookReview;
use App\Models\ProductView;
use App\Models\BorrowedBook;
use Illuminate\Http\Request;
use App\Models\ProductFavourite;
use App\Models\ProductCategoryMap;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class ReaderBookController extends Controller
{


    public function index(Request $request)
    {
        if (auth()->user()->role_id == 2 && auth()->user()->role_id == 3) {
            abort(404, 'Page not found');
        }

        $category = Category::where('status', '1')->get();
        $author = User::where('role_id', '2')->where('status', '1')->get();
        $user_id = Auth::user()->id;

        $books = Product::with('borrowedBooks', 'favouriteBooks')
            ->where('status', 10)
            ->latest();

        if (!empty($request->category)) {
            $productIds = ProductCategoryMap::where('product_category_id', $request->category)->pluck('product_id');
            $books->whereIn('id', $productIds);
        }

        if (!empty($request->author)) {
            $books->where('user_id', $request->author);
        }
        if (!empty($request->keyword)) {
            $books->where('title', 'LIKE', '%' . $request->keyword . '%');
        }

        if ($request->has('content') && !empty($request->get('content'))) {
            $books->where('is_paid', '0');
        }
        $products = $books->paginate(20);

        $products->getCollection()->transform(function ($book) use ($user_id) {
            $book->isBorrowed = $book->borrowedBooks->where('is_valid', '1')->where('user_id', $user_id)->isNotEmpty();
            $book->isBorrowedValid = is_borrowed($book->id);
            $book->isBought = is_bought($book->id);
            $book->next_date = $book->borrowedBooks->where('is_valid', '1')->where('user_id', $user_id)->first()->borrowed_nextdate ?? '';
            $book->isFavorite = $book->favouriteBooks->where('user_id', $user_id)->isNotEmpty();
            return $book;
        });

        $data = [
            'title' => 'All Books',
            'books' => $products,
            'categories' => $category,
            'authors' => $author,
        ];

        return view('user.book.reader.index', $data);
    }


    public function borrowed()
    {
        $user = auth()->user();
        if ($user->role_id == 2) {
            abort(404, 'Page not found');
        }
        $data['title'] = 'Library';
        $next_date = now()->subDays(2);
        $next_date = date('Y-m-d', strtotime($next_date));
        $query = BorrowedBook::whereHas('book', function ($q) {
            $q->with('author', 'category', 'reviews', 'AvgReview')
                ->where('status', '10');
        })
            ->where('user_id', $user->id)
            ->with('book')->where('is_valid', 1);
        if ($user->role_id == '3') {
            $data['books'] = $query->get();
        } else {
            $data['books'] = $query->where(function ($q){
                $q->where('borrowed_enddate', '>=', now())
                    ->orWhere('is_bought', 1);
            })->get();

        }


        return view('user.book.reader.borrowed_books', $data);
    }


    public function favourite()
    {
        if (auth()->user()->role_id == 2) {
            abort(404, 'Page not found');
        }
        $data['title'] = 'Favourite list';
        $favorite_ids = ProductFavourite::where('user_id', Auth::user()->id)->pluck('product_id')->toArray();
        $books = Product::with('borrowedBooks', 'favouriteBooks')->where('status', 10)->findMany($favorite_ids);
        $books->each(function ($book) {
            $book->isBorrowed = $book->borrowedBooks->where('is_valid', '1')->isNotEmpty();
            $book->isBought = $book->borrowedBooks()->where('user_id', auth()->user()->id)->where('is_valid', '1')->where('is_bought', 1)->exists();
        });
        $data['books'] = $books;
        return view('user.book.reader.favourite_books', $data);
    }

    public function details($slug)
    {
        $user = Auth::user();
        $book = Product::with('borrowedBooks', 'author')->where('slug', $slug)->where('status', 10)->firstOrFail();


        if ($book->file_type == 'url') {
            $isBorrowed = true;
        } elseif ($user->role_id == 1) {
            $isBorrowed = $book->borrowedBooks()->where('user_id', $user->id)->where('is_valid', '1')->where(function ($q){
                $q->where('borrowed_enddate', '>=', now())
                    ->orWhere('is_bought', 1);
            })->first();
        } elseif ($user->role_id == 3) {
            $isBorrowed = $book->borrowedBooks->where('user_id', $user->id)->where('is_valid', '1')->where('is_institution', '1')->first();
        } else {
            $isBorrowed = $book->where('user_id', $user->id)->first();
        }
        if ($isBorrowed) {
            $reviews = BookReview::where('book_id', $book->id)->get();
            $auth_review = BookReview::where('book_id', $book->id)->where('user_id', $user->id)->first();
            $viewed = ProductView::where('product_id', $book->id)->where('user_id', $user->id)->first();
            if ($viewed) {
                $viewed->total_view = $viewed->total_view + 1;
            } else {
                $viewed = new ProductView();
                $viewed->user_id = $user->id;
                $viewed->total_view = 1;
                $viewed->product_id = $book->id;
            }
            $reading_time = $viewed->book->reading_time > 0 ? $viewed->book->reading_time * 60 * 60 : 3600;
            $viewed->total_time = $reading_time;
            $viewed->last_view = now();
            $viewed->save();
            $data = [
                'title' => 'Book Details',
                'book' => $book,
                'viewed' => $viewed,
                'user' => $user,
                'reviews' => $reviews,
                'auth_review' => $auth_review,
                'avg_rating' => number_format($reviews->avg('rating'), 2),
                'total_review' => $reviews->count(),
            ];

            return view('user.book.reader.book_details', $data);
        } else {
            Toastr::warning('First, you need to borrow this book.', 'Warning');
            return redirect()->back();
        }

    }

    public function read(Request $request, $slug)
    {
        $book = Product::with('borrowedBooks')->where('slug', $slug)->firstOrFail();
        if ($book->file_type != 'pdf' || !file_exists($book->file_dir)) {
            abort(403, 'File not found');
        }


        $user = Auth::user();
        $viewed = ProductView::where('product_id', $book->id)->where('user_id', $user->id)->first();
        $reading_time = $book->reading_time > 0 ? $book->reading_time * 60 * 60 : 3600;
        $current_page = $viewed->current_page;
        $status = $viewed->status == 1;

        return view('user.book.reader.read', compact('book', 'viewed', 'reading_time', 'current_page', 'status'));

    }

    public function review(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000'
        ]);


        DB::beginTransaction();
        try {
            $review = BookReview::where('book_id', $id)->where('user_id', Auth::user()->id)->first();
            if (!$review) {
                $review = new BookReview();
            }
            $review->user_id = Auth::user()->id;
            $review->book_id = $id;
            $review->rating = $request->get('rating');
            $review->review = $request->get('review');
            $review->status = 0;
            $review->save();
            DB::commit();
            Toastr::success('Review saved successfully', 'Success');
        } catch (\Exception $e) {
            DB::rollback();
            Log::debug($e->getMessage());
            Toastr::error('Something is wrong!', 'Error');
        }

        return back();


    }

    public function reviewDelete($id)
    {

        try {
            DB::beginTransaction();
            $review = BookReview::find($id);
            $review->delete();
            DB::commit();
            Toastr::success('Review deleted successfully', 'Success');
        } catch (\Exception $e) {
            DB::rollback();
            Log::debug($e->getMessage());
            Toastr::error('Something is wrong!', 'Error');
        }

        return back();


    }

    public function borrowedStore($id)
    {
        if (auth()->user()->role_id == 2) {
            abort(404, 'Page not found');
        }

        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        $dates = getBookValidity();
        DB::beginTransaction();
        $user_id = Auth::user()->id;
        $book = Product::find($id);
        $next_date = now()->subDays(2);
        $next_date = date('Y-m-d', strtotime($next_date));
        $borrowedBook_count = BorrowedBook::where('user_id', $user_id)
            ->where('borrowed_enddate', '>=', now())
            ->count();


        try {
            if (!userPlanPrivilege() || (userPlanPrivilege()->library == 1 && $book->is_paid == 1)) {
                Toastr::warning(trans('To access this privilege, please upgrade your plan'), 'Warning', ["positionClass" => "toast-top-right"]);
                return redirect()->route('frontend.pricing');
            } elseif (userPlanPrivilege()->offerings == 1) {
                if ($borrowedBook_count < 1) {
                    $isOk = $this->handleBorrowing($id, $user_id, $dates);
                } else {
                    Toastr::warning(trans('To access this privilege, please upgrade your plan'), 'Warning', ["positionClass" => "toast-top-right"]);
                    return redirect()->route('frontend.pricing');
                }
            } else {
                $isOk = $this->handleBorrowing($id, $user_id, $dates);
            }
            DB::commit();
            if ($isOk == 'invalid') {
                Toastr::error('Borrowed book cannot be renewed.', 'Error');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error('Something went wrong', 'Error');

        }
        return redirect()->back();

    }

    public function handleBorrowing($id, $user_id, $dates, $is_institution = 0)
    {
        $borrowedBook = BorrowedBook::where('product_id', $id)->where('user_id', $user_id)->where('is_valid', 1)->first();
        if ($borrowedBook && $borrowedBook->borrowed_nextdate >= now()) {
            return 'invalid';
        }

        $borrowed = new BorrowedBook();
        $borrowed->fill([
            'product_id' => $id,
            'user_id' => $user_id,
            'is_valid' => 1,
            'is_institution' => $is_institution,
            'borrowed_startdate' => now(),
            'borrowed_enddate' => now()->addDays($dates['validDay']),
            'borrowed_nextdate' => now()->addDays($dates['validDay'] + $dates['extraDay']),
        ])->save();


        return $borrowed;
    }

    public function favoriteStore($id)
    {
        if (auth()->user()->role_id == 2) {
            abort(404, 'Page not found');
        }
        DB::beginTransaction();
        try {
            $fav = ProductFavourite::where('user_id', Auth::user()->id)->where('product_id', $id)->first();
            if ($fav) {
                $fav->delete();
                Toastr::success('Book is remove from favourite list.', 'Success');
            } else {
                $fav = new ProductFavourite();
                $fav->product_id = $id;
                $fav->user_id = Auth::user()->id;
                $fav->save();
                Toastr::success('Books is successfully added to favourite list.', 'Success');
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error('Something went wrong', 'Error');
        }
        return redirect()->back();

    }

    public function progress(Request $request, $id)
    {

        $viewed = ProductView::find($id);
        $reading_time = $viewed->book->reading_time > 0 ? $viewed->book->reading_time * 60 * 60 : 3600;
        $viewed->total_time = $reading_time;
        $viewed->save();
        $page = $request->page;
        $page_view = $viewed->page_views->where('page_no', $page)->first();
        $complete_page = $viewed->page_views()->where('status', 1)->count();

        if ($request->type == 'get') {

            if ($page_view) {
                $page_view->total_view = $page_view->total_view + 1;
            } else {
                $page_view = new ProductPageView();
                $page_view->product_id = $viewed->product_id;
                $page_view->user_id = $viewed->user_id;
                $page_view->product_view_id = $viewed->id;
                $page_view->total_view = 1;
                $page_view->page_no = $page;
                $page_view->page_total_time = $request->page_total_time;
                $page_view->page_stay_time = 0;
                $viewed->total_page = max($request->total_page, 1);
            }
            $page_view->save();
            if ($viewed->progress < 100) {
                $progress = ($complete_page / max($request->total_page, 1)) * 100;
                $viewed->progress = $progress;
                $viewed->current_page = $page;
                $viewed->page_stay_time = $request->page_stay_time;
            }
            if ($complete_page == $viewed->total_page && $viewed->stay_time >= $viewed->total_time) {
                $viewed->status = 1;
                $viewed->progress = 100;
            }
            $viewed->save();
            $status = $viewed->status == 1;
            return response()->json(['success' => true, 'progress' => $viewed, 'stay_time' => $page_view->page_stay_time, 'status' => $status]);
        }
        if ($request->page_stay_time) {
            $viewed->current_page = $page;
            $viewed->stay_time = $viewed->page_views->sum('page_stay_time');
            $viewed->page_stay_time = $request->page_stay_time;

            $page_view->page_stay_time = $request->page_stay_time;
            if ($request->page_stay_time >= $page_view->page_total_time) {
                $page_view->status = 1;
            }
            if (($complete_page == $viewed->total_page || $page == $viewed->total_page) && $viewed->stay_time >= $viewed->total_time) {
                $viewed->status = 1;
                $viewed->progress = 100;
            }
            $page_view->save();
            $viewed->save();

            return response()->json(['success' => true, 'progress' => $viewed->page_stay_time]);
        }
        return response()->json(['success' => true, 'progress' => $viewed->progress]);
    }

}
