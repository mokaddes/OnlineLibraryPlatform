<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class UserBookController extends Controller
{
    public function index(){
        $data['title'] = 'User Book';
        return view('admin.user.book.index', compact('data'));
    }

    public function bookDeteils($slug){
        $data['title'] = 'Book Details';
        $data['book'] = Product::where('slug', $slug)->first();
        return view('admin.user.book.book_details', compact('data'));
    }

    public function favouriteBook(){
        $data['title'] = 'Favourite Book';
        return view('admin.user.book.favourite_book', compact('data'));
    }

    public function borrowBook(){
        $data['title'] = 'Favourite Book';
        return view('admin.user.book.borrow_book', compact('data'));
    }
}
