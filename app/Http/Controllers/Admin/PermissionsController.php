<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{

        public $user;

        function __construct()
        {
            $this->middleware(function ($request, $next) {
                $this->user = Auth::guard('admin')->user();
                return $next($request);
            });
        }
            /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index()
        {
            if (is_null($this->user) || !$this->user->can('admin.permissions.index')) {
                abort(403, 'Sorry !! You are Unauthorized.');
            }

            $data['title'] = 'Permissions';
            $permissions = Permission::all();
            return view('admin.permissions.index', compact('data', 'permissions'));
        }

        /**
         * Show form for creating permissions
         *
         * @return \Illuminate\Http\Response
         */
        public function create()
        {
            if (is_null($this->user) || !$this->user->can('admin.permissions.create')) {
                abort(403, 'Sorry !! You are Unauthorized.');
            }

            $data['title'] = 'Permissions';
            return view('admin.permissions.create', compact('data'));
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function store(Request $request)
        {
            if (is_null($this->user) || !$this->user->can('admin.permissions.store')) {
                abort(403, 'Sorry !! You are Unauthorized.');
            }

            $request->validate([
                'name' => 'required|unique:users,name'
            ]);

            // Permission::create($request->only('name'));
            Permission::create([
                'name' => $request->name,
                'group_name' => $request->group_name,
            ]);
            Toastr::success(trans('Permission created successfully.'), 'Success', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.permissions.index');
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param  Permission  $post
         * @return \Illuminate\Http\Response
         */
        public function edit($id)
        {
            if (is_null($this->user) || !$this->user->can('admin.permissions.edit')) {
                abort(403, 'Sorry !! You are Unauthorized.');
            }

            $data['title'] = 'Permissions';
            $permission = Permission::find($id);
            return view('admin.permissions.edit', compact('data', 'permission'));
        }

        /**
         * Update the specified resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  Permission  $permission
         * @return \Illuminate\Http\Response
         */
        public function update(Request $request, $id)
        {
            if (is_null($this->user) || !$this->user->can('admin.permissions.update')) {
                abort(403, 'Sorry !! You are Unauthorized.');
            }

            $permission = Permission::find($id);
            $request->validate([
                'name' => 'required|unique:permissions,name,'.$permission->id
            ]);

            // $permission->update($request->only('name'));
            $permission->update([
                'name' => $request->name,
                'group_name' => $request->group_name,
            ]);
            Toastr::success(trans('Permission updated successfully.'), 'Success', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.permissions.index');
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param  \App\Models\Post  $post
         * @return \Illuminate\Http\Response
         */
        public function destroy( $id)
        {
            if (is_null($this->user) || !$this->user->can('admin.permissions.destroy')) {
                abort(403, 'Sorry !! You are Unauthorized.');
            }

            Permission::find($id)->delete();
            Toastr::success(trans('Permission deleted successfully.'), 'Success', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.permissions.index');
        }
}
