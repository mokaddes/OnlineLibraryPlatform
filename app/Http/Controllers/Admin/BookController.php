<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Mail\Allmail;
use App\Models\Book;
use App\Models\BorrowedBook;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductPageView;
use App\Models\ProductView;
use App\Models\User;
use App\Traits\Notification;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\ProductCategoryMap;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class BookController extends Controller
{
    public $user;
    protected $book;
    use Notification;

    public function __construct(Book $book)
    {
        $this->book = $book;
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('admin.book.index')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $data['title'] = 'Books';
        $user = $request->user;
        $data['type'] = "";
        $user_details = User::where('id', $user)->first();
        if ($request->books == 'borrowed') {
            $borrowed_products = BorrowedBook::where('user_id', $user)->get();

            $product_ids = $borrowed_products->pluck('product_id')->unique();

            $products = Product::whereIn('id', $product_ids)
                ->latest('id')
                ->get()
                ->each(function ($book) use ($user_details, $borrowed_products) {
                    $product_borrowed = $borrowed_products->where('product_id', $book->id);

                    if ($product_borrowed->where('is_valid', 1)->count() > 1) {
                        $latest_borrowed = $product_borrowed->where('is_valid', 1)->sortByDesc('borrowed_nextdate')->first();
                        $next_date = $latest_borrowed->borrowed_enddate;
                        $borrowed_id = $latest_borrowed->id;
                        $is_valid = 1;
                    } else {
                        $borrowedBook = $product_borrowed->first();
                        $next_date = $borrowedBook->borrowed_enddate;
                        $borrowed_id = $borrowedBook->id;
                        $is_valid = $borrowedBook->is_valid;
                    }

                    $remaining_days = now()->diffInDays($next_date, false);

                    $book->borrowed_valid = $is_valid;
                    $book->borrowed_id = $borrowed_id;
                    $book->borrowed_user = $user_details;
                    $book->remaining_days = $remaining_days;
                });

            $data['rows'] = $products;

            $data['heading'] = "All Borrowed Books - " . $user_details->name . " " . $user_details->last_name;
            $data['type'] = "borrowed";
            $data['add_button'] = "0";
        } elseif ($request->books == 'published') {

            $data['rows'] = Product::latest('id')->where('user_id', $user)->get();
            $user_details = User::where('id', $user)->first();
            $data['heading'] = "All Books - " . $user_details->name . " " . $user_details->last_name;
            $data['type'] = "published";
            $data['add_button'] = "0";
        } else {
            $data['rows'] = Product::latest('id')->get();
            $data['heading'] = "All Books";
            $data['add_button'] = "1";
        }
        $data['authors'] = User::where('status', '1')->where('role_id', '2')->get();
        return view('admin.book.index', compact('data'));
    }


    public function create()
    {
        if (is_null($this->user) || !$this->user->can('admin.book.create')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $data['title'] = 'Book';
        $data['categories'] = Category::where('status', '1')->get();
        $data['authors'] = User::where('status', '1')->where('role_id', '2')->get();
        return view('admin.book.create', compact('data'));
    }

    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('admin.book.store')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $request->validate([
            'title' => 'required',
            'category_id' => 'required',
            'user_id' => 'required',
            'publisher' => 'required',
            'publisher_year' => 'required',
            'description' => 'required',
            'file_type' => 'required',
            'pdf_book' => 'required_if:file_type,pdf',
            'reading_time' => 'required_if:file_type,pdf',
            'audio_book' => 'required_if:file_type,audio',
            'video_book' => 'required_if:file_type,video',
            'url_book' => 'required_if:file_type,url',
            'isbn10' => 'required',
            'book_for' => 'required',
            'book_price' => 'required_if:book_for,sale',

        ], [
            'book_for.required' => 'The book distribution is required',
            'book_price.required_if' => 'The book price is required',
            'pdf_book.required_if' => 'The book file is required',
            'reading_time.required_if' => 'The reading time field is required',
            'audio_book.required_if' => 'The book file is required',
            'video_book.required_if' => 'The book file is required',
            'url_book.required_if' => 'The book url field is required',

        ]);
        try {
            DB::beginTransaction();


            $user = User::findOrFail($request->user_id);
            $Book = new Product();

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
            $book_url = $request->get('url_book');
            if ($book_url) {
                $url = getEmbeddedUrl($book_url);
                if ($url == '') {
                    Toastr::error('Youtube url is not valid');
                    return redirect()->back()->withInput();
                }
                $Book->file_dir = $url;
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

            $code = Product::count() === 0 ? 1001 : Product::max('code') + 1;

            $author = $user->name . ' ' . $user->last_name;

            $marcData = createMARCRecord($request->title, $author, $request->isbn10, $request->publisher, $request->publisher_year, '');

            $Book->code = $code;
            $Book->title = $request->title;
            $Book->sub_title = $request->sub_title;
            // $Book->category_id = $request->category_id;
            $Book->category_id = $request->category_id[0];
            $Book->admin_id = Auth::user()->id;
            $Book->user_id = $request->user_id;
            $Book->status = $request->status;
            $Book->file_type = $request->file_type;

            $Book->isbn10 = $request->isbn10;
            $Book->isbn13 = $request->isbn13;
            $Book->publisher = $request->publisher;
            $Book->size = $request->size;
            $Book->pages = $request->pages;
            $Book->edition = $request->edition;
            $Book->publisher_year = $request->publisher_year;
            $Book->authors = $request->author;
            $Book->description = $request->description;
            $Book->status = '10';
            $Book->is_highlight = $request->is_highlight ?? 0;
            $Book->is_paid = $request->is_paid ?? 0;
            $Book->marc_data = $marcData;
            $Book->reading_time = $request->reading_time ?? 0;
            $Book->book_for = $request->book_for;
            if($request->book_for == 'sale') {
                $Book->book_price = $request->book_price ?? 0;
            } else {
                $Book->book_price = 0;
            }
            $Book->save();

            if(isset($request->category_id) && !empty($request->category_id))
            {
                foreach ($request->category_id as $id) {
                    ProductCategoryMap::create([
                        'product_id' => $Book->id,
                        'product_category_id' => $id
                    ]);
                }
            }

            DB::commit();

            $this->bookPublishedNotify($Book, $user);

        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Something went wrong! Please try agin', 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->back()->withInput();
        }
        Toastr::success(trans('Book Created Successfully!'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->route('admin.book.index');
    }

    /**
     * @param Product $Book
     * @param $user
     * @return void
     */
    public function bookPublishedNotify(Product $book, $user): void
    {
        $title = 'A new book with title ' . $book->title . ' is published.';
        $author_title = 'Your book with title ' . $book->title . ' is published.';
        $users = User::where('status', 1)->where('role_id', 1)->where('email_verified_at', '!=', null)->get();
        foreach ($users as $us) {
            $this->saveUserNotification($title, 'user.book.index', 'new_book', 'user', $us->id);
        }
        $this->saveUserNotification($author_title, 'admin.book.index', 'author_book', 'user', $user->id);
        $this->sendUserPushNotification($users->pluck('id')->toArray(), 'Book published', $title, route('user.book.index'));
        $this->sendUserPushNotification([$user->id], 'Book publish', $author_title, route('admin.book.index'));
    }

    public function edit($id)
    {
        if (is_null($this->user) || !$this->user->can('admin.book.edit')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $data['title'] = 'Book';
        $data['categories'] = Category::where('status', '1')->get();
        $data['authors'] = User::where('status', '1')->where('role_id', '2')->get();
        $book = Product::find($id);
        $data['row'] = $book;
        return view('admin.book.edit', compact('data'));
    }

    public function view()
    {
        if (is_null($this->user) || !$this->user->can('admin.book.view')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $data['title'] = 'View Book';
        return view('admin.book.view', compact('data'));
    }

    public function delete($id)
    {
        if (is_null($this->user) || !$this->user->can('admin.book.delete')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $product = Product::find($id);
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

        Toastr::success('Book deleted successfully');
        return redirect()->route('admin.book.index');
    }

    public function month_book($id, $month_book)
    {

        try {
            Product::where('id', '!=', $id)->update(['is_book_of_month' => 0]);
            $book = Product::findOrFail($id);
            $book->is_book_of_month = $month_book;
            $book->save();
            Toastr::success('Book updated successfully');
            return redirect()->route('admin.book.index');
        } catch (\Exception $e) {
            Toastr::error('Something went wrong');
            return redirect()->route('admin.book.index');
        }

    }

    public function update(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('admin.book.update')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }
        $this->validate($request, [
            'title' => 'required|max:100',
            'category_id' => 'required',
            'reading_time' => 'required_if:file_type,pdf',
            'book_for' => 'required',
            'book_price' => 'required_if:book_for,sale',
        ], [
            'book_for.required' => 'The book distribution is required',
            'book_price.required_if' => 'The book price is required',
            'reading_time.required_if' => 'The reading time field is required',
        ]);

        DB::beginTransaction();
        try {


            $user = User::where('id', $request->user_id)->firstOrfail();

            $Book = Product::find($request->id);
            $Book->title = $request->title;
            $Book->sub_title = $request->sub_title;
            $Book->category_id = $request->category_id[0];
            $Book->user_id = $request->user_id;
            $Book->file_type = $request->file_type;
            $Book->isbn10 = $request->isbn10;
            $Book->isbn13 = $request->isbn13;
            $Book->publisher = $request->publisher;
            $Book->authors = $request->author;
            $Book->size = $request->size;
            $Book->pages = $request->pages;
            $Book->edition = $request->edition;
            $Book->publisher_year = $request->publisher_year;
            $Book->description = $request->description;

            $author = $user->name . ' ' . $user->last_name;
            $Book->is_highlight = $request->is_highlight ?? 0;
            $Book->is_paid = $request->is_paid ?? 0;
            $marcData = createMARCRecord($request->title, $author, $request->isbn10, $request->publisher, $request->publisher_year, '');
            $Book->marc_data = $marcData;
            $Book->reading_time = $request->reading_time ?? 0;
            $Book->book_for = $request->book_for;
            if($request->book_for == 'sale') {
                $Book->book_price = $request->book_price ?? 0;
            } else {
                $Book->book_price = 0;
            }

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
            $book_url = $request->get('url_book');
            if ($book_url) {
                $url = getEmbeddedUrl($book_url);
                if ($url == '') {
                    Toastr::error('Youtube url is not valid');
                    return redirect()->back()->withInput();
                }
                $Book->file_dir = $url;
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
            if ($Book->status != $request->status) {
                $author_email = $user->email;
                $name = $user->name . ' ' . $user->last_name;
                $data = [
                    'user_email' => $author_email,
                    'template' => 'bookstatusmail',
                    'subject' => 'Your Book Status Change.',
                    'greeting' => 'Hello ' . $name,
                    'body' => 'We would like to infrom you that your book has been ' . getStatusText($request->status),
                    'book' => 'Book Name: ' . $Book->title,
                    'link' => '0',
                    'thanks' => 'Thank you and stay with ' . ' ' . config('app.name'),
                    'site_url' => route('home'),
                    'site_name' => config('app.name'),
                    'copyright' => ' © ' . ' ' . Carbon::now()->format('Y') . config('app.name') . ' ' . 'All rights reserved.',
                    'footer' => '1',
                ];

                // if ($settings->app_mode == 'live') {
                Mail::to($data['user_email'])->send(new Allmail($data));
                // }

                if ($request->status == 10) {
                    $this->bookPublishedNotify($Book, $user);

                }
                if ($request->status == 30) {
                    $author_title = 'Your book with title ' . $Book->title . ' is rejected.';
                    $this->saveUserNotification($author_title, 'author.books.index', 'author_book', 'user', $user->id);
                    $this->sendUserPushNotification([$user->id], 'Book rejected', $author_title, route('admin.book.index'));
                }
                if ($request->status == 40) {
                    $author_title = 'Your book with title ' . $Book->title . ' is expired.';
                    $this->saveUserNotification($author_title, 'author.books.index', 'author_book', 'user', $user->id);
                    $this->sendUserPushNotification([$user->id], 'Book expired', $author_title, route('admin.book.index'));
                }
            }

            $Book->status = $request->status;
            $Book->save();

            if(isset($request->category_id) && !empty($request->category_id))
            {
                ProductCategoryMap::where('product_id', $Book->id)->delete();
                foreach ($request->category_id as $id) {
                    ProductCategoryMap::create([
                        'product_id' => $Book->id,
                        'product_category_id' => $id
                    ]);
                }
            }

        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error(trans('Book not Updated !'), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.book.edit', $request->id);
        }
        DB::commit();
        Toastr::success(trans('Book Updated Successfully !'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->route('admin.book.index');
    }

    public function marcData($id)
    {
        $product = Product::select('marc_data')->findOrFail($id);
        $data = [
            'title' => "MARC Data",
            'marcData' => $product->marc_data,
        ];
        return view('admin.book.marc', $data);
    }

    public function analytic($id)
    {
        $product = Product::findOrFail($id);
        $data['title'] = 'Analytic Book';
        return view('admin.book.analytic', compact('product', 'data'));

    }

    public function analyticStatus(Request $request, $id)
    {

        $status = $request->status;

        $view = ProductView::findOrFail($id);
        if (!$view->total_page) {
            Toastr::error('There are no reading pages in this book.');
            return redirect()->back();
        }
        $page_complete_percent = $request->page_complete_percent;
        if (!empty($page_complete_percent)) {
            $page_total_time = $view->total_time / $view->total_page;
            $page_stay_time = ($page_total_time / 100) * $page_complete_percent;
            ProductPageView::where('product_view_id', $id)->where('page_stay_time', '>' , $page_stay_time)->update(['status' => 1]);
        }
        $view->status = $status;
        if ($status == 1) {
            $view->progress = 100;
        } else {
            $complete_page = ProductPageView::where('product_view_id', $id)->where('status', 1)->count();
            $view->progress = ($complete_page / max($view->total_page, 1)) * 100;
        }
        $view->save();
        Toastr::success('Analytic status updated successfully');
        return redirect()->back();
    }
    public function analyticDetails($id)
    {
        $view = ProductView::findOrFail($id);
        $data['title'] = 'Analytic Details';
        return view('admin.book.analytic_details', compact('view', 'data'));
    }
    public function analyticDelete($id)
    {
        $view = ProductView::findOrFail($id);
        $view->delete();
        Toastr::success('Analytic data deleted successfully');
        return redirect()->back();
    }
    public function pageStatus(Request $request, $id)
    {
        $page = ProductPageView::findOrFail($id);
        $view_id = $page->product_view_id;

        $status = $request->status;
        if ($status == 1) {
            $page->status = 1;
            $page->save();
        } else {
            $page->delete();
        }
        $view = ProductView::findOrFail($view_id);
        $complete_page = ProductPageView::where('product_view_id', $view_id)->where('status', 1)->count();
        $view->progress = ($complete_page / max($view->total_page, 1)) * 100;
        $view->status = $complete_page == $view->total_page ? 1 : 0;
        $view->save();

        Toastr::success('Analytic data deleted successfully');
        return redirect()->back();
    }

    public function borrowedChange(Request $request)
    {
        $id = $request->borrowed_id;
        $days = $request->remaining_day;
        BorrowedBook::where('id', $id)->update([
            'borrowed_nextdate' => now()->addDays($days + 2),
            'borrowed_enddate' => now()->addDays($days),
            'is_valid' => $request->validity,
        ]);

        Toastr::success(trans('Borrowed Updated Successfully !'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->back();
    }
}
