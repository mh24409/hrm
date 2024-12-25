<?php

namespace Modules\Accounts\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Accounts\Entities\AccCoa;
use Modules\Accounts\Entities\AccSubtype;
use Modules\Accounts\Entities\AccTransaction;
use Modules\Accounts\Entities\AccVoucher;
use Modules\Accounts\Http\DataTables\PendingVoucherDataTable;

class AccTransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read_voucher_approval')->only('index');
        $this->middleware('permission:create_voucher_approval')->only(['approve', 'reverseVoucher']);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(PendingVoucherDataTable $datatable)
    {
        $accountName = AccCoa::where('is_active', 1)->where('head_level', 4)->select('id', 'account_name')->get();
        $accSubtype = AccSubtype::where('status', 1)->select('id', 'subtype_name')->get();
        return $datatable->render('accounts::transaction.index', compact('accountName', 'accSubtype'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function approve(Request $request)
    {
        DB::beginTransaction();
        try {

            if (empty($request->voucherId)) {
                Toastr::error('No Checkbox is Check for Approved');
                return redirect()->route('transaction.pending');
            } else {
                $getData = AccVoucher::whereIn('id', $request->voucherId)->get();

                $reverseInsert = [];
                $normalInsert = [];
                $current_date_time = Carbon::now()->toDateTimeString();
                foreach ($getData as $key => $vouturevalue) {

                    $normalInsert[$key]['acc_coa_id'] = $vouturevalue->acc_coa_id;
                    $normalInsert[$key]['financial_year_id'] = $vouturevalue->financial_year_id;
                    $normalInsert[$key]['acc_subtype_id'] = $vouturevalue->acc_subtype_id;
                    $normalInsert[$key]['acc_subcode_id'] = $vouturevalue->acc_subcode_id;
                    $normalInsert[$key]['voucher_no'] = $vouturevalue->voucher_no;
                    $normalInsert[$key]['voucher_type_id'] = $vouturevalue->voucher_type;
                    $normalInsert[$key]['reference_no'] = $vouturevalue->reference_no;
                    $normalInsert[$key]['voucher_date'] = $vouturevalue->voucher_date;
                    $normalInsert[$key]['narration'] = $vouturevalue->narration;
                    $normalInsert[$key]['cheque_no'] = $vouturevalue->cheque_no;
                    $normalInsert[$key]['cheque_date'] = $vouturevalue->cheque_date;
                    $normalInsert[$key]['is_honour'] = $vouturevalue->is_honour;
                    $normalInsert[$key]['ledger_comment'] = $vouturevalue->ledger_comment;

                    $normalInsert[$key]['debit'] = $vouturevalue->debit;
                    $normalInsert[$key]['credit'] = $vouturevalue->credit;
                    $normalInsert[$key]['reverse_code'] = $vouturevalue->reverse_code;

                    $normalInsert[$key]['is_approved'] = 1;
                    $normalInsert[$key]['approved_by'] = Auth::id();
                    $normalInsert[$key]['approved_at'] = $current_date_time;

                    $normalInsert[$key]['created_at'] = $current_date_time;
                    $normalInsert[$key]['updated_at'] = $current_date_time;
                    $normalInsert[$key]['uuid'] = Str::uuid();
                    $normalInsert[$key]['created_by'] = Auth::id();

                    $subtype = AccCoa::where('id', $vouturevalue->reverse_code)->whereNotNull('subtype_id')->first();

                    $reverseInsert[$key]['acc_coa_id'] = $vouturevalue->reverse_code;
                    $reverseInsert[$key]['financial_year_id'] = $vouturevalue->financial_year_id;
                    $reverseInsert[$key]['acc_subtype_id'] = $subtype ? $subtype->subtype_id : null;
                    $reverseInsert[$key]['acc_subcode_id'] = $vouturevalue->acc_subcode_id;
                    $reverseInsert[$key]['voucher_no'] = $vouturevalue->voucher_no;
                    $reverseInsert[$key]['voucher_type_id'] = $vouturevalue->voucher_type;
                    $reverseInsert[$key]['reference_no'] = $vouturevalue->reference_no;
                    $reverseInsert[$key]['voucher_date'] = $vouturevalue->voucher_date;
                    $reverseInsert[$key]['narration'] = $vouturevalue->narration;
                    $reverseInsert[$key]['cheque_no'] = $vouturevalue->cheque_no;
                    $reverseInsert[$key]['cheque_date'] = $vouturevalue->cheque_date;
                    $reverseInsert[$key]['is_honour'] = $vouturevalue->is_honour;
                    $reverseInsert[$key]['ledger_comment'] = $vouturevalue->ledger_comment;

                    $reverseInsert[$key]['debit'] = $vouturevalue->credit;
                    $reverseInsert[$key]['credit'] = $vouturevalue->debit;
                    $reverseInsert[$key]['reverse_code'] = $vouturevalue->acc_coa_id;

                    $reverseInsert[$key]['is_approved'] = 1;
                    $reverseInsert[$key]['approved_by'] = Auth::id();
                    $reverseInsert[$key]['approved_at'] = $current_date_time;

                    $reverseInsert[$key]['created_at'] = $current_date_time;
                    $reverseInsert[$key]['updated_at'] = $current_date_time;
                    $reverseInsert[$key]['uuid'] = Str::uuid();
                    $reverseInsert[$key]['created_by'] = Auth::id();
                    $reverseInsert[$key]['auto_create'] = true;
                }


                AccTransaction::insert($normalInsert);
                AccTransaction::insert($reverseInsert);
                $updateData = [
                    'is_approved' => 1,
                    'approved_by' => Auth::id(),
                    'approved_at' => $current_date_time,
                ];
                AccVoucher::whereIn('id', $request->voucherId)->update($updateData);


                DB::commit();
                return redirect()->route('transaction.index')->with('success', localize('voucher_approved_successfully'));
            }
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Something went wrong :(', 'Error');
            return response()->json(['error' => 'error']);
        }
    }

    // Reverse Voucher
    public function reverseVoucher($uuid)
    {

        $voucher = AccVoucher::where('uuid', $uuid)->firstOrFail();
        $vouchers = AccVoucher::where('voucher_no', $voucher->voucher_no)->where('voucher_type', $voucher->voucher_type)->get();

        DB::beginTransaction();
        try {
            DB::commit();

            AccTransaction::where('voucher_no', $voucher->voucher_no)->where('voucher_type_id', $voucher->voucher_type)->forceDelete();

            foreach ($vouchers as $acc_voucher) {
                $acc_voucher->is_approved = false;
                $acc_voucher->approved_by = null;
                $acc_voucher->approved_at = null;
                $acc_voucher->update();
            }

            Toastr::success('Voucher Reversed successfully :)', 'Success');
            return response()->json(['success' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Something went wrong :(', 'Error');
            return response()->json(['error' => 'error']);
        }
    }
}
