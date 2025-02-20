<?php

namespace App\Http\Controllers\User;

use App\Models\ProductView;
use Carbon\Carbon;
use App\Models\Book;
use App\Models\User;
use App\Mail\Allmail;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProductCategoryMap;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class BookController extends Controller
{
    public function index()
    {
        if (Auth::check() && intval(Auth::user()->role_id) !== 2) {
            $data = [
                'title' => 'All Books',
                'books' => Product::where('status', 1)->get(),
                'categories' => Category::all(),
            ];
            return view('user.book.reader.index', $data);
        }
        $data['title'] = 'Library';
        $data['books'] = Product::where('user_id', Auth::user()->id)->get();
        return view('user.book.author.index', $data);
    }

    public function myBooks()
    {
        $data['title'] = 'My Books';
        $data['books'] = Product::where('user_id', Auth::user()->id)->latest('id')->get();
        return view('user.book.author.index', $data);
    }

    public function pendingBooks()
    {
        $data['title'] = 'My Books';
        $data['books'] = Product::where('user_id', Auth::user()->id)->where('status', '0')->get();
        return view('user.book.author.pending', $data);
    }

    public function declinedBooks()
    {
        $data['title'] = 'My Books';
        $data['books'] = Product::where('user_id', Auth::user()->id)->where('status', '30')->get();
        return view('user.book.author.declined', $data);
    }

    public function borrowed()
    {
        $data['title'] = 'Library';
        // $data['books'] = Product::where('user_id', Auth::user()->id)->get();
        return view('user.book.reader.borrowed_books', $data);
    }

    public function favourite()
    {
        $data['title'] = 'Library';
        // $data['books'] = Product::where('user_id', Auth::user()->id)->get();
        return view('user.book.reader.favourite_books', $data);
    }

    public function book_readers()
    {
        $user = Auth::user();
        $readers = $user->productViews()->with('user', 'book')->latest('total_view')->get();
        $data = [
            'title' => 'My Readers',
            'readers' => $readers,
            'user' => $user
        ];
        return view('user.book.author.readers', $data);
    }

    public function create()
    {
        if (auth()->user()->role_id != 2) {
            abort(404, 'Page not found');
        }
        $data['title'] = 'Book';
        $data['categories'] = Category::where('status', '1')->get();
        $data['authors'] = User::where('status', '1')->where('role_id', '2')->get();
        $auth_user = Auth::user()->name . ' ' . Auth::user()->last_name;
        return view('user.book.author.create', compact('data', 'auth_user'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role_id != 2) {
            abort(404, 'Page not found');
        }

        DB::beginTransaction();
        $request->validate([
            'title' => 'required',
            'category_id' => 'required',
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
            'author' => 'required',
            'book_for' => 'nullable',
            'book_price' => 'required_if:book_for,sale',

        ], [
            'book_for.required' => 'The book distribution is required',
            'book_price.required_if' => 'The book price is required',
            'pdf_book.required_if' => 'The book file is required',
            'reading_time.required_if' => 'The reading time is required',
            'audio_book.required_if' => 'The book file is required',
            'video_book.required_if' => 'The book file is required',
        ]);
        try {



            $code = Product::count() === 0 ? 1001 : Product::max('code') + 1;
            $author = Auth::user()->name . ' ' . Auth::user()->last_name;
            $marcData = createMARCRecord($request->title, $author, $request->isbn10, $request->publisher, $request->publisher_year, '');

            $Book = new Product();
            $book_url = $request->get('url_book');
            if ($book_url) {
                $url = getEmbeddedUrl($book_url);
                if ($url == '') {
                    Toastr::error('Youtube url is not valid');
                    return redirect()->back()->withInput();
                }
                $Book->file_dir = $url;
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
            $Book->code = $code;
            $Book->title = $request->title;
            $Book->sub_title = $request->sub_title;
            $Book->category_id = $request->category_id[0];
            // $Book->admin_id             = Auth::user()->id;
            $Book->user_id = Auth::user()->id;
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
            $Book->status = 0;
            $Book->is_highlight = 0;
            $Book->is_book_of_month = 0;
            $Book->marc_data = $marcData;
            $Book->reading_time = $request->reading_time ?? 0;
            $Book->book_for = $request->book_for ?? 'library';
            if($request->book_for == 'sale') {
                $Book->book_price = $request->book_price ?? 0;
            } else {
                $Book->book_price = 0;
            }
            $result = $Book->save();

            if(isset($request->category_id) && !empty($request->category_id))
            {
                foreach ($request->category_id as $id) {
                    ProductCategoryMap::create([
                        'product_id' => $Book->id,
                        'product_category_id' => $id
                    ]);
                }
            }

            if ($result) {
                $name = Auth::user()->name . ' ' . Auth::user()->last_name;
                $data = [
                    'admin_email' => getSetting()->support_email,
                    'template' => 'bookstatusmail',
                    'subject' => 'New Book Posted!',
                    'greeting' => 'Hello, Admin,',
                    'body' => 'A new book has been posted from user - ' . $name . '. Please review and respond to the users book as soon as possible.',
                    'book' => 'Book Name: ' . $Book->title,
                    'link' => route('admin.book.index'),
                    'msg' => 'Click here to navigate to the Book Index',
                    'thanks' => 'Thank you and stay with ' . ' ' . config('app.name'),
                    'site_url' => route('home'),
                    'site_name' => config('app.name'),
                    'copyright' => ' © ' . ' ' . Carbon::now()->format('Y') . config('app.name') . ' ' . 'All rights reserved.',
                    'footer' => '0',
                ];
                // if ($settings->app_mode == 'live') {
                Mail::to($data['admin_email'])->send(new Allmail($data));
                // }
            }
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error(trans('Book creation failed !'), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->back()->withInput();
        }
        DB::commit();
        Toastr::success(trans('Book Created Successfully!'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->route('author.books.index');
    }


    public function edit($id)
    {
        if (auth()->user()->role_id != 2) {
            abort(404, 'Page not found');
        }

        $data['title'] = 'Book';
        $data['categories'] = Category::where('status', '1')->get();
        $data['authors'] = User::where('status', '1')->where('role_id', '2')->get();
        $book = Product::find($id);
        $data['row'] = $book;
        return view('user.book.author.edit', compact('data'));
    }

    public function update(Request $request)
    {
        if (auth()->user()->role_id != 2) {
            abort(404, 'Page not found');
        }
        $this->validate($request, [
            'title' => 'required|max:100',
            'category_id' => 'required',
            'author' => 'required',
            'reading_time' => 'required_if:file_type,pdf',
            'book_for' => 'nullable',
            'book_price' => 'required_if:book_for,sale',
        ],[
            'book_for.required' => 'The book distribution is required',
            'book_price.required_if' => 'The book price is required',
            'reading_time.required_if' => 'The reading time is required',
        ]);
        try {
            DB::beginTransaction();



            $author = Auth::user()->name . ' ' . Auth::user()->last_name;
            $marcData = createMARCRecord($request->title, $author, $request->isbn10, $request->publisher, $request->publisher_year, '');

            $Book = Product::find($request->id);
            $book_url = $request->get('url_book');
            if ($book_url) {
                $url = getEmbeddedUrl($book_url);
                if ($url == '') {
                    Toastr::error('Youtube url is not valid');
                    return redirect()->back()->withInput();
                }
                $Book->file_dir = $url;
            }
            $book = $request->file($request->file_type . '_book');
            if ($book) {
                if (File::exists($Book->file_dir)) {
                    File::delete($Book->file_dir);
                }
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
                if (File::exists($Book->thumb)) {
                    File::delete($Book->thumb);
                }
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
            $Book->title = $request->title;
            $Book->sub_title = $request->sub_title;
            $Book->category_id = $request->category_id[0];
            $Book->status = $request->status;
            $Book->user_id = Auth::user()->id;
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
            $Book->status = $request->status;
            $Book->is_highlight = $request->is_highlight;
            $Book->is_book_of_month = $request->is_book_of_month;
            $Book->marc_data = $marcData;
            $Book->reading_time = $request->reading_time ?? 0;
            $Book->book_for = $request->book_for ?? 'library';
            if($request->book_for == 'sale') {
                $Book->book_price = $request->book_price ?? 0;
            } else {
                $Book->book_price = 0;
            }
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
            return redirect()->back();
        }
        DB::commit();
        Toastr::success(trans('Book Updated Successfully !'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->route('author.books.index');
    }

    public function delete($id)
    {
        if (auth()->user()->role_id != 2) {
            abort(404, 'Page not found');
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
        return redirect()->back();
    }

    public function view()
    {
        $data['title'] = 'View Book';
        return view('user.book.author.view', compact('data'));
    }

    public function bookDetails($slug)
    {
        $data['title'] = 'Book Details';
        $data['book'] = Product::where('slug', $slug)->first();
        return view('user.book.reader.book_details', $data);
    }

    public function analytic($id)
    {
        $product = Product::findOrFail($id);
        return view('user.book.author.analytic', compact('product'));

    }

}
