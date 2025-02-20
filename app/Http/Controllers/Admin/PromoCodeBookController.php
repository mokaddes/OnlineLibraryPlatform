<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\PromocodeBook;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PromoCodeBookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $promoCode = PromocodeBook::latest()->get();
        $data = [
            'title' => 'Book Promocode',
            'promoCode' => $promoCode
        ];
        return view('admin.book-promo.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|Application|View
     */
    public function create()
    {
        $books = Product::where('status', '10')->get();
        $categories = Category::where('status', 1)->get();
        $data = [
            'title' => 'Create PromoCode for Book',
            'books' => $books,
            'categories' => $categories
        ];
        return view('admin.book-promo.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:8|unique:promocode_books,code',
            'validity' => 'required|integer',
            'user_limit' => 'required|integer',
            'book_ids' => 'required|array',
            'book_ids.*' => 'exists:products,id',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            Toastr::error($validator->errors()->first(), 'Error');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            $promo = new PromocodeBook();
            $this->saveBookPromo($request, $promo);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error('Something went wrong.', 'Error');
            return redirect()->back()->withInput();
        }

        return redirect()->route('admin.book-promo.index');
    }

    /**
     * @param Request $request
     * @param PromocodeBook $promo
     * @return void
     */
    public function saveBookPromo(Request $request, PromocodeBook $promo): void
    {
        $promo->title = $request->title;
        $promo->code = $request->code;
        $promo->validity = $request->validity;
        $promo->valid_date = Carbon::now()->addDays($request->validity);
        $promo->user_limit = $request->user_limit;
        $promo->category_id = $request->category_id;
        $promo->book_ids = $request->book_ids;
        $promo->status = $request->status;
        $promo->save();
        Toastr::success('Promo code book saved successfully', 'Success');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit(int $id)
    {
        $promocodeBook = PromocodeBook::findOrFail($id);
        $books = Product::where('status', '10')->get();
        $categories = Category::where('status', 1)->get();
        $data = [
            'title' => 'Create PromoCode for Book',
            'books' => $books,
            'promocodeBook' => $promocodeBook,
            'categories' => $categories
        ];
        return view('admin.book-promo.create', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $promoCodeBook = PromocodeBook::findOrFail($id);
            $promoCodeBook->borrowed()->update(['is_valid' => 0]);
            $promoCodeBook->used()->delete();
            $promoCodeBook->delete();
            Toastr::success('Promo code book deleted successfully', 'Success');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error('Something went wrong.', 'Error');
        }
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:8|unique:promocode_books,code,' . $id,
            'validity' => 'required|integer',
            'user_limit' => 'required|integer',
            'book_ids' => 'required|array',
            'book_ids.*' => 'exists:products,id',
            'status' => 'required|in:1,0',
        ]);

        if ($validator->fails()) {
            Toastr::error($validator->errors()->first(), 'Error');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            $promo = PromocodeBook::findOrFail($id);
            $this->saveBookPromo($request, $promo);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error('Something went wrong.', 'Error');
        }

        return redirect()->route('admin.book-promo.index');
    }
}
