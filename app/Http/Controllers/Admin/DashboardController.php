<?php

namespace App\Http\Controllers\Admin;

use Artisan;
use App\Models\User;
use App\Models\Product;
use App\Models\Package;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    //

    public function dashboard()
    {
        $data['title'] = 'Dashboard';
        return view('admin.dashboard',compact('data'));
    }
    public function admin_dashboard()
    {
        $data['title'] = 'Dashboard';
        $data['rows'] = Product::latest('id')->get();
        $data['package_count'] = Package::count();
        $data['user_count'] = User::count();
        $data['transaction_count'] = Transaction::count();
        $data['authors'] = User::where('status','1')->where('role_id','2')->get();
        return view('admin.super_admin.admin_dashboard',compact('data'));
    }

    public function cacheClear(){
        // \Artisan::call('php artisan cache:forget spatie.permission.cache');
        Artisan::call('route:clear');
        \Artisan::call('optimize');
        \Artisan::call('optimize:clear');
        \Artisan::call('view:clear');
        \Artisan::call('config:clear');
        \Artisan::call('storage:link');
        \Artisan::call('cache:forget spatie.permission.cache');
        \Artisan::call('config:cache');

        echo 'Done';
        die();
    }

    public function adminProfile()
    {
         return view('admin.profile.index');
    }

}
