<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketDetail;
use App\Traits\RepoResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    use RepoResponse;

    public function index()
    {
        $ticket = Ticket::where('user_id', Auth::user('api')->id)->get();
        if ($ticket && $ticket->count() > 0) {
            return $this->apiResponse(1, 200, 'Ticket is successfully found.', '', $ticket);
        } else {
            return $this->apiResponse(0, 404, 'Ticket is not found.', '');
        }
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'subject' => 'required',
            'message' => 'required|max:1000',
            'attachment' => 'nullable|max:10240'
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(
                0,
                422,
                'Validation Error.',
                $validator->errors()->first(),
                $validator->errors()->toArray()
            );
        }
        DB::beginTransaction();
        try {

            $ticket = new Ticket();
            $ticket->subject = $request->subject;
            $ticket->created_at = now();
            $ticket->user_id = Auth::user('api')->id;
            $ticket->priority = $request->priority;
            $ticket->status = 1;
            $ticket->save();

            $ticketId = $ticket->pk_no;

            $ticketDetail = new TicketDetail();
            $ticketDetail->ticket_id = $ticketId;
            $ticketDetail->message = $request->message;
            $ticketDetail->from_user_id = Auth::user('api')->id;
            $ticketDetail->created_at = now();

            if ($request->hasFile('attachment')) {
                $attachment = $request->file('attachment');
                $baseName = pathinfo($attachment->getClientOriginalName(), PATHINFO_FILENAME);
                $baseName = Str::lower(str_replace(' ', '-', $baseName));
                $imageExtension = $attachment->getClientOriginalExtension();
                $imageName = "{$baseName}-" . uniqid() . ".$imageExtension";
                $filePath = 'uploads/tickets';
                $attachment->move(public_path($filePath), $imageName);

                $ticketDetail->file_name_uploaded = "$filePath/$imageName";
            }

            $ticketDetail->save();
            DB::commit();
            return $this->apiResponse(1, 200, 'Ticket is successfully created.', '', $ticket);
        } catch (\Exception $e) {
            DB::rollback();
            $errorArray = [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ];
            return $this->apiResponse(0, 422, 'Ticket is not created.', $e->getMessage(), $errorArray);
        }
    }

    public function view($id)
    {

        $ticket = Ticket::with(['details' => function ($q) {
            $q->with(['admin', 'user']);
        }])->where('pk_no', $id)->first();
        if ($ticket) {
            return $this->apiResponse(1, 200, 'Ticket is successfully found.', '', $ticket);
        } else {
            return $this->apiResponse(0, 422, 'Ticket is not found.');
        }
    }

    public function reply(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'message' => 'required|max:1000',
                'ticketid' => 'required|exists:tickets,pk_no'
            ]);

            $ticketdetail = new TicketDetail();
            if ($request->attachment) {
                $attachment = $request->file('attachment');
                $base_name = preg_replace('/\..+$/', '', $attachment->getClientOriginalName());
                $base_name = explode(' ', $base_name);
                $base_name = implode('-', $base_name);
                $base_name = Str::lower($base_name);
                $image_name = $base_name . "-" . uniqid() . "." . $attachment->getClientOriginalExtension();
                $file_path = 'uploads/tickets';
                $attachment->move(public_path($file_path), $image_name);
                $ticketdetail->file_name_uploaded = $file_path . '/' . $image_name;
            }
            $ticketdetail->message = $request->message;
            $ticketdetail->from_user_id = Auth::user('api')->id;
            $ticketdetail->ticket_id = $request->ticketid;
            $ticketdetail->save();
            $ticket = $ticketdetail->ticket;
            $ticket->status = 3;
            $ticket->updated_at = now();
            $ticket->save();
        } catch (\Exception $e) {
            DB::rollback();
            $errorArray = [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ];
            return $this->apiResponse(0, 422, 'Reply is not send.', $e->getMessage(), $errorArray);
        }
        DB::commit();
        return $this->apiResponse(1, 200, 'Your reply is successfully send.', '', $ticketdetail);
    }

    public function delete($id)
    {
        $ticket = Ticket::find($id);
        if ($ticket) {
            $ticket->details()->delete();
            $ticket->delete();
            return $this->apiResponse(1, 200, 'Ticket is deleted.');
        } else {
            return $this->apiResponse(0, 422, 'Ticket is not found.');
        }
    }
}
