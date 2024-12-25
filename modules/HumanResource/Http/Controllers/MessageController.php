<?php

namespace Modules\HumanResource\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Modules\HumanResource\Entities\Message;
use Modules\HumanResource\Entities\Employee;

class MessageController extends Controller
{

    public function __construct() {
        $this->middleware('permission:read_messages', ['only' => ['index', 'sent', 'inbox', 'viewUpdate']]);
        $this->middleware('permission:create_messages', ['only' => ['store']]);
        $this->middleware('permission:update_messages', ['only' => ['update']]);
        $this->middleware('permission:delete_messages', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {

        // Get the authenticated user ID
        $userId = Auth::id();

        // Retrieve messages with the user relationship where the created_by field matches the authenticated user ID
        $dbData = Message::with('user')
                ->where('created_by', $userId)
                ->get();
        $employees = Employee::where('is_active', 1)
        ->whereNotIn('user_id', [$userId])
        ->get();

        return view('humanresource::message.index', compact('dbData', 'employees'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('humanresource::message.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required',
            'subject' => 'required',
            'message' => 'required',
        ]);

        Message::create($validated);

        return redirect()->route('message.index')->with('success', localize('data_saved_successfully'));
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function sent()
    {
        // Get the authenticated user ID
        $userId = Auth::id();

        // Retrieve messages with the user relationship where the created_by field matches the authenticated user ID
        $dbData = Message::with('user')
                ->where('created_by', $userId)
                ->get();

        $employees = Employee::where('is_active', 1)
        ->whereNotIn('user_id', [$userId])
        ->get();

        return view('humanresource::message.sent', compact('dbData' , 'employees'));
    }


    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function inbox()
    {
        // Get the authenticated user ID
        $userId = Auth::id();

        $employees = Employee::where('is_active', 1)
        ->whereIn('user_id', [$userId])
        ->get();

        // user info
        $empInfo = Employee::where('is_active', 1)
        ->where('user_id', $userId)
        ->first();
        if($empInfo){

            // Retrieve messages with the user relationship where the created_by field matches the authenticated user ID
            $dbData = Message::with('user')
                    ->where('receiver_id', $empInfo->id)
                    ->get();

            return view('humanresource::message.inbox', compact('dbData', 'employees'));

        }else{

            return redirect()->route('message.index')->with('fail', localize('must_be_employee_to_access_inbox'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('humanresource::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('humanresource::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Message $message)
    {

        $validated = $request->validate([
            'receiver_id' => 'required',
            'subject' => 'required',
            'message' => 'required',
        ]);

        $message->update($validated);

        return redirect()->route('message.index')->with('update', localize('data_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Message $message)
    {
        $message->delete();
        Toastr::success('Message Deleted successfully :)', 'Success');
        return response()->json(['success' => 'success']);
    }

    /**
     * viewUpdate resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function viewUpdate(Request $request)
    {
        // Update the record using the model
        Message::where('id', $request->input('id'))->update([
            'receiver_status'     => 1,
        ]);

        // Toastr::success('Message Viewed successfully :)', 'Success');
        return response()->json(['success' => 'success']);
    }

}
