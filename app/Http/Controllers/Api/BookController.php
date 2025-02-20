<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\User\ReaderBookController;
use App\Models\BookReview;
use App\Models\BorrowedBook;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductFavourite;
use App\Models\ProductPageView;
use App\Models\ProductView;
use App\Models\User;
use App\Traits\RepoResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BookController extends Controller
{
    use RepoResponse;

    protected $user;


    public function __construct()
    {
        $user = Auth::guard('api')->user();
        $this->user = $user;
    }

    public function index(Request $request)
    {
        $query = Product::with('author', 'category')->where('status', 10)->latest();
        if (!empty($request->keyword)) {
            $query->where('title', 'LIKE', '%' . $request->keyword . '%');
        }


        if ($request->has('category') && !empty($request->category)) {

            $query->where('category_id', $request->category);
        }
        if (!empty($request->author_id)) {
            $query->where('user_id', $request->author_id);
        }


        //        if (!empty($request->publisher)) {
        //            $query->where('publisher', $request->publisher);
        //        }
        $books = $query->paginate(12);
        if ($books && $books->count() > 0) {

            $books->getCollection()->transform(function ($book) {
                $borrowed = $book->borrowedBooks()->where('user_id', $this->user->id)->where('is_valid', '1')->first();
                $book->is_favorite = is_favorite($book->id);
                $book->is_borrowed = is_borrowed($book->id);
                $book->is_bought = is_bought($book->id);
                $book->is_valid = $borrowed->is_valid ?? '';
                $book->borrowed_startdate = $borrowed->borrowed_startdate ?? '';
                $book->borrowed_enddate = $borrowed->borrowed_enddate ?? '';
                $book->borrowed_nextdate = $borrowed->borrowed_nextdate ?? '';
                $book->is_institution = $borrowed->is_institution ?? '';
                $book->avg_rating = $book->AvgReview();
                $book->total_review = $book->reviews()->count();
                return $book;
            });
            $message = 'Books is successfully found.';
        } else {
            $message = 'Books is not found.';
        }
        $code = 200;
        $status = 1;
        $data = [
            'books' => $books,
            'total_books' => $books->count(),
        ];
        return $this->apiResponse($status, $code, $message, '', $data);
    }

    public function borrowed()
    {
        $query = BorrowedBook::whereHas('book', function ($q) {
            $q->with('author', 'category', 'reviews', 'AvgReview')
                ->where('status', '10');
        })
            ->where('user_id', $this->user->id)
            ->with('book')->where('is_valid', 1);

        $user = Auth::user();
        $next_date = now()->subDays(2);
        $next_date = date('Y-m-d', strtotime($next_date));

        if (intval($user->role_id) == 1) {
            $query->where(function ($q){
                $q->where('borrowed_enddate', '>=', now())
                    ->orWhere('is_bought', 1);
            });
        }
        $books = $query->get();

        if ($books->count() > 0) {
            $modifiedBooks = $books->map(function ($book) {
                $bookData = $book->book;
                $bookData->is_valid = $book->is_valid ?? '';
                $bookData->borrowed_startdate = $book->borrowed_startdate ?? '';
                $bookData->borrowed_enddate = $book->borrowed_enddate ?? '';
                $bookData->borrowed_nextdate = $book->borrowed_nextdate ?? '';
                $bookData->is_institution = $book->is_institution ?? '';
                $bookData->is_favorite = is_favorite($bookData->id ?? 0);
                $bookData->is_borrowed = true;
                $bookData->avg_rating = $bookData->AvgReview();
                $bookData->total_review = $bookData->reviews()->count();

                return $bookData;
            });

            $data = [
                'books' => $modifiedBooks,
                'total_books' => $books->count(),
            ];

            return $this->apiResponse(1, 200, 'Books were successfully found.', '', $data);
        } else {
            return $this->apiResponse(0, 404, 'Books were not found.', '', []);
        }
    }

    public function favorite()
    {
        $books = ProductFavourite::whereHas('book', function ($q) {
            $q->with('author', 'category')
                ->where('status', '10');
        })
            ->where('user_id', $this->user->id)
            ->with('book')->paginate(12);
        if ($books->count() > 0) {
            $books->map(function ($book) {
                $borrowed = $book->book->borrowedBooks()
                    ->where('user_id', $this->user->id)
                    ->where('is_valid', '1')->first();
                $book->book->is_valid = $borrowed->is_valid ?? '';
                $book->book->borrowed_startdate = $borrowed->borrowed_startdate ?? '';
                $book->book->borrowed_enddate = $borrowed->borrowed_enddate ?? '';
                $book->book->borrowed_nextdate = $borrowed->borrowed_nextdate ?? '';
                $book->book->is_institution = $borrowed->is_institution ?? '';
                $book->book->is_favorite = is_favorite($book->book->id ?? 0);
                $book->book->is_borrowed = is_borrowed($book->book->id ?? 0);
                $book->book->avg_rating = $book->book->AvgReview();
                $book->book->total_review = $book->book->reviews()->count();
                return $book;
            });
            $data = [
                'books' => $books,
                'total_books' => $books->count(),
            ];
            return $this->apiResponse(1, 200, 'Books is successfully found.', '', $data);
        } else {
            $data = [
                'books' => $books,
                'total_books' => $books->count(),
            ];
            return $this->apiResponse(1, 200, 'Books is not found.', '', $data);
        }
    }


    public function borrowedStore($id)
    {
        $book = Product::find($id);
        if (!$book) {
            return $this->apiResponse(0, 404, 'Book is not found.', '', []);
        }
        $dates = getBookValidity();
        $user_id = Auth::user()->id;
        $next_date = now()->subDays(2);
        $next_date = date('Y-m-d', strtotime($next_date));
        $borrowedBook_count = BorrowedBook::where('user_id', $user_id)
            ->where('borrowed_nextdate', '>=', $next_date)
            ->count();
        $handle = new ReaderBookController();
        if (!userPlanPrivilege() || userPlanPrivilege()->offerings == 1) {
            if ($borrowedBook_count >= 1 || (userPlanPrivilege()->library == 1 && $book->is_paid == 1)) {
                return $this->apiResponse(0, 404, 'To access this privilege, please upgrade your plan', '', []);
            } else {
                $borrowed = $handle->handleBorrowing($id, $user_id, $dates);
            }
        } else {
            $borrowed = $handle->handleBorrowing($id, $user_id, $dates);
        }
        if ($borrowed == 'invalid') {
            return $this->apiResponse(0, 500, 'Borrowed book cannot be renewed.', '');
        }
        return $this->apiResponse(1, 200, 'Books is successfully added to borrowed..', '', $borrowed);
    }

    public function favoriteStore($id)
    {
        $book = Product::find($id);
        if (!$book) {
            return $this->apiResponse(0, 404, 'Book is not found.', '', []);
        }
        $fav = ProductFavourite::where('user_id', $this->user->id)->where('product_id', $id)->first();
        if ($fav) {
            $fav->delete();
            return $this->apiResponse(0, 403, 'Book is remove from favourite list.', '', []);
        } else {
            $fav = new ProductFavourite();
            $fav->product_id = $id;
            $fav->user_id = $this->user->id;
            $fav->save();
        }
        return $this->apiResponse(1, 200, 'Books is successfully added to favourite list.', '', $fav);
    }

    public function delete($id)
    {
        $product = Product::find($id);
        if (intval($this->user->role_id) !== 2 && $this->user->id !== $product->user_id) {
            return $this->apiResponse(0, 403, 'You are not allowed to access this.');
        }
        if (File::exists($product->file_dir)) {
            File::delete($product->file_dir);
        }
        if (File::exists($product->thumb)) {
            File::delete($product->thumb);
        }
        $product->delete();
        $product->borrowedBooks()->delete();
        $product->favouriteBooks()->delete();
        $product->reviews()->delete();
        $product->productViews()->delete();
        return $this->apiResponse(1, 200, 'Book is successfully deleted.', '', $product);
    }

    public function my_readers()
    {
        $user = Auth::user();
        $readers = $user->productViews()
            ->join('products as p', 'p.id', '=', 'product_views.product_id')
            ->join('users as u', 'u.id', '=', 'product_views.user_id')
            ->select('p.title as book_title', 'p.thumb as book_thumb', 'u.name as reader_first_name', 'u.last_name as reader_last_name', 'u.email as reader_email', 'product_views.total_view', 'product_views.updated_at as last_view_at')
            ->latest('product_views.total_view')
            ->get();

        if ($readers && $readers->count() > 0) {
            $data = [
                'title' => 'My Readers',
                'readers' => $readers,
                'author' => $user
            ];

            return $this->apiResponse(1, 200, 'Readers are successfully found.', '', $data);
        } else {
            return $this->apiResponse(0, 404, 'No reader found.', '', []);
        }
    }

    public function viewed()
    {
        $books = ProductView::with('book')->where('user_id', $this->user->id)->paginate(12);
        if ($books && $books->count() > 0) {
            $data = [
                'books' => $books,
                'total_books' => $books->count(),
            ];
            return $this->apiResponse(1, 200, 'Books are successfully found.', '', $data);
        } else {
            return $this->apiResponse(0, 404, 'No book found', '', []);
        }
    }

    public function my_books()
    {
        if (intval($this->user->role_id) !== 2) {
            return $this->apiResponse(0, 403, 'You are not allowed to access this.');
        }
        $books = Product::where('user_id', $this->user->id)->get();
        $data = [
            'books' => $books,
            'total_books' => $books->count(),
        ];
        if ($books->count() > 0) {
            return $this->apiResponse(1, 200, 'Books is successfully found.', '', $data);
        } else {
            return $this->apiResponse(0, 404, 'Books is not found.');
        }
    }

    public function pending_books()
    {
        if (intval($this->user->role_id) !== 2) {
            return $this->apiResponse(0, 403, 'You are not allowed to access this.');
        }
        $books = Product::where('user_id', $this->user->id)->where('status', '0')->get();
        if ($books && $books->count() > 0) {
            $data = [
                'books' => $books,
                'total_books' => $books->count(),
            ];
            return $this->apiResponse(1, 200, 'Books is successfully found.', '', $data);
        } else {
            return $this->apiResponse(0, 404, 'Books is not found.');
        }
    }

    public function declined_books()
    {
        if (intval($this->user->role_id) !== 2) {
            return $this->apiResponse(0, 403, 'You are not allowed to access this.');
        }
        $books = Product::where('user_id', $this->user->id)->where('status', '30')->get();
        $data = [
            'books' => $books,
            'total_books' => $books->count(),
        ];
        if ($books && $books->count() > 0) {
            return $this->apiResponse(1, 200, 'Books is successfully found.', '', $data);
        } else {
            return $this->apiResponse(0, 404, 'Books is not found.');
        }
    }

    public function store(Request $request)
    {

        if (intval($this->user->role_id) !== 2) {
            return $this->apiResponse(0, 403, 'You are not allowed to access this.');
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'category_id' => 'required|integer',
            'publisher' => 'required',
            'publisher_year' => 'required',
            'description' => 'required',
            'file_type' => 'required|in:pdf,audio,video,url',
            'pdf_book' => 'required_if:file_type,pdf|mimes:pdf',
            'reading_time' => 'required_if:file_type,pdf',
            'audio_book' => 'required_if:file_type,audio|mimes:mp3,wav,ogg,aac',
            'video_book' => 'required_if:file_type,video|mimes:mp4,avi,mkv,webm',
            'url_book' => 'required_if:file_type,url|url',
            'isbn10' => 'required',
            'book_for' => 'required|in:sale,library',
            'book_price' => 'required_if:book_for,sale',

        ]);
        if ($validator->fails()) {
            return $this->apiResponse(0, 422, 'Validation Error.', $validator->errors()->first(), $validator->errors()->toArray());
        }
        // return $request->all();
        DB::beginTransaction();
        try {

            $code = Product::count() === 0 ? 1001 : Product::max('code') + 1;

            $Book = new Product();
            $Book->code = $code;
            $Book->title = $request->title;
            $Book->sub_title = $request->sub_title;
            $Book->category_id = $request->category_id;
            $Book->user_id = $this->user->id;
            $Book->status = $request->status;
            $Book->file_type = $request->file_type;
            $Book->isbn10 = $request->isbn10;
            $Book->isbn13 = $request->isbn13;
            $Book->publisher = $request->publisher;
            $Book->size = $request->size;
            $Book->pages = $request->pages;
            $Book->edition = $request->edition;
            $Book->publisher_year = $request->publisher_year;
            $Book->description = $request->description;
            $Book->book_for = $request->book_for;
            $Book->book_price = $request->book_price;
            $Book->reading_time = $request->reading_time ?? 0;
            $Book->status = 0;

            $book_file = $request->file($request->file_type . '_book');
            if ($book_file) {
                $book = $book_file;
                $base_name_book = preg_replace('/\..+$/', '', $book->getClientOriginalName());
                $base_name_book = explode(' ', $base_name_book);
                $base_name_book = implode('-', $base_name_book);
                $base_name_book = Str::lower($base_name_book);
                $book_name = $base_name_book . "-" . uniqid() . "." . $book->getClientOriginalExtension();
                $file_path = 'uploads/product/file';
                $book->move(public_path($file_path), $book_name);
                $Book->file_dir = $file_path . '/' . $book_name;
            }
            if ($request->thumb) {
                $thumb = $request->file('thumb');
                $base_name_thumb = preg_replace('/\..+$/', '', $thumb->getClientOriginalName());
                $base_name_thumb = explode(' ', $base_name_thumb);
                $base_name_thumb = implode('-', $base_name_thumb);
                $base_name_thumb = Str::lower($base_name_thumb);
                $thumb_name = $base_name_thumb . "-" . uniqid() . "." . $thumb->getClientOriginalExtension();
                $thumb_path = 'uploads/product/thumb';
                $thumb->move(public_path($thumb_path), $thumb_name);
                $Book->thumb = $thumb_path . '/' . $thumb_name;
            }

            $book_url = $request->get('url_book');
            if ($book_url) {
                $url = getEmbeddedUrl($book_url);
                if ($url == '') {
                    return $this->apiResponse(0, 422, 'Validation Error.', 'Book url must be valid youtube link.');
                }

                $Book->file_dir = $url;
            }
            $Book->save();
        } catch (\Exception $e) {
            DB::rollback();
            $errorArray = [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ];
            return $this->apiResponse(0, 422, 'Book is not created.', $e->getMessage(), $errorArray);
        }
        DB::commit();
        return $this->apiResponse(1, 200, 'Book is successfully created.', '', $Book);
    }

    public function update(Request $request, $id)
    {
        $Book = Product::find($id);
        if (intval($this->user->role_id) !== 2 && $this->user->id !== $Book->user_id) {
            return $this->apiResponse(0, 403, 'You are not allowed to access this.');
        }
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:250',
            'category_id' => 'required|integer',
            'publisher' => 'required',
            'publisher_year' => 'required',
            'description' => 'required',
            'file_type' => 'required|in:pdf,audio,video,url',
            'reading_time' => 'required_if:file_type,pdf',
            'isbn10' => 'required',
            'book_for' => 'required|in:sale,library',
            'book_price' => 'required_if:book_for,sale',

        ], [
            'pdf_book.required_if' => 'The book file is required',
            'audio_book.required_if' => 'The book file is required',
            'video_book.required_if' => 'The book file is required',
            'pdf_book.mimes' => 'The book file must be pdf',
            'audio_book.mimes' => 'The book file must be audio',
            'video_book.mimes' => 'The book file must be video',
        ]);


        if ($validator->fails()) {
            return $this->apiResponse(0, 422, 'Validation Error.', $validator->errors()->first(), $validator->errors()->toArray());
        }
        DB::beginTransaction();
        try {


            $Book->title = $request->title;
            $Book->sub_title = $request->sub_title;
            $Book->category_id = $request->category_id;
            $Book->status = $request->status;
            $Book->user_id = $this->user->id;
            $Book->file_type = $request->file_type;
            $Book->isbn10 = $request->isbn10;
            $Book->isbn13 = $request->isbn13;
            $Book->publisher = $request->publisher;
            $Book->size = $request->size;
            $Book->pages = $request->pages;
            $Book->edition = $request->edition;
            $Book->publisher_year = $request->publisher_year;
            $Book->description = $request->description;
            $Book->reading_time = $request->reading_time ?? 0;
            $Book->book_for = $request->book_for;
            $Book->book_price = $request->book_price;

            $book_file = $request->file($request->file_type . '_book');
            if ($book_file) {
                $book = $book_file;
                $base_name_book = preg_replace('/\..+$/', '', $book->getClientOriginalName());
                $base_name_book = explode(' ', $base_name_book);
                $base_name_book = implode('-', $base_name_book);
                $base_name_book = Str::lower($base_name_book);
                $book_name = $base_name_book . "-" . uniqid() . "." . $book->getClientOriginalExtension();
                $file_path = 'uploads/product/file';
                $book->move(public_path($file_path), $book_name);
                $Book->file_dir = $file_path . '/' . $book_name;
            }
            if ($request->thumb) {
                $thumb = $request->file('thumb');
                $base_name_thumb = preg_replace('/\..+$/', '', $thumb->getClientOriginalName());
                $base_name_thumb = explode(' ', $base_name_thumb);
                $base_name_thumb = implode('-', $base_name_thumb);
                $base_name_thumb = Str::lower($base_name_thumb);
                $thumb_name = $base_name_thumb . "-" . uniqid() . "." . $thumb->getClientOriginalExtension();
                $thumb_path = 'uploads/product/thumb';
                $thumb->move(public_path($thumb_path), $thumb_name);
                $Book->thumb = $thumb_path . '/' . $thumb_name;
            }

            $book_url = $request->get('url_book');
            if ($book_url) {
                $url = getEmbeddedUrl($book_url);
                if ($url == '') {
                    return $this->apiResponse(0, 422, 'Validation Error.', 'Book url must be valid youtube link.');
                }
                $Book->file_dir = $url;
            }

            $Book->save();
        } catch (\Exception $e) {
            DB::rollback();
            $errorArray = [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ];
            return $this->apiResponse(0, 422, 'Book is not updated.', $e->getMessage(), $errorArray);
        }
        DB::commit();
        return $this->apiResponse(1, 200, 'Book is successfully updated.', '', $Book);
    }

    public function details($id)
    {
        $product = Product::with('author')->find($id);

        if (!$product) {
            return $this->apiResponse(0, 422, 'Book is not found.', '', []);
        }

        $next_date = now()->subDays(2);
        $next_date = date('Y-m-d', strtotime($next_date));
        $user = $this->user;
        if ($product->file_type == 'url') {
            $isBorrowed = true;
        } elseif ($user->role_id == 1) {
            $isBorrowed = $product->borrowedBooks()->where('user_id', $user->id)->where('is_valid', '1')->where(function ($q){
                $q->where('borrowed_enddate', '>=', now())
                    ->orWhere('is_bought', 1);
            })->first();
        } elseif ($user->role_id == 3) {
            $isBorrowed = $product->borrowedBooks()->where('user_id', $user->id)->where('is_valid', '1')->where('is_institution', '1')->first();
        } else {
            $isBorrowed = $product->where('user_id', $user->id)->first();
        }

        if (!$isBorrowed) {
            return $this->apiResponse(0, 422, 'First, you need to borrow this book.', '', []);
        }

        $product->author_status = $product->author->status ?? 0;
        $reviews = BookReview::with('user')->where('book_id', $product->id)->get();
        $auth_review = BookReview::where('book_id', $product->id)->where('user_id', $this->user->id)->exists();
        $viewed = ProductView::where('product_id', $id)->where('user_id', $this->user->id)->first();
        if ($viewed) {
            $viewed->total_view = $viewed->total_view + 1;
        } else {
            $viewed = new ProductView();
            $viewed->user_id = $this->user->id;
            $viewed->total_view = 1;
            $viewed->product_id = $id;
        }
        $reading_time = $viewed->book->reading_time > 0 ? $viewed->book->reading_time * 60 * 60 : 3600;
        $viewed->total_time = $reading_time;
        $viewed->last_view = now();
        $viewed->save();
        $viewed->total_reading_time = $product->reading_time;
        $data = [
            'book' => $product,
            'user' => $this->user,
            'reviews' => $reviews,
            'progress' => $viewed,
            'auth_review' => $auth_review,
            'avg_rating' => number_format($reviews->avg('rating'), 2),
            'total_review' => $reviews->count(),
        ];
        return $this->apiResponse(1, 200, 'Book is successfully found.', '', $data);
    }

    public function review(Request $request)
    {
        //        if (intval($this->user->role_id) == 2) {
        //            return $this->apiResponse(0, 403, 'You are not allowed to access this.');
        //        }

        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000',
            'book_id' => 'required|exists:products,id'

        ]);
        if ($validator->fails()) {
            return $this->apiResponse(0, 422, 'Validation Error.', $validator->errors()->first(), [], $validator->errors()->toArray());
        }


        DB::beginTransaction();
        try {
            $id = $request->get('book_id');
            $review = BookReview::where('book_id', $id)->where('user_id', $this->user->id)->first();
            if (!$review) {
                $review = new BookReview();
            }
            $review->user_id = $this->user->id;
            $review->book_id = $id;
            $review->rating = $request->get('rating');
            $review->review = $request->get('review');
            $review->status = 0;
            $review->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $errorArray = [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ];
            return $this->apiResponse(0, 422, 'Something is wrong.', $e->getMessage(), $errorArray);
        }
        return $this->apiResponse(1, 200, 'Thank you for your review', '', $review);
    }


    public function reviewDelete($id)
    {

        try {
            DB::beginTransaction();
            $review = BookReview::find($id);
            $review->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::debug($e->getMessage());
            $errorArray = [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ];
            return $this->apiResponse(0, 422, 'Something is wrong.', $e->getMessage(), $errorArray);
        }

        return $this->apiResponse(1, 200, 'Review removed successfully.', '', $review);
    }

    public function getSearchData()
    {
        $authors = User::select('id', 'name', 'last_name')->where('role_id', 2)->whereHas('products')->get();
        $publishers = Product::select('publisher')->where('status', 10)->distinct()->pluck('publisher')->toArray();
        $categories = Category::select(['id', 'name'])->where('status', 1)->get();
        $data = [
            'authors' => $authors,
            'publishers' => $publishers,
            'categories' => $categories
        ];

        return $this->apiResponse(1, 200, 'Books is successfully found.', '', $data);
    }

    /**
     * @param Request $request
     * @param $id
     * @return object
     */
    public function readPage(Request $request, $id)
    {
        $viewed = ProductView::find($id);
        if (!$viewed) {
            return $this->apiResponse(0, 404, 'Data not found.',);
        }

        if ($viewed->total_page) {
            $required = 'nullable';
        } else {
            $required = 'required';
        }
        $validator = Validator::make($request->all(), [
            'page' => 'required|integer',
            'total_page' => "$required|integer",
        ]);
        if ($validator->fails()) {
            return $this->apiResponse(0, 422, 'Validation Error.', $validator->errors()->first(), $validator->errors()->toArray());
        }
        $page = $request->page;
        if ($request->total_page) {
            $viewed->total_page = max($request->total_page, 1);
        }
        $viewed->current_page = $page;
        $viewed->save();
        $page_view = $viewed->page_views->where('page_no', $page)->first();

        if ($page_view) {
            $page_view->total_view = $page_view->total_view + 1;
        } else {
            $page_view = new ProductPageView();
            $page_view->product_id = $viewed->product_id;
            $page_view->user_id = $viewed->user_id;
            $page_view->product_view_id = $viewed->id;
            $page_view->total_view = 1;
            $page_view->page_no = $page;
            $page_view->page_stay_time = 0;
        }
        $page_view->page_total_time = intval($viewed->total_time / $viewed->total_page);

        $page_view->save();

        $status = $viewed->status == 1;
        $data = [
            'page_view' => $page_view,
            'viewed' => $viewed,
            'is_page_complete' => $page_view->status == 1,
            'is_book_complete' => $status,
        ];
        return $this->apiResponse(1, 200, 'Data successfully found.', '', $data);

    }

    public function progress(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'required|integer',
            'page_stay_time' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse(0, 422, 'Validation Error.', $validator->errors()->first(), $validator->errors()->toArray());
        }
        $viewed = ProductView::find($id);
        $page = $request->page;
        $page_view = $viewed->page_views->where('page_no', $page)->first();
        $complete_page = $viewed->page_views()->where('status', 1)->count();


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
        $data = [
            'page_view' => $page_view,
            'viewed' => $viewed,
            'is_page_complete' => $page_view->status == 1,
            'is_book_complete' => $viewed->status == 1,
        ];
        return $this->apiResponse(1, 200, 'Data successfully found.', '', $data);

    }


    public function analytic($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return $this->apiResponse(0, 404, 'Data not found.',);
        }
        $data = [
            'total_borrowed' => (int)$product->borrowedBooks()->count(),
            'total_complete' => (int)$product->productViews()->where('progress', 100)->count(),
            'total_viewed' => (int)$product->productViews()->sum('total_view'),
            'viewed' => $product->productViews()->with('user')->get(),
        ];
        return $this->apiResponse(1, 200, 'Data successfully found.', '', $data);
    }
}
