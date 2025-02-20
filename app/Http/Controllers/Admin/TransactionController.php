<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use DB;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public $user;

    function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }


    public function index()
    {
        if (is_null($this->user) || !$this->user->can('admin.transaction.index')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $data['title'] = 'Transactions';
        $data['transactions'] = Transaction::latest()->get();
        return view('admin.transaction.index', $data);
    }

    public function edit($id)
    {
        $data['title'] = 'Transactions';
        return view('admin.transaction.edit', compact('data'));
    }

    public function delete($id)
    {
        // if (is_null($this->user) || !$this->user->can('admin.category.delete')) {
        //     abort(403, 'Sorry !! You are Unauthorized.');
        // }

    }
}
