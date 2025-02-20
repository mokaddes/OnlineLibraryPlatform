<?php

namespace App\Http\Controllers\Admin;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Brian2694\Toastr\Facades\Toastr;

class ContactController extends Controller
 {
    public $user;
    protected $contact;

    public function __construct(Contact $contact)
    {
        $this->contact     = $contact;
       $this->middleware(function ($request, $next) {
             $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }



    public function index(Request $request)
    {
       if (is_null($this->user) || !$this->user->can('admin.contact.index')) {
            abort(403, 'Sorry !! You are Unauthorized.');
       }

        $data['title']  = 'Contact';
        $data['rows']   =  Contact::orderBy('id', 'desc')->get();
        return view('admin.contact.index',compact('data'));
   }

   public function view($id)
   {

       if (is_null($this->user) || !$this->user->can('admin.contact.view')) {
           abort(403, 'Sorry !! You are Unauthorized.');
       }

       $data['row']= Contact::find($id);
       $html = view('admin.contact.view', compact('data'))->render();
       return response()->json($html);
   }


   public function delete($id)
   {
       if (is_null($this->user) || !$this->user->can('admin.contact.delete')) {
           abort(403, 'Sorry !! You are Unauthorized.');
       }

    Contact::find($id)->delete();
    Toastr::success('Contact deleted successfully');
    return redirect()->route('admin.contact.index');

   }



}
