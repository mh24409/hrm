<?php

namespace Modules\HumanResource\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\User\Entities\Student;
use Modules\HumanResource\Entities\Employee;
class IdcardController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read_id_card')->only(['employeeindex','employeeshow']);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function employeeindex()
    {
        $dbData = Employee::where('is_active',1)->paginate(30);
        return view('humanresource::idprint.employeeindex',compact('dbData'));
    }

    public function employeeshow(Employee $idprint)
    {
        $dbData = $idprint;
        return view('humanresource::idprint.employeeid',compact('dbData'));
    }
}
