<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Support\Str;
use App\Models\TicketDetail;
use Illuminate\Http\Request;
use App\Mail\Allmail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TicketController extends Controller
{
    public $user;
    protected $ticket;
    public function __construct(Ticket $ticket)
    {
        $this->ticket     = $ticket;
    }

    public function index()
    {
        $data['title'] = 'Support Ticket';
        $data['og_title'] = '';
        $data['og_description'] = '';
        $data['og_image'] = '';
        $data['tickets'] = Ticket::where('user_id', Auth::user()->id)
            ->withCount(['details as unread_count' => function ($query) {
                $query->where('from_admin', 1)->where('is_read', 0);
            }])->get();
        return view('user.ticket.index', $data);
    }

    public function create()
    {
        // if (is_null($this->user) || !$this->user->can('admin.ticket.reply')) {
        //     abort(403, 'Sorry !! You are Unauthorized.');
        // }
        $data['title'] = 'Support Ticket';
        return view('user.ticket.create', $data);
    }

    public function store(Request $request)
    {

        $request->validate([
            'subject' => 'required',
            'message' => 'required',
            'file' => 'nullable|max:10240'
        ]);
        DB::beginTransaction();
        try {

            $ticket = new Ticket();
            $ticket->subject = $request->subject;
            $ticket->created_at = now();
            $ticket->user_id = Auth::id();
            $ticket->priority = $request->priority;
            $ticket->status = 1;
            $ticket->save();

            $ticketId = $ticket->pk_no;
            $ticket->ticket_id = $ticketId;
            $ticket->save();

            $ticketDetail               = new TicketDetail();
            $ticketDetail->ticket_id    = $ticketId;
            $ticketDetail->message      = $request->message;
            $ticketDetail->from_admin   = 0;
            $ticketDetail->created_at         = now();

            if ($request->hasFile('attachment')) {
                $attachment         = $request->file('attachment');
                $baseName           = pathinfo($attachment->getClientOriginalName(), PATHINFO_FILENAME);
                $baseName           = Str::lower(str_replace(' ', '-', $baseName));
                $imageExtension     = $attachment->getClientOriginalExtension();
                $imageName          = "{$baseName}-" . uniqid() . ".$imageExtension";
                $extension          = $attachment->getClientOriginalExtension();
                $filePath           = 'uploads/tickets';
                $ticketDetail->attachment_type      = $extension;
                $ticketDetail->file_name_uploaded   = "$filePath/$imageName";
                $attachment->move(public_path($filePath), $imageName);
            }

            $result = $ticketDetail->save();

            if ($result) {
                $data = [
                    'admin_email'   => getSetting()->support_email,
                    'template'      => 'bookstatusmail',
                    'subject'       => 'New Support Inquiry from ' . auth()->user()->name . ' ' . auth()->user()->last_name,
                    'greeting'      => 'Hello, Admin',
                    'body'          => 'A new support inquiry has been received. Please review and respond to the users inquiry as soon as possible.',
                    'book'          => '0',
                    'link'          => route('admin.ticket.reply', $ticketId),
                    'msg'           => 'Click here to navigate to the conversation page',
                    'thanks'        => 'Thank you and stay with ' . ' ' . config('app.name'),
                    'site_url'      => route('home'),
                    'site_name'     => config('app.name'),
                    'copyright'     => ' © ' . ' ' . Carbon::now()->format('Y') . config('app.name') . ' ' . 'All rights reserved.',
                    'footer'        => '0',
                ];
                // if ($settings->app_mode == 'live') {
                Mail::to($data['admin_email'])->send(new Allmail($data));
                // }
            }

            DB::commit();
            Toastr::success(trans('Message is Created Successfully!'), 'Success', ["positionClass" => "toast-top-right"]);
            return redirect()->route('user.ticket.index');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            Toastr::error(trans('Ticket is not Created !'), 'Error', ["positionClass" => "toast-top-right"]);
            return back();
        }
    }

    public function view($id)
    {
        // if (is_null($this->user) || !$this->user->can('admin.ticket.reply')) {
        //     abort(403, 'Sorry !! You are Unauthorized.');
        // }
        $data['title'] = 'Tickets View';
        $data['row'] = Ticket::where('pk_no', $id)->first();
        $data['row']->details()->where('from_admin', '1')->update(['is_read' => "1"]);
        return view('user.ticket.reply', compact('data'));
    }

    public function reply(Request $request)
    {

        // if (is_null($this->user) || !$this->user->can('admin.category.store')) {
        //     abort(403, 'Sorry !! You are Unauthorized.');
        // }

        DB::beginTransaction();
        try {
            $this->validate($request, [
                'message'          => 'required|max:2000',
                'ticketid'         => 'required'
            ]);

            if ($request->attachment) {
                $attachment = $request->file('attachment');
                $base_name  = preg_replace('/\..+$/', '', $attachment->getClientOriginalName());
                $base_name  = explode(' ', $base_name);
                $base_name  = implode('-', $base_name);
                $base_name  = Str::lower($base_name);
                $image_name = $base_name . "-" . uniqid() . "." . $attachment->getClientOriginalExtension();
                $extension  = $attachment->getClientOriginalExtension();
                $file_path  = 'uploads/tickets';
                $attachment->move(public_path($file_path), $image_name);
            }
            $ticketdetail                           = new TicketDetail();
            $ticketdetail->message                  = $request->message;
            $ticketdetail->from_admin               = 0;
            $ticketdetail->from_user_id             = Auth()->id();
            $ticketdetail->ticket_id                = $request->ticketid;
            if ($request->attachment) {
                $ticketdetail->file_name_uploaded    =  $file_path . '/' . $image_name;
                $ticketdetail->attachment_type       =  $extension;
            }
            $ticketdetail->save();
            $ticket = $ticketdetail->ticket;
            $ticket->status = 3;
            $ticket->updated_at = now();
            $result = $ticket->save();

            if ($result) {
                $data = [
                    'admin_email'   => getSetting()->support_email,
                    'template'      => 'bookstatusmail',
                    'subject'       => 'New Support Inquiry from ' . auth()->user()->name . ' ' . auth()->user()->last_name,
                    'greeting'      => 'Hello, Admin',
                    'body'          => 'A new support inquiry has been received. Please review and respond to the users inquiry as soon as possible.',
                    'book'          => '0',
                    'link'          => route('admin.ticket.reply', $request->ticketid),
                    'msg'           => 'Click here to navigate to the conversation page',
                    'thanks'        => 'Thank you and stay with ' . ' ' . config('app.name'),
                    'site_url'      => route('home'),
                    'site_name'     => config('app.name'),
                    'copyright'     => ' © ' . ' ' . Carbon::now()->format('Y') . config('app.name') . ' ' . 'All rights reserved.',
                    'footer'        => '0',
                ];
                // if ($settings->app_mode == 'live') {
                Mail::to($data['admin_email'])->send(new Allmail($data));
                // }
            }
        } catch (\Exception $e) {
            DB::rollback();
            // dd($e);
            Toastr::error(trans('Message is not sent !'), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }
        DB::commit();
        Toastr::success(trans('Message is reated Successfully!'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->back();
    }

    public function delete($id)
    {
        // if (is_null($this->user) || !$this->user->can('admin.category.delete')) {
        //     abort(403, 'Sorry !! You are Unauthorized.');
        // }

        DB::beginTransaction();
        try {
            $ticket = Ticket::find($id);
            $ticket->details()->delete();
            $ticket->delete();
        } catch (\Exception $e) {
            DB::rollback();
            // dd($e);
            Toastr::error(trans('Ticket is not Deleted !'), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.ticket.index');
        }
        DB::commit();
        Toastr::success(trans('Ticket is Deleted Successfully !'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->route('admin.ticket.index');
    }
}
