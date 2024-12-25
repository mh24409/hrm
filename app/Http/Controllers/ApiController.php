<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Modules\Setting\Entities\Application;
use App\Models\User;
use App\Models\Appsetting;
use Modules\HumanResource\Entities\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\HumanResource\Entities\Attendance;
use Modules\HumanResource\Entities\Notice;
use Modules\HumanResource\Entities\Loan;
use Modules\HumanResource\Entities\ApplyLeave;
use Modules\HumanResource\Entities\WeekHoliday;
use Modules\HumanResource\Entities\SalaryGenerate;
use Modules\HumanResource\Entities\LeaveType;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    public function index()
    {

        $json['response'] = array(
            'status' => "Ok",
        );

        echo json_encode($json, JSON_UNESCAPED_UNICODE);
    }

    /*
    |--------------------------------------------------
    |
    |LANGUAGE LIST
    |-------------------------------------------------
    */
    public function language()
    {
        $json['response'] = array(
            'login'               => localize('login'),
            'add_attendance'      => localize('add_attendance'),
            'attendance_list'     => localize('attendance_list'),
            'attendance_history'  => localize('attendance_history'),
            'home'                => localize('home'),
            'give_attendance'     => localize('give_attendance'),
            'ledger_history'      => localize('ledger_history'),
            'request_leave'       => localize('request_leave'),
            'my_profile'          => localize('my_profile'),
            'notice_board'        => localize('notice'),
            'notice'              => localize('notices'),
            'salary_statement'    => localize('salary_statement'),
            'profile'             => localize('profile'),
            'working_hour'        => localize('working_hour'),
            'qr_attendance'       => localize('qr_attendance'),
            'loan_amount'         => localize('loan_amount'),
            'leave_remaining'     => localize('leave_remaining'),
            'total_attendance'    => localize('total_attendance'),
            'day_absent'          => localize('day_absent'),
            'day_present'         => localize('day_present'),
            'next'                => localize('next'),
            'previous'            => localize('previous'),
            'network_alert'       => localize('network_alert'),
            'select_date'         => localize('select_date'),
            'from'                => localize('from'),
            'to'                  => localize('to'),
            'search'              => localize('search'),
            'attendance_log'      => localize('attendance_log'),
            'date'                => localize('date'),
            'time'                => localize('times'),
            'in'                  => localize('in'),
            'out'                 => localize('out'),
            'work_hour'           => localize('work_hour'),
            'action'              => localize('action'),
            'load_more'           => localize('load_more'),
            'data_not_found'      => localize('data_not_found'),
            'view'                => localize('view'),
            'worked'              => localize('worked'),
            'wastage'             => localize('wastage'),
            'net_hours'           => localize('net_hour'),
            'sl'                  => localize('sl'),
            'status'              => localize('status'),
            'punch_time'          => localize('punch_time'),
            'loading'             => localize('loading'),
            'wrong_info_alert'    => localize('wrong_info_alert'),
            'from_to_date_alrt'   => localize('from_to_date_alrt'),
            'qr_scan'             => localize('qr_scan'),
            'stop_scan'           => localize('stop_scan'),
            'scan_again'          => localize('scan_again'),
            'confirm_attendance'  => localize('confirm_attendance'),
            'scan_alert'          => localize('scan_alert'),
            'attn_success_mgs'    => localize('attn_success_mgs'),
            'you_r_not_in_office' => localize('you_r_not_in_office'),
            'out_of_range'        => localize('out_of_range'),
            'debit'               => localize('debit'),
            'credit'              => localize('credit'),
            'balance'             => localize('balance'),
            'request_for_leave'   => localize('request_for_leave'),
            'leave_type'          => localize('leave_type'),
            'select_type'         => localize('select_type'),
            'leave_reason'        => localize('leave_reason'),
            'write_reason'        => localize('write_reason'),
            'send_request'        => localize('send_request'),
            'leave_his_status'    => localize('leave_his_status'),
            'amount'              => localize('amount'),
            'name'                => localize('name'),
            'salary_type'         => localize('sal_type'),
            'total_tax'           => localize('total_tax'),
            'basic_salary'        => localize('basic_salary'),
            'total_salary'        => localize('total_salary'),
            'bank_name'           => localize('bank_name'),
            'paid_by'             => localize('paid_by'),
            'employee'            => localize('employee'),
            'no'                  => localize('no'),
            'email'               => localize('email'),
            'phone'               => localize('phone'),
            'employee_id'         => localize('employee_id'),
            'employment_date'     => localize('employment_date'),
            'state'               => localize('state'),
            'company_name'        => localize('company_name'),
            'city'                => localize('city'),
            'zip'                 => localize('zip'),
            'present_address'     => localize('present_address'),
            'parmanent_address'   => localize('parmanent_address'),
            'education'           => localize('education'),
            'university_name'     => localize('university_name'),
            'notice_by'           => localize('notice_by'),
            'notice_date'         => localize('notice_date'),
            'notice_details'      => localize('notice_details'),
            'no_notice_to_show'   => localize('no_notice_to_show'),
            'welcome_msg'         => localize('welcome_msg'),
            'enter_your_email'    => localize('enter_your_email'),
            'enter_your_password' => localize('enter_your_password'),
            'cannot_remember_pass' => localize('cannot_remember_pass'),
            'forgot_password'     => localize('forgot_password'),
            'email_pass_cannot_empt'     => localize('email_pass_cannot_empt'),
            'email_format_was_not_right'     => localize('email_format_was_not_right'),
            'email_or_pass_not_matched'     => localize('email_or_pass_not_matched'),
            'reset_your_password' => localize('reset_your_password'),
            'reset'               => localize('reset'),
            'your_remember_password' => localize('your_remember_password'),
            'back_to_login'       => localize('back_to_login'),
            'email_fild_can_not_empty'     => localize('email_fild_can_not_empty'),
            'email_not_found'     => localize('email_not_found'),
            'successfully_send_email'     => localize('successfully_send_email'),
            'email_is_not_valid'     => localize('email_is_not_valid'),
            'sorry_email_not_sent'     => localize('sorry_email_not_sent'),
            'day_leave'             => localize('day_leave'),
            'search_work_details'             => localize('search_work_details'),
            'request_not_send'             => localize('request_not_send'),
            'leave_request_success'             => localize('leave_request_success'),
            'all_field_are_required'             => localize('all_field_are_required'),
            'plz_select_data_properly'             => localize('plz_select_data_properly'),
            'pending'             => localize('pending'),
            'approved'            => localize('approved'),
            'logout'              => localize('logout'),
            'paid'                => localize('paid'),
            'unpaid'              => localize('unpaid'),
            'salary_details'      => localize('salary_details'),
            'worked_days'        => localize('worked_days'),
        );

        echo json_encode($json, JSON_UNESCAPED_UNICODE);
    }

    /*
    |---------------------------------------------------
    |Web Settings Data 
    |---------------------------------------------------
    */

    public function webSetting()
    {

        $settings = Application::first();

        if (!empty($settings)) {

            $settings->logo = 'storage/' . $settings->logo;
            $settings->favicon = 'storage/' . $settings->favicon;
            $settings->language = 'language';

            $json['response'] = [
                'status'         => "Ok",
                'attendance_url' => route('api.add_attendance'),
                'base_url'       => url('/') . '/',
                'logo_url'       => app_setting()->logo,
                'settings'       => $settings,
            ];
        } else {

            $json['response'] = [
                'status'  => localize('error'),
                'message' => localize('settings_not_found')

            ];
        }

        echo $json_encode = json_encode($json, JSON_UNESCAPED_UNICODE);
    }

    /*-----------------------------------------------------
    |  ADD  ATTENDANCE 
    |
    |---------------------------------------------------
    */
    public function addAttendance(Request $request)
    {

        $ulatitude       = $request->get('latitude');
        $ulongitude      = $request->get('longitude');
        $employee_id     = $request->get('employee_id');
        $time            = $request->get('datetime');
        $userid          = $request->get('user_id');
        $checklatitude = Appsetting::where('latitude', $ulatitude)
            ->where('longitude', $ulongitude)
            ->count();

        $userInfo = User::select('*')->where('id', $userid)->first();
        $user_data = $this->userData($userInfo->email);
        $user_data->firstname = $user_data->first_name;
        $user_data->lastname = $user_data->last_name;

        $settingdata = Appsetting::first();

        $lat1 = $settingdata->latitude;
        $lon1 = $settingdata->longitude;
        $lat2 = $ulatitude;
        $lon2 = $ulongitude;
        $theta = $lon1 - $lon2;

        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles= $dist * 60 * 1.1515;
        $unit = 'K';
        $metre   = ($miles*1.609344)*1000;

        $distance =  number_format($metre,1);

        $attendance_history = [
            'employee_id' => $employee_id,
            'state'  => 1,
            'id'     => 0,
            'time'   => $time,

        ];

        if($settingdata->acceptablerange > $distance){
            
            if(Attendance::create($attendance_history)){
                $json['response'] = [
                    'status'     => 'ok',
                    'range'     => $distance,
                    'message'    => 'Successfully Saved',

                ];

                $icon='';
                $fields3 = array(
                    'to'=> $user_data->token_id,
                    'data'=>array(
                        'title'=>"Attendance",
                        'body'=>"Dear ".$user_data->firstname.' '.$user_data->lastname." Your Attendance Successfully Saved",
                        'image'=>$icon,
                        'media_type'=>"image",
                        "action"=> "2",
                    ),
                    'notification'=>array(
                        'sound'=>"default",
                        'title'=>"Attendance",
                        'body'=>"Dear ".$user_data->firstname.' '.$user_data->lastname." Your Attendance Successfully Saved",
                        'image'=>$icon,
                    )
                );

                $post_data3 = json_encode($fields3);
                $url = "https://fcm.googleapis.com/fcm/send";
                $ch3  = curl_init($url); 
                curl_setopt($ch3, CURLOPT_FAILONERROR, TRUE); 
                curl_setopt($ch3, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch3, CURLOPT_SSL_VERIFYPEER, 0); 
                curl_setopt($ch3, CURLOPT_POSTFIELDS, $post_data3);
                curl_setopt($ch3, CURLOPT_HTTPHEADER, array($settingdata->googleapi_authkey,
                    'Content-Type: application/json')
                );
                $result3 = curl_exec($ch3);
                curl_close($ch3);   
            } 
            else {
                $json['response'] = [
                    'status'     => 'error',
                    'range'      => $distance,
                    'lat'        => $lat1,
                    'dfrange'    => $settingdata->acceptablerange,
                    'message'    =>  localize('please_try_again'),

                ];
            } 
        }else{
                $json['response'] = [
                'status'     => 'error',
                    'range'    => $distance,
                'message'    => localize('out_of_range'),

            ];
        }

        echo json_encode($json,JSON_UNESCAPED_UNICODE);

    }

    /*
    |---------------------------------------------------
    |    Login info
    |---------------------------------------------------
    */

    public function login(Request $request)
    {

        $email = $request->get('email');
        $password =  $request->get('password');
        $token   = $request->get('token_id');

        $userInfo = $this->userData($email);
        if ($userInfo && $userInfo->profile_pic != null) {
            $userInfo->profile_pic = 'storage/' . $userInfo->profile_pic;
        }

        if (empty($email) || empty($password)) {
            $json['response'] = [
                'status'      => localize('error'),
                'type'        => 'required_field',
                'message'     => 'required_field',
                'permission' => 'read'
            ];
        } else {

            $data['user'] = (object) $userData =  [
                'email'      => $email,
                'password'   => $password
            ];

            $user = $this->checkUser($userData);
            $img = $userInfo->profile_pic;

            if ($user) {
                $token_data = array(
                    'token_id' => $token,
                );
                // Find the user by email and update the token data
                User::where('email', $email)->update($token_data);

                $sData = array(
                    'user_id'     => $userInfo->id,
                    'tokendata'   => $token,
                    'password'    => $password,
                    'profile_pic' => (!empty($img) ? url('/') . '/' . $img : ""),
                    'userdata'    => $userInfo,
                );

                $json['response'] = [
                    'status'  => 'ok',
                    'user_data'    => $sData,
                    'message' => localize('successfully_login'),
                ];
            } else {
                $json['response'] = [
                    'status'  => localize('error'),
                    'message' => localize('no_data_found'),

                ];
            }
        }

        echo json_encode($json, JSON_UNESCAPED_UNICODE);
    }

    /*-----------------------------------------------------
    |  CHANGE PASSWORD 
    |
    |---------------------------------------------------
    */

    public function password_recovery(Request $request)
    {

        try {
            // Validate the request data
            $validatedData = $request->validate([
                'email' => 'required|email|max:100',
            ]);

            $userData = array(
                'email' => $request->input('email')
            );

            $user = User::select('*')->where('email', $userData['email'])->first();

            $ptoken = date('ymdhis');

            if ($user) {

                $email = $user->email;

                $precdat = array(
                    'email'      => $email,
                    'password_reset_token' => $ptoken,
                );

                // Find the user by email and update the token data
                User::where('email', $email)->update($precdat);
                $send_email = '';
                if (!empty($email)) {
                    $send_email = $this->setmail($email, $ptoken);
                }

                if ($send_email) {
                    $json['response'] = [
                        'status'     => 'ok',
                        'message'    => localize('check_Your_email'),

                    ];
                } else {
                    $json['response'] = [
                        'status'     => 'error',
                        'message'    => localize('sorry_email_not_sent'),

                    ];
                }
            } else {
                $json['response'] = [
                    'status'     => 'error',
                    'message'    => localize('email_not_found'),

                ];
            }

            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            exit;

            // If validation passes, continue with your logic
        } catch (ValidationException $e) {
            // Validation failed, retrieve the validation errors
            $errors = $e->validator->errors();

            $json['response'] = [
                'status'     => 'error',
                'message'    => 'Email Is Not Valid',

            ];
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    /*-----------------------------------------------------
    |  SEND MAIL TO USER
    |
    |---------------------------------------------------
    */
    public function setmail($email, $ptoken)
    {
        $msg = "Click on this url for change your password :" . url('/') . '/' . 'api/recovery_form/' . $ptoken;

        // use wordwrap() if lines are longer than 70 characters
        return $msg = wordwrap($msg, 100);

        // send email
        mail($email, "Password Recovery", $msg);
    }

    public function recoveryForm($token_id)
    {

        $burl = url('/') . '/';

        $tokeninfo = $this->token_matching($token_id);
        if ($tokeninfo) {

            $token = $token_id;
            $title = localize('recovery_form');

            return view(
                'recovery_form',
                compact(
                    'token',
                    'title',
                )
            );
        } else {
            return redirect()->route('login');
        }
    }

    /*-----------------------------------------------------
    |  RECOVER PASSWORD
    |
    |---------------------------------------------------
    */
    public function recoverySubmit(Request $request, $token_id)
    {

        try {

            // Validate the request data
            $request->validate([
                'password' => 'required|min:8', // New password validation rules
            ]);

            $token = $request->input('token', true);
            $newpassword = $request->input('password', true);

            // Find the user by the provided field (e.g., username)
            $user = User::where('password_reset_token', $token)->first();
            if ($user) {

                // Update the user's password
                $user->password = Hash::make($request->password);
                $user->save();

                // Redirect the user or return a response
                return redirect()->route('login')->with('success', 'Password updated successfully');
            } else {
                return redirect()->route('recovery_form', $token_id)->with('fail', 'User not found');
            }
        } catch (ValidationException $e) {
            // Validation failed, retrieve the validation errors
            $errors = $e->validator->errors();

            return redirect()->route('recovery_form', $token_id)->with('fail', 'Password must be at last 8 characters');
        }
    }

    public function token_matching($token_id)
    {
        return User::select('*')->where('password_reset_token', $token_id)->first();
    }


    /*-----------------------------------------------------
    |   ATTENDANCE HISTORY FOR ALL ATTENDANCE DATA
    |
    |------------------------------------------------
    */

    public function attendanceHistory(Request $request)
    {

        $start = $request->get('start');
        $employee_id = $request->get('employee_id');

        $total_attn = $this->count_att_history($employee_id);
        if (empty($start)) {
            $attendance = $this->attendance_history($employee_id);  
        } else {
            $attendance = $this->attendance_historylimit($employee_id,$start);
        }
        
        $add_data = [];
        foreach($attendance as $myattendance){

            $dt = Carbon::parse($myattendance['time']);
            $date = $dt->format('Y-m-d');
            $date_y = $dt->year;
            $date_m = $dt->month;
            $date_d = $dt->day;

            $add_data[] =   DB::table('attendances as a')
            ->select('a.*', DB::raw('CONCAT_WS(" ", b.first_name, b.last_name) AS employee_name'), DB::raw('DATE(time) as date'), DB::raw('TIME(time) as time'),
                DB::raw('(SELECT TIMEDIFF(MAX(time), MIN(time)) FROM attendances WHERE employee_id = "' . $employee_id . '" AND DATE(time) = "' . $date . '") as totalhours'))
            ->leftJoin('employees as b', 'b.id', '=', 'a.employee_id')
            ->where('a.employee_id', $employee_id)
            ->whereRaw("YEAR(a.time) = ?", [$date_y])
            ->whereRaw("MONTH(a.time) = ?", [$date_m])
            ->whereRaw("DAY(a.time) = ?", [$date_d])
            ->orderBy('a.id', 'ASC')
            ->groupBy('a.id')
            ->get();
        }

        // dd($add_data);
        
        if(!empty($attendance)){
            $json['response'] = [
                'status'      => 'ok',
                'length'      => "$total_attn",
                'historydata' => $add_data,
                'message'     => localize('found_some_data'),

            ];

        }else{
            $json['response'] = [
                'status'     => 'error',
                'length'      => "",
                'historydata' => [],
                'message'    => localize('no_record_found'),

            ]; 
        }
        echo json_encode($json,JSON_UNESCAPED_UNICODE);
    }

    public function attendance_history($employeeId)
    {

        return DB::table('attendances')
            ->select(DB::raw('*, DATE(time) as mydate, TIMEDIFF(MAX(time), MIN(time)) as totalhours'))
            ->where('employee_id', $employeeId)
            ->groupBy('mydate')
            ->orderByDesc('time')
            ->get()
            ->toArray();
    }

    public function count_att_history($employeeId){

        return Attendance::selectRaw('*, DATE(time) as mydate, TIMEDIFF(MAX(time), MIN(time)) as totalhours')
            ->where('employee_id', $employeeId)
            ->groupBy(DB::raw('mydate'))
            ->orderBy('time', 'desc')
            ->get()
            ->count();
     }

    public function attendance_historylimit($employeeId, $limit)
    {

        return Attendance::selectRaw('*, DATE(time) as mydate, TIMEDIFF(MAX(time), MIN(time)) as totalhours')
            ->where('employee_id', $employeeId)
            ->groupBy(DB::raw('mydate'))
            ->orderBy('time', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /*-----------------------------------------------------
    |  DATE WISE ATTENDANCE
    |
    |------------------------------------------------
    */

    public function attendanceDatewise(Request $request){
        
        $employee_id = $request->get('employee_id');
        $fromdate = $request->get('from_date');
        $todate = $request->get('to_date');
        $attendance = $this->attendance_history_datewise($employee_id,$fromdate,$todate);


        if(!empty($attendance)){
            $json['response'] = [
                'status'      => 'ok',
                'historydata' =>  $attendance,
                'message'     => localize('found_some_data'),
                
            ];
            
        }else{
            $json['response'] = [
                'status'     => 'error',
                'message'    => localize('no_record_found'),
                
            ]; 
        }

        echo json_encode($json,JSON_UNESCAPED_UNICODE);
        
    }

    /*-----------------------------------------------------
    | Total Hours of current month
    |
    |------------------------------------------------
    */

    public function currentMonthTotalHours(Request $request){
    
    
        $query_date = date('Y-m-d');
        $employee_id = $request->get('employee_id');
        $fromdate = date('Y-m-01', strtotime($query_date));
        $todate = date('Y-m-t', strtotime($query_date));
        $allhours = $this->attendance_history_datewise($employee_id,$fromdate,$todate);

        $totalhour=[];
        $idx=1;
        
        foreach($allhours as $hours){

            $hou = 0;
            $min = 0;
            $sec = 0;
        
            $split = explode(":", @$hours['nethours']); 
                        $hou += @$split[0];
                        $min += @$split[1];
                        $sec += @$split[2];

                $seconds = $sec % 60;
                $minutes = $sec / 60;
                $minutes = (integer)$minutes;
                $minutes += $min;
                $hours = $minutes / 60;
                $minutes = $minutes % 60;
                $hours = (integer)$hours;
                $hours += $hou % 24;
        
            $totalnethours = $hours.":".$minutes.":".$seconds;
                $totalhour[$idx] = $totalnethours;
                
                $idx++;
        }
    
        $seconds = 0;
        foreach($totalhour as $t)
        {
            $timeArr = array_reverse(explode(":", $t));
            foreach ($timeArr as $key => $value)
            {
                if ($key > 2) break;
                $seconds += pow(60, $key) * $value;
            }
        }

        $hours = floor($seconds / 3600);
        $mins = floor(($seconds - ($hours*3600)) / 60);
        $secs = floor($seconds % 60);

        $ntotalhours  =  $hours.':'.$mins.':'.$secs;
        
        if(!empty($allhours)){
            $json['response'] = [
                'status'      => 'ok',
                'totalhours'  =>  $ntotalhours,
                'message'     => localize('found_some_data'),
            
            ];
            
        }else{
            $json['response'] = [
                'status'     => 'error',
                'message'    => localize('no_record_found'),
            
            ]; 
        }

        echo json_encode($json,JSON_UNESCAPED_UNICODE);
        
    }

    /*-----------------------------------------------------
    |  NOTICE BOARD
    |
    |------------------------------------------------
    */

    public function noticeInfo(Request $request){
        
        $start=$request->get('start');
        $totlanotice = $this->count_notice();
        if(empty($start)){
            $notice = $this->notice_boardall();
            
        }else{
            $notice = $this->notice_board($start);
        }

        foreach($notice as $key => $notice_r){
            $notice[$key]['notice_attachment'] = 'storage/' . $notice_r['notice_attachment'];
        }

        if(!empty($notice)){
            $json['response'] = [
                'status'      => 'ok',
                'length'      => $totlanotice,
                'historydata' =>  $notice,
                'message'     => localize('found_some_data'),
            
            ];
            
        }else{
            $json['response'] = [
                'status'     => 'error',
                'message'    =>  localize('no_record_found'),
            
            ]; 
        }
        echo json_encode($json,JSON_UNESCAPED_UNICODE);
        
    }

    /*-----------------------------------------------------
    | Loan Amount Remaining info
    |
    |------------------------------------------------
    */

    public function loanAmount(Request $request){
        
        
        $employee_id = $request->get('employee_id');
        $totaldue = $this->total_loan_amount($employee_id);
        
        if(!empty($totaldue)){

            $json['response'] = [
                'status'      => 'ok',
                'totalamount' =>  "$totaldue",
                'message'     => localize('found_some_data'),
                
            ];
            
        }else{
            $json['response'] = [
                'status'     => 'error',
                'totalamount' =>  "",
                'message'     => localize('no_record_found'),
                
            ]; 
        }
        echo json_encode($json,JSON_UNESCAPED_UNICODE);
        
    }

    public function total_loan_amount($id){

        $totalpayble = null;

        // Raw SQL query with a binding
        $loanreceive = "SELECT *, SUM(repayment_amount) as totalreceive FROM `loans` WHERE `employee_id` = ? AND (`released_amount` IS NULL OR `repayment_amount` > COALESCE(`released_amount`, 0))";

        // Execute the query with the binding
        $totalpayble = DB::select($loanreceive, [$id]);

        // Check if the result is not empty and get the first row
        if (!empty($totalpayble)) {
            $totalpayble = $totalpayble[0];
        }

        $due = (!empty($totalpayble)?$totalpayble->repayment_amount:0) - (!empty($totalpayble)?$totalpayble->released_amount:0);
        return $due;

    }

    /*-----------------------------------------------------
    | Dashboard Graph info
    |
    |------------------------------------------------
    */

    public function graphInfo(Request $request){
        
        $query_date = date('Y-m-d');
        $employee_id = $request->get('employee_id');
        $fromdate = date('Y-m-01', strtotime($query_date));
        $todate = date('Y-m-d', strtotime($query_date));

        $alldays = $this->attendance_totalday_currentmonth($employee_id,$fromdate,$todate);
        $takenleave = $this->takenleave($employee_id);
        $weekend    = $this->weekends();
        $totaldaycurrentdate = $this->totaldayofcurrentstage();
        
        $absentdays = $totaldaycurrentdate - ($alldays + (!empty($takenleave)?$takenleave:0) + (!empty($weekend)?$weekend:0));
        if($absentdays > 0){
            $absentdays = $absentdays; 
        }else{
            $absentdays = '';
        }
        
        if(!empty($alldays)){
            $json['response'] = [
                'status'         => 'ok',
                'totalpresent'   => "$alldays",
                'takenleave'     => (!empty($takenleave)?$takenleave:''),
                'weekendholiday' => "$weekend",
                'Monthname'      => date('F'),
                'date'           => date('Y-m-d'),
                'absent'         => "$absentdays",
                'message'        => localize('found_some_data'),
                
            ];
            
        }else{
            $json['response'] = [
                'status'          => 'ok',
                'totalpresent'   => '',
                'takenleave'     => '',
                'weekendholiday' => '',
                'Monthname'      => date('F'),
                'date'           => date('Y-m-d'),
                'absent'         => '',
                
            ]; 
        }

        echo json_encode($json,JSON_UNESCAPED_UNICODE);
        
    }


    /*-----------------------------------------------------
    | Salary info
    |
    |-----------------------------------------------------
    */

    public function salaryInfo(Request $request){
        
        $start=$request->get('start');    
        $employee_id = $request->get('employee_id');

        $total = $this->count_payroll_salaryinfo($employee_id);
        
        if(empty($start)){
            $salaryinfo = $this->payroll_salaryinfo($employee_id);

        }else{
            $salaryinfo = $this->payroll_salaryinfolimit($employee_id,$start);    
        }
      
        if(!empty($salaryinfo)){
            $json['response'] = [
                    'status'        => 'ok',
                    'lenght'        => "$total",
                    'salary_info'       => $salaryinfo,
                    'message'           => localize('found_some_data'),
                           
                ];
           
        }else{
            $json['response'] = [
                    'status'                 => 'error',
                    'lenght'                 => "",
                    'salary_info'            => [],
                    'message'                =>  localize('no_record_found'),
                           
                ]; 
        }

        echo json_encode($json,JSON_UNESCAPED_UNICODE);
        
    }

    /*
    -------------------------------------------------------------------
    |
    |LEAVE TYPE LIST
    |
    --------------------------------------------------------------------
    */
    public function leaveTypeList(){
        $typelist = $this->type_list();

        foreach($typelist as $key => $value){
            $typelist[$key]['leave_type_id'] = $value['id'];
        }
        
        if(!empty($typelist)){
            $json['response'] = [
                        'status'        => 'ok',
                        'type_list'     => $typelist,
                        'message'       => localize('no_record_found'),
                    
                    ];
        }else{
                $json['response'] = [
                        'status'     => 'error',
                        'message'    => localize('found_some_data'),
                    
                    ]; 
        }
        
        echo json_encode($json,JSON_UNESCAPED_UNICODE);
    }

    /*-----------------------------------------------------
    | LEAVE APPLICATION 
    |
    |------------------------------------------------
    */

    public function leaveApplication(Request $request){

        $employee_id = $request->get('employee_id'); 
        $from_date   = $request->get('from_date');
        $to_date     = $request->get('to_date');

        // Create Carbon instances for the start and end dates
        $startdate = Carbon::createFromFormat('Y-m-d', $from_date);
        $enddate = Carbon::createFromFormat('Y-m-d', $to_date);

        $employee_info = Employee::where('id', $employee_id)->first();

        // Calculate the difference in days
        $apply_day = $startdate->diffInDays($enddate);

        $leave_type  = $request->get('type_id',true);
        $reason      = $request->get('reason',true);

        $lv = ApplyLeave::where('employee_id', $employee_id)
            ->where('leave_type_id', $leave_type)
            ->sum('total_approved_day');

        // To get the result as an object similar to `row()`
        $employee_leave = (object) ['lv' => $lv];

        $userid  = $employee_info->user_id;
        $userInfo = User::select('*')->where('id', $userid)->first();
        $user_data = $this->userData($userInfo->email);
        $user_data->firstname = $user_data->first_name;
        $user_data->lastname = $user_data->last_name;
        
        $settingdata = Appsetting::first();
        
        $totalleave = LeaveType::select('leave_days')
            ->where('id', $leave_type)
            ->first();
        if($employee_leave->lv < $totalleave->leave_days){
        
            $data = array(
                'uuid'                   => (string) Str::uuid(),
                'employee_id'            => $employee_id,
                'leave_type_id'          => $leave_type,
                'leave_apply_start_date' => $from_date,
                'leave_apply_end_date'   => $to_date,
                'total_apply_day'        => $apply_day+1,
                'reason'                 => $reason,
                'leave_apply_date'       => date('Y-m-d'),
            );
        
            if($resp = $this->insert_leave_application($data)){

                $json['response'] = [
                    'status'        => 'ok',
                    'message'       => 'Successfully Saved',
                    
                ];
    
                $icon='';
                $fields3 = array(
                    'to'=> $user_data->token_id,
                    'data'=>array(
                        'title'=>"Leave Application",
                        'body'=>"Dear ".$user_data->firstname.' '.$user_data->lastname." Your Leave Request Successfull",
                        'image'=>$icon,
                        'media_type'=>"image",
                        "action"=> "3",
                    ),
                    'notification'=>array(
                        'sound'=>"default",
                        'title'=>"Leave Application",
                        'body'=>"Dear ".$user_data->firstname.' '.$user_data->lastname." Your Leave Request Successfull",
                        'image'=>$icon,
                    )
                );
                $post_data3 = json_encode($fields3);
                $url = "https://fcm.googleapis.com/fcm/send";
                $ch3  = curl_init($url); 
                curl_setopt($ch3, CURLOPT_FAILONERROR, TRUE); 
                curl_setopt($ch3, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch3, CURLOPT_SSL_VERIFYPEER, 0); 
                curl_setopt($ch3, CURLOPT_POSTFIELDS, $post_data3);
                curl_setopt($ch3, CURLOPT_HTTPHEADER, array($settingdata->googleapi_authkey,
                    'Content-Type: application/json')
                );
                $result3 = curl_exec($ch3);
                curl_close($ch3);             
                        
                
            }else{
                $json['response'] = [
                    'status'     => 'error',
                    'message'    => 'Please Try Again !',
                    
                ]; 
            }
        }else{
            $json['response'] = [
                'status'     => 'error',
                'message'    => 'You Already Enjoyed All leaves',
                
            ];    
        }
        echo json_encode($json,JSON_UNESCAPED_UNICODE);
        
    }

    /*
    -------------------------------------------------------------------
    |
    |LEAVE  LIST
    |
    --------------------------------------------------------------------
    */
    public function leaveList(Request $request){

        $start=$request->get('start');
        $employee_id = $request->get('employee_id');

        $countdata =  $this->count_leave($employee_id);

        if(empty($start)){
            $leavelist = $this->leave_list($employee_id);
        }else{
            $leavelist = $this->leave_listlimit($employee_id,$start);   
        }
        
        if(!empty($leavelist)){
            $json['response'] = [
                'status'        => 'ok',
                'type_list'     => $leavelist,
                'length'        => $countdata,
                'message'       => localize('found_some_data'),
            
            ];
        }else{
            $json['response'] = [
                'status'     => 'error',
                'message'    => localize('no_record_found'),
            
            ]; 
        }
        
        echo json_encode($json,JSON_UNESCAPED_UNICODE);
    }

     /*
    -------------------------------------------------------------------
    |
    |Ledger ******** As of now accounts module not available , so ledger data will be null
    |
    --------------------------------------------------------------------
    */

    public function ledger(){

        $json['response'] = [
            'status'     => 'error',
            'length'        => "",
            'type_list'     => [],
            'message'    =>  localize('no_record_found'),
        
        ]; 
        
        echo json_encode($json,JSON_UNESCAPED_UNICODE);
    }

    /*-----------------------------------------------------
    | Leave Remaining info
    |
    |------------------------------------------------
    */

    public function leaveRemaining(Request $request){
        
        
        $employee_id = $request->get('employee_id');
        $totalremaining = $this->get_leave_remaining($employee_id);
        
        if(!empty($totalremaining)){
            $json['response'] = [
                'status'      => 'ok',
                'total'        =>  "$totalremaining",
                'message'     => localize('found_some_data'),
                
            ];
            
        }else{
            $json['response'] = [
                'status'     => 'error',
                'total'        => "",
                'message'    => localize('no_record_found'),
                
            ]; 
        }
        echo json_encode($json,JSON_UNESCAPED_UNICODE);
        
    }

    /*-----------------------------------------------------
    | Total Working Day of current month
    |
    |------------------------------------------------
    */

    public function currentMonthTotalday(Request $request){
        
        
        $query_date = date('Y-m-d');
        $employee_id = $request->get('employee_id');
        $fromdate = date('Y-m-01', strtotime($query_date));
        $todate = date('Y-m-t', strtotime($query_date));

        $alldays = $this->attendance_totalday_currentmonth($employee_id,$fromdate,$todate);
    
        if(!empty($alldays)){
            $json['response'] = [
                'status'      => 'ok',
                'totalday'    =>  "$alldays",
                'message'     => localize('no_record_found'),
                
            ];
            
        }else{
            $json['response'] = [
                'status'     => 'error',
                'totalday' =>  "",
                'message'    => localize('no_record_found'),
                
            ]; 
        }

        echo json_encode($json,JSON_UNESCAPED_UNICODE);
        
    }

    public function get_leave_remaining($id){

        $totalleave = $totalleave = LeaveType::select(DB::raw('*, sum(leave_days) as totalleave'))->first();
        $totaltaken = ApplyLeave::select(DB::raw('*, sum(total_approved_day) as takenlv'))
        ->where('employee_id', $id)
        ->first();
        
        $remainingleave = (!empty($totalleave)?$totalleave->totalleave:0) - (!empty($totaltaken)?$totaltaken->takenlv:0);
        return $remainingleave;

    }

    public function leave_listlimit($employee_id,$limit){

        return ApplyLeave::select('leave_apply_start_date as fromdate', 'leave_apply_end_date as todate', 'total_apply_day as apply_day', 'reason', 'is_approved as status')
        ->where('employee_id', $employee_id)
        ->where('deleted_at', Null)
        ->orderBy('id', 'desc')
        ->limit($limit)
        ->get()
        ->toArray();
   }

    public function leave_list($employee_id){

        return ApplyLeave::select('leave_apply_start_date as fromdate', 'leave_apply_end_date as todate', 'total_apply_day as apply_day', 'reason', 'is_approved as status')
            ->where('employee_id', $employee_id)
            ->where('deleted_at', Null)
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();

    }

    public function count_leave($employee_id){

        $leaveAppliesCount = ApplyLeave::where('employee_id', $employee_id)->count();
        if ($leaveAppliesCount > 0) {
            return $leaveAppliesCount;
        }
        
        return false;

    }

    public function insert_leave_application($data = array())
    {
        return ApplyLeave::create($data);
    }

    public function type_list(){

        return LeaveType::all()->toArray();
    }

    public function payroll_salaryinfolimit($id,$limit){

        $data = DB::table('salary_generates as gs')
        ->select('gs.*', 
                DB::raw("CONCAT_WS(' ', b.first_name, b.last_name) AS employee_name"), 
                DB::raw("'2' AS salarytype"), 
                'gss.is_approved as approved')
        ->leftJoin('employees as b', 'b.id', '=', 'gs.employee_id')
        ->leftJoin('salary_sheet_generates as gss', 'gss.name', '=', 'gs.salary_month_year')
        ->where('gs.employee_id', $id)
        ->where('gs.deleted_at',NULL)
        ->groupBy('gs.id')
        ->limit($limit)
        ->get()
        ->toArray();


        return $data;
          
      }

    public function payroll_salaryinfo($id){

        $data = DB::table('salary_generates as gs')
        ->select('gs.*',DB::raw("CONCAT_WS(' ', b.first_name, b.last_name) AS employee_name"),'gss.is_approved as approved', DB::raw("'2' as salarytype"))
        ->leftJoin('employees as b', 'b.id', '=', 'gs.employee_id')
        ->leftJoin('salary_sheet_generates as gss', 'gss.name', '=', 'gs.salary_month_year')
        ->where('gs.employee_id', $id)
        ->where('gs.deleted_at',NULL)
        ->groupBy('gs.id')
        ->get()
        ->toArray();

        return $data;
       
    }

    public function count_payroll_salaryinfo($id){

        $count = SalaryGenerate::where('employee_id', $id)->count();

        return $count;

    }

    public function totaldayofcurrentstage(){

        $query_date = date('Y-m-d');
        $fromdate = date('Y-m-01', strtotime($query_date));
        $todate = date('Y-m-d');

        $begin = Carbon::createFromFormat('Y-m-d', $fromdate);
        $end = Carbon::createFromFormat('Y-m-d', $todate)->addDay(); // Add one day to include the end date

        $daterange = $begin->toPeriod($end, '1 day');

        $result = 0;
        foreach ($daterange as $date) {
            $result += 1;
        }

        return $result;

    }

    public function weekends(){

        $query_date = date('Y-m-d');
        $fromdate = date('Y-m-01', strtotime($query_date));
        $todate = date('Y-m-d');  
                
        $wknd = WeekHoliday::first();

        $holidays = $wknd->dayname;
        
        $weeklyholiday = array();
        $weeklyholiday = array_map('trim', explode(',', $holidays));
        $existdata = 0;
        
        if (sizeof($weeklyholiday) > 0) {
            foreach($weeklyholiday as $days){

                $begin = Carbon::createFromFormat('Y-m-d', $fromdate);
                $end = Carbon::createFromFormat('Y-m-d', $todate)->addDay(); // Add one day to include the end date

                $daterange = $begin->toPeriod($end, '1 day');

                foreach ($daterange as $date) {
                    $dates = strtolower($date->format('l'));
                    // return $dates;
                    if ($days == $dates) {
                        $existdata += 1;
                    } else {
                        $existdata += 0;
                    }
                }
                
            }
        }
        
        return $existdata;        
                
    }

    public function takenleave($id){

        $totalTaken = ApplyLeave::selectRaw('*, sum(total_approved_day) as takenlv')
        ->where('employee_id', $id)
        ->first();

        $takenLv = !empty($totalTaken) ? $totalTaken->takenlv : 0;

        return $takenLv;

    }

    public function attendance_totalday_currentmonth($id,$from_date,$to_date){

        return Attendance::select('*', DB::raw('DATE(time) as mydate'))
        ->where('employee_id', $id)
        ->whereDate('time', '>=', $from_date)
        ->whereDate('time', '<=', $to_date)
        ->groupBy('mydate')
        ->orderByDesc('time')
        ->get()->count();
    }

    public function notice_board($start){
        // Fetch all notices ordered by notice_id in descending order
        $notices = Notice::limit($start)->orderBy('id', 'desc')->get()->toArray();

        return $notices;
    }

    public function notice_boardall(){
        // Fetch all notices ordered by notice_id in descending order
        $notices = Notice::orderBy('id', 'desc')->get()->toArray();

        return $notices;
    }

    public function count_notice(){
       return Notice::count();
    }

    public function attendance_history_datewise($id,$from_date,$to_date){

        $from_date = Carbon::parse($from_date)->format('Y-m-d');
        $to_date = Carbon::parse($to_date)->format('Y-m-d');

        $query = Attendance::select('*', DB::raw('DATE(time) as mydate'))
        ->where('employee_id', $id)
        ->whereBetween(DB::raw('DATE(time)'), [$from_date, $to_date])
        ->groupBy('mydate')
        ->orderByDesc('time')
        ->get();

        $attendance = [];
        $i=1;
        foreach ($query as $att) {

            $attendance[] = Attendance::selectRaw('MIN(time) as intime, MAX(time) as outtime, employee_id as uid, time, TIMEDIFF(MAX(time), MIN(time)) as totalhours, DATE(time) as date, TIME(time) as punchtime')
            ->where('employee_id', $att->employee_id)
            ->where('time', 'LIKE', date('Y-m-d', strtotime($att->mydate)) . '%')
            ->orderBy('time', 'DESC')
            ->get()
            ->toArray();

            $i = 1;
            foreach($attendance as $k => $v){

                $resp = $this->employee_worked_hour_by_date($attendance[$k][0]['uid'], date('Y-m-d', strtotime($attendance[$k][0]['time'])));
                
                $attendance[$k]['totalhours'] = $attendance[$k][0]['totalhours'];
                $attendance[$k]['date']       = $attendance[$k][0]['date'];


                $attendance[$k]['wastage'] =   $resp['totalwasthour'];
                $attendance[$k]['nethours'] = $resp['totalnetworkhour'];

                        
            }

            $i++;
        }
        return $attendance;
       
   }

    /**
     * Calculating totalNetworkHours for an employee current_day
     */
    public function employee_worked_hour_by_date($employee_id, $mydate)
    {

        $totalhour = 0;
        $totalwasthour = 0;
        $totalnetworkhour = 0;

        $attenddata = DB::table('attendances as a')
            ->select('a.time', DB::raw('MIN(a.time) as intime'), DB::raw('MAX(a.time) as outtime'), 'a.employee_id as uid')
            ->where('a.time', 'LIKE', '%' . date("Y-m-d", strtotime($mydate)) . '%')
            ->where('a.employee_id', $employee_id)
            ->whereNull('a.deleted_at')
            ->get();

        // Getting totalWorkHours
        $date_a = Carbon::createFromFormat('Y-m-d H:i:s', $attenddata[0]->outtime);
        $date_b = Carbon::createFromFormat('Y-m-d H:i:s', $attenddata[0]->intime);
        $interval = $date_a->diff($date_b);

        $totalwhour = $interval->format('%h:%i:%s');

        // End of Getting totalWorkHours

        $att_dates = date("Y-m-d", strtotime($attenddata[0]->time));
        // Convert the given date to a Carbon instance
        $exist_date = Carbon::createFromFormat('Y-m-d', $att_dates);
        // Get the next day's date
        $nextDayDate = $exist_date->addDay()->toDateString();
        $att_in = DB::table('attendances as a')
            ->select('a.*', 'b.first_name', 'b.last_name')
            ->leftJoin('employees as b', 'a.employee_id', '=', 'b.id')
            // ->where('a.time', 'LIKE', '%' . $att_dates)
            ->where('a.employee_id', $attenddata[0]->uid)
            ->whereRaw("a.time > ?", [$att_dates])
            ->whereRaw("a.time < ?", [$nextDayDate])
            ->whereNull('a.deleted_at')
            ->orderBy('a.time', 'ASC')
            ->get();

        $ix = 1;
        $in_data = [];
        $out_data = [];
        foreach ($att_in as $attendancedata) {

            if ($ix % 2) {
                $status = "IN";
                $in_data[$ix] = $attendancedata->time;
            } else {
                $status = "OUT";
                $out_data[$ix] = $attendancedata->time;
            }
            $ix++;
        }

        $result_in = array_values($in_data);
        $result_out = array_values($out_data);
        $total = [];
        $count_out = count($result_out);

        if ($count_out >= 2) {
            $n_out = $count_out - 1;
        } else {
            $n_out = 0;
        }
        for ($i = 0; $i < $n_out; $i++) {

            $date_a = Carbon::parse($result_in[$i + 1]);
            $date_b = Carbon::parse($result_out[$i]);
            $interval = $date_a->diff($date_b);

            $total[$i] = $interval->format('%h:%i:%s');
        }

        $hou = 0;
        $min = 0;
        $sec = 0;
        $totaltime = '00:00:00';
        $length = sizeof($total);

        for ($x = 0; $x <= $length; $x++) {
            $split = explode(":", @$total[$x]);
            $hou += @(int)$split[0];
            $min += @$split[1];
            $sec += @$split[2];
        }

        $seconds = $sec % 60;
        $minutes = $sec / 60;
        $minutes = (int)$minutes;
        $minutes += $min;
        $hours = $minutes / 60;
        $minutes = $minutes % 60;
        $hours = (int)$hours;
        $hours += $hou % 24;

        $totalwasthour = $hours . ":" . $minutes . ":" . $seconds;

        $date_a = Carbon::parse($totalwhour);
        $date_b = Carbon::parse($totalwasthour);
        $networkhours = $date_a->diff($date_b);

        $totalnetworkhour = $networkhours->format('%h:%i:%s');

        return [
            "totalwasthour" => $totalwasthour,
            "totalnetworkhour" => $totalnetworkhour,
        ];
    }

    public function checkUser($userData)
    {
        // Attempt to authenticate the user
        if (Auth::attempt(['email' => $userData['email'], 'password' => $userData['password']])) {
            // Authentication successful
            $user = Auth::user();
            // Return user data or any response you want
            return $user;
        }
        return false;
    }

    public function userData($email)
    {
        $employeeData = Employee::select('employees.*', 'departments.department_name', 'users.profile_image as profile_pic', 'users.token_id')
            ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
            ->leftJoin('users', 'users.email', '=', 'employees.email')
            ->where('employees.email', $email)
            ->where('users.user_type_id', 2)
            ->first();

        return $employeeData;
    }
}
