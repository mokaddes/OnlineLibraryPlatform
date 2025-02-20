<?php

namespace App\Http\Controllers\Admin;

use App\Models\CustomPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class CustomPageController extends Controller
{
    public $user;
    protected $page;
    public function __construct(CustomPage $page)
    {
        $this->page     = $page;

        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }


    // public function index(Request $request)
    // {

    //     if (is_null($this->user) || !$this->user->can('admin.cpage.index')) {
    //         abort(403, 'Sorry !! You are Unauthorized.');
    //     }

    //     $data['title'] = 'Custom Page List';
    //     $data['rows'] = CustomPage::get();
    //     return view('admin.custom-page.index', compact('data'));
    // }

    // public function create()
    // {
    //     if (is_null($this->user) || !$this->user->can('admin.cpage.create')) {
    //         abort(403, 'Sorry !! You are Unauthorized.');
    //     }

    //     $data['title'] = 'Custom Page Create';
    //     return view('admin.custom-page.create', compact('data'));
    // }

    // public function store(Request $request)
    // {
    //     if (is_null($this->user) || !$this->user->can('admin.cpage.store')) {
    //         abort(403, 'Sorry !! You are Unauthorized.');
    //     }

    //     $this->resp = $this->page->postStore($request);
    //     if (!$this->resp->status) {
    //         return redirect()->back()->with($this->resp->redirect_class, $this->resp->msg);
    //         Toastr::error(trans($this->resp->msg), 'Error', ["positionClass" => "toast-top-right"]);
    //     }
    //     Toastr::success(trans($this->resp->msg), 'Success', ["positionClass" => "toast-top-right"]);
    //     return redirect()->route($this->resp->redirect_to)->with($this->resp->redirect_class, $this->resp->msg);
    // }


    // public function edit($id)
    // {
    //     if (is_null($this->user) || !$this->user->can('admin.cpage.edit')) {
    //         abort(403, 'Sorry !! You are Unauthorized.');
    //     }

    //     $data['title'] = 'Custom Page edit';
    //     $data['row'] = CustomPage::find($id);
    //     return view('admin.custom-page.edit', compact('data'));
    // }

    // public function update(Request $request, $id)
    // {

    //     $this->resp = $this->page->putUpdate($request, $id);
    //     if (!$this->resp->status) {
    //         return redirect()->back()->with($this->resp->redirect_class, $this->resp->msg);
    //         Toastr::error(trans($this->resp->msg), 'Error', ["positionClass" => "toast-top-right"]);
    //     }
    //     Toastr::success(trans($this->resp->msg), 'Success', ["positionClass" => "toast-top-right"]);
    //     return redirect()->route($this->resp->redirect_to)->with($this->resp->redirect_class, $this->resp->msg);
    // }


    // public function view($id)
    // {
    //     if (is_null($this->user) || !$this->user->can('admin.cpage.view')) {
    //         abort(403, 'Sorry !! You are Unauthorized.');
    //     }

    //     $data['title'] = 'Custom Page View';
    //     $data['row'] = CustomPage::find($id);

    //     return view('admin.custom-page.view', compact('data'));
    // }

    // public function postEditorImageUpload(Request $request)
    // {
    //     if (!is_null($request->file('image'))) {
    //         $image = $request->file('image');
    //         $extension = $image->getClientOriginalExtension();
    //         $file_path = 'assets/uploads/page';
    //         $base_name = preg_replace('/\..+$/', '', $image->getClientOriginalName());
    //         $base_name = explode(' ', $base_name);
    //         $base_name = implode('-', $base_name);
    //         $img = Image::make($image->getRealPath());
    //         $feature_image = $base_name . "-" . uniqid() . '.webp';
    //         Image::make($img)->save($file_path . '/' . $feature_image);
    //         $image_name = $file_path . '/' . $feature_image;
    //         return   url('/') . '/' . $image_name;
    //     }
    // }

    // public function getDelete($id)
    // {
    //     $this->resp = $this->page->getDelete($id);
    //     Toastr::success(trans($this->resp->msg), 'Success', ["positionClass" => "toast-top-right"]);
    //     return redirect()->route($this->resp->redirect_to)->with($this->resp->redirect_class, $this->resp->msg);
    // }

    // public function changeActiveStatus(Request $request)
    // {

    //     $id                     = $request->id;
    //     $article                = Page::findOrFail($id);
    //     $page->is_active        = !$page->is_active;
    //     $page->updated_by   = Auth::user()->PK_NO;
    //     $page->update();
    //     return response()->json($article);
    // }

    public function privacy_policy(Request $request)
    {

        if (is_null($this->user) || !$this->user->can('admin.cpage.privacy_policy')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $data['nav_link'] = 'privacy_policy';
        $data['title'] = 'Privacy Policy';
        $data['url_slug'] = 'privacy-policy';
        $data['row'] = CustomPage::where('url_slug', 'privacy-policy')->first();
        return view('admin.custom-page.custom_page', compact('data'));
    }
    public function terms_conditions(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('admin.cpage.terms_conditions')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $data['nav_link'] = 'terms_conditions';
        $data['title'] = 'Terms & Conditions';
        $data['url_slug'] = 'terms-and-conditions';
        $data['row'] = CustomPage::where('url_slug', 'terms-and-conditions')->first();
        return view('admin.custom-page.custom_page', compact('data'));
    }

    public function storeCustomPage(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('admin.cpage.store')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $validator = Validator($request->all());

        if ($validator->fails()) {
            Toastr::error(trans('Please fill the form'), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            $check_slug = CustomPage::where('url_slug', $request->url_slug)->first();
            if ($check_slug) {
                $check_slug->body          = $request->body;
                $check_slug->is_active     = '1';
                $check_slug->save();
            } else {
                $page                = new CustomPage();
                $page->title         = $request->title;
                $page->url_slug      = $request->url_slug;
                $page->body          = $request->body;
                $page->is_active     = '1';
                $page->save();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error(trans('Page not updated !'), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }
        DB::commit();
        Toastr::success(trans('Page updated Successfully!'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->back();
    }

    public function home(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('admin.cpage.home')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $data['nav_link']       = 'home';
        $data['title']          = 'Home';
        $data['sections']       = DB::table('home_page')->where('id', 1)->first();
        return view('admin.custom-page.home_page', compact('data'));
    }

    public function updateHomePage(Request $request)
    {

        if (is_null($this->user) || !$this->user->can('admin.cpage.home.store')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        DB::beginTransaction();
        try {



            DB::table('home_page')->updateOrInsert(
            ['id' => 1],
            [
                'title' => $request->title,
                'sub_title' => $request->sub_title,
                'button_text1' => $request->button_text1,
                'button_text2' => $request->button_text2,
                'button_link1' => $request->button_link1,
                'button_link2' => $request->button_link2
            ]);
            if ($request->hasFile('book_of_month_image')) {
                // __delete old image
                $old_data = DB::table('home_page')->where('id', 1)->first();
                if ($old_data) {
                    $imagePath = public_path($old_data->book_of_month_image);
                    if (File::exists($imagePath)) {
                        File::delete($imagePath);
                    }
                }

                $image = $request->file('book_of_month_image');
                $base_name = preg_replace('/\..+$/', '', $image->getClientOriginalName());
                $base_name = explode(' ', $base_name);
                $base_name = implode('-', $base_name);
                $base_name = Str::lower($base_name);
                $image_name = $base_name . "-" . uniqid() . "." . $image->getClientOriginalExtension();
                $file_path = 'uploads/category';
                $image->move(public_path($file_path), $image_name);
                $book_of_month_image  =  $file_path . '/' . $image_name;
                DB::table('home_page')->where('id', 1)->updateOrInsert(['id' => 1], ['book_of_month_image' => $book_of_month_image]);
            }
            if ($request->hasFile('image')) {
                // __delete old image
                $old_data = DB::table('home_page')->where('id', 1)->first();
                if ($old_data) {
                    $imagePath = public_path($old_data->image);
                    if (File::exists($imagePath)) {
                        File::delete($imagePath);
                    }
                }

                $image = $request->file('image');
                $base_name = preg_replace('/\..+$/', '', $image->getClientOriginalName());
                $base_name = explode(' ', $base_name);
                $base_name = implode('-', $base_name);
                $base_name = Str::lower($base_name);
                $image_name = $base_name . "-" . uniqid() . "." . $image->getClientOriginalExtension();
                $file_path = 'uploads/category';
                $image->move(public_path($file_path), $image_name);
                $path  =  $file_path . '/' . $image_name;
                DB::table('home_page')->where('id', 1)->updateOrInsert(['id' => 1], ['image' => $path]);
            }



        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
            Toastr::error(trans('Page not updated !'), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }
        DB::commit();
        Toastr::success(trans('Page updated Successfully!'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->back();
    }
}
