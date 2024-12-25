<?php

namespace Modules\HumanResource\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HumanResource\Entities\Notice;

class NoticeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read_notice')->only(['index']);
        $this->middleware('permission:create_notice', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit_notice', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_notice', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $dbData = Notice::all();
        return view('humanresource::notice.index', compact('dbData'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('humanresource::notice.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $path = '';

        $validated = $request->validate([
            'notice_type' => 'required',
            'notice_descriptiion' => 'required',
            'notice_date' => 'required',
            'notice_by' => 'required',
        ]);

        if ($request->hasFile('notice_attachment')) {
            $request_file = $request->file('notice_attachment');
            $filename = time() . rand(10, 1000) . '.' . $request_file->extension();
            $path = $request_file->storeAs('notice', $filename, 'public');
        }

        $validated['notice_attachment'] = $path;

        Notice::create($validated);

        return redirect()->route('notice.index')->with('success', localize('data_save'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('humanresource::notice.edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Notice $notice)
    {
        $path = '';
        $validated = $request->validate([
            'notice_type' => 'required',
            'notice_descriptiion' => 'required',
            'notice_date' => 'required',
            'notice_by' => 'required',
        ]);

        if ($request->hasFile('notice_attachment')) {
            $request_file = $request->file('notice_attachment');
            $filename = time() . rand(10, 1000) . '.' . $request_file->extension();
            $path = $request_file->storeAs('notice', $filename, 'public');

            $validated['notice_attachment'] = $path;
        }

        $notice->update($validated);

        return redirect()->route('notice.index')->with('update', localize('data_update'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Notice $notice)
    {
        $notice->delete();
        Toastr::success('Notice Deleted successfully :)', 'Success');
        return response()->json(['success' => 'success']);
    }
}
