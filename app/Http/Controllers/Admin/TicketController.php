<?php

namespace App\Http\Controllers\Admin;



use Carbon\Carbon;
use App\Models\Ticket;
use App\Models\TicketDetail;
use Illuminate\Support\Str;
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

        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }
    public function index()
    {
        if (is_null($this->user) || !$this->user->can('admin.ticket.index')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $data['title'] = 'Tickets';
        // $data['rows'] = Ticket::oldest('pk_no')
        $data['rows'] = Ticket::latest('pk_no')
            ->withCount(['details as unread_count' => function ($query) {
                $query->where('from_admin', 0)->where('is_read', 0);
            }])
            ->get();
        return view('admin.ticket.index', compact('data'));
    }


    public function reply($id)
    {
        if (is_null($this->user) || !$this->user->can('admin.ticket.reply')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $data['title'] = 'Tickets Reply';
        $data['row'] = Ticket::where('pk_no', $id)->first();
        $data['row']->details()->where('from_admin', '0')->update(['is_read' => "1"]);
        return view('admin.ticket.reply', compact('data'));
    }

    public function store(Request $request)
    {

        if (is_null($this->user) || !$this->user->can('admin.ticket.store')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

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
            $ticketdetail = new TicketDetail();
            $ticketdetail->message                = $request->message;
            $ticketdetail->from_admin             = Auth::id();
            $ticketdetail->admin_id               = Auth::id();
            $ticketdetail->ticket_id              = $request->ticketid;
            if ($request->attachment) {
               $ticketdetail->file_name_uploaded  = $file_path . '/' . $image_name;
               $ticketdetail->attachment_type     = $extension;
            }
            $ticketdetail->save();
            $ticket = $ticketdetail->ticket;
            $ticket->status = 2;
            $ticket->updated_at = now();
            $result = $ticket->save();

            if($result) {
                $ticket = Ticket::find($request->ticketid);
                $email  = $ticket->user->email;
                $name   = $ticket->user->name.' '.$ticket->user->last_name;
                $data   = [
                    'user_email'    => $email,
                    'template'      => 'bookstatusmail',
                    'subject'       => 'Response to Your Support Inquiry from '.config('app.name').'.',
                    'greeting'      => 'Dear '.$name,
                    'body'          => 'Thank you for reaching out to us. We have received your inquiry, and our team is actively addressing the concerns you raised. Response from our admin is linked below',
                    'book'          => '0',
                    'link'          => route('user.ticket.view', $request->ticketid),
                    'msg'           => 'Click here to navigate to the conversation page',
                    'thanks'        => 'Thank you and stay with ' . ' ' . config('app.name'),
                    'site_url'      => route('home'),
                    'site_name'     => config('app.name'),
                    'copyright'     => ' © ' . ' ' . Carbon::now()->format('Y') . config('app.name') . ' ' . 'All rights reserved.',
                    'footer'        => '1',
                ];
                // if ($settings->app_mode == 'live') {
                Mail::to($data['user_email'])->send(new Allmail($data));
                // }
            }


        } catch (\Exception $e) {
            DB::rollback();
            // dd($e);
            Toastr::error(trans('Message is not sent !'), 'Error', ["positionClass" => "toast-top-right"]);
            // return redirect()->route('admin.ticket.index');
            return redirect()->back();
        }
        DB::commit();
        Toastr::success(trans('Message is Created Successfully!'), 'Success', ["positionClass" => "toast-top-right"]);
        // return redirect()->route('admin.ticket.index');
        return redirect()->back();
    }

    public function close($id)
    {
        if (is_null($this->user) || !$this->user->can('admin.ticket.close')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $tickt = Ticket::find($id);
        $tickt->status = 0;
        $tickt->updated_at = now();
        $tickt->save();
        Toastr::success(trans('Message is Closed Successfully!'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->route('admin.ticket.index');
    }

    public function reopen($id)
    {
        if (is_null($this->user) || !$this->user->can('admin.ticket.reopen')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $tickt = Ticket::find($id);
        $tickt->status = 1;
        $tickt->updated_at = now();
        $tickt->save();
        Toastr::success(trans('Message is opned Successfully!'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->route('admin.ticket.index');
    }

    public function delete($id)
    {
        if (is_null($this->user) || !$this->user->can('admin.ticket.delete')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

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
