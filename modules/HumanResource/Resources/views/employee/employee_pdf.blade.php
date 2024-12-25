<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Responsive Bootstrap 4 Admin &amp; Dashboard Template" />
    <meta name="author" content="Bdtask" />
    <title>invoice</title>
    <!--Global Styles(used by all pages)-->
    <link href="{{ asset('backend/assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ module_asset('HumanResource/css/employee-pdf.css') }}">
</head>
<body>
    <div id="print-table">
        <div class="wrapper">
            <div class="body-content" style="padding-bottom: 0px">
                <table class="mb-3" style="width: 100%">
                    <tbody tyle="border: solid 1px #ddd;">
                        <tr>
                            <td style="width: 15%">
                                <img class="logo" src="{{ app_setting()->logo }}" alt="" />
                            </td>
                            <td class="text-center" style="width: 70%">
                                <h4 class="text-muted fw-bold mb-0 text-uppercase">{{ app_setting()->title }}</h4>
                                <span class="mb-0 fs-14">{{ localize('human_resource_department') }}</span>
                                <h6 class="mb-0 fs-14">{{ localize('employee_information') }}</h6>
                            </td>
                            <td style="width: 15%">
                                <img class="user-img"
                                    src="{{ $employee->profile_img_location ? asset('storage/' . $employee->profile_img_location) : asset('backend/assets/dist/img/nopreview.jpeg') }}"
                                    alt="" />
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table class="table mb-4">
                    <thead>
                        <tr>
                            <th class="border-right" colspan="2">{{ localize('personal_information') }}</th>
                            <th class="border-left" colspan="2">{{ localize('employment_information') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ localize('name_of_employee') }}</td>
                            <td class="text-end border-right">{{ ucwords($employee->full_name) }}</td>
                            <td class="border-left">{{ localize('employee_id') }}</td>
                            <td class="text-end">{{ $employee->employee_id }}
                            </td>
                        </tr>

                        <tr>
                            <td>{{ localize('date_of_birth') }}</td>
                            <td class="text-end border-right">{{ $employee->date_of_birth }}</td>
                            <td class="border-left">{{ localize('employee_type') }}</td>
                            <td class="text-end">{{ $employee->duty_type->type_name }}</td>
                        </tr>
                        <tr>
                            <td>{{ localize('father_name') }}</td>
                            <td class="text-end border-right">{{ $employee->father_name }}</td>
                            <td class="border-left">{{ localize('designation') }}</td>
                            <td class="text-end">{{ $employee->position->position_name }}</td>
                        </tr>

                        <tr>
                            <td>{{ localize('mother_name') }}</td>
                            <td class="text-end border-right">{{ ucwords($employee->mother_name) }}</td>
                            <td class="border-left">{{ localize('department') }}</td>
                            <td class="text-end">{{ $employee->department->department_name }}</td>
                        </tr>
                        <tr>
                            <td>{{ localize('blood_group') }}</td>
                            <td class="text-end border-right">{{ $employee->blood_group }}</td>
                            <td class="border-left">{{ localize('joining_date') }}</td>
                            <td class="text-end">{{ $employee->joining_date }}</td>
                        </tr>
                        <tr>
                            <td>{{ localize('marital_status') }}</td>
                            <td class="text-end border-right">{{ $employee->marital_status->name }}</td>
                            <td>{{ localize('work_place') }}</td>
                            <td class="text-end border-right">{{ $employee->work_in_city }}</td>
                        </tr>
                        <tr>
                            <td>{{ localize('gender') }}</td>
                            <td class="text-end border-right">{{ $employee->gender->gender_name }}</td>
                            <td>{{ localize('last_promotion_date') }}</td>
                            <td class="text-end border-right">{{ $employee->promotion_date }}</td>
                        </tr>
                        <tr>
                            <td>{{ localize('identification_number') }}</td>
                            <td class="text-end border-right">{{ $employee->national_id }}</td>
                            <td>{{ localize('email_id') }}</td>
                            <td class="text-end border-right">{{ $employee->email }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>{{ localize('mobile_no') }}</td>
                            <td class="text-end border-right">{{ $employee->phone }}</td>
                        </tr>
                    </tbody>
                </table>

                <table class="table">
                    <thead>
                        <tr>
                            <th class="border-right" colspan="2">{{ localize('present_address') }}</th>
                            <th class="border-left" colspan="2">{{ localize('bank_information') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ localize('country') }}</td>
                            <td class="text-end border-right">{{ $employee->present_address_country }}</td>
                            <td class="border-left">{{ localize('account_name') }}</td>
                            <td class="text-end">{{ $bank_info->account_name }}</td>
                        </tr>

                        <tr>
                            <td>{{ localize('state_division') }}</td>
                            <td class="text-end border-right">{{ $employee->present_address_state }}</td>
                            <td class="border-left">{{ localize('bank_name') }}</td>
                            <td class="text-end">{{ $bank_info->bank_name }}</td>
                        </tr>
                        <tr>
                            <td>{{ localize('city_district') }}</td>
                            <td class="text-end border-right">
                                {{ $employee->present_address_city }}</td>
                            <td class="border-left">{{ localize('account_number') }}</td>
                            <td class="text-end">{{ $bank_info->acc_number }}</td>
                        </tr>
                        <tr>
                            <td>{{ localize('post_code') }}</td>
                            <td class="text-end border-right">{{ $employee->present_address_post_code }}</td>
                            <td class="border-left">{{ localize('branch') }}</td>
                            <td class="text-end">{{ $bank_info->branch_name }}</td>
                        </tr>
                        <tr>
                            <td>{{ localize('address') }}</td>
                            <td class="text-end border-right">{{ $employee->present_address_address }}</td>
                            <td>{{ localize('route_number') }}</td>
                            <td class="text-end border-right">{{ $bank_info->route_number }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-end border-right"></td>
                            <td>{{ localize('tin_no') }}</td>
                            <td class="text-end border-right">{{ $employee_file->tin_no }}</td>
                        </tr>
                    </tbody>
                </table>

                <table class="table">
                    <thead>
                        <tr>
                            <th class="border-right" colspan="2">{{ localize('academic_information') }}</th>
                            <th class="border-left" colspan="2">{{ localize('skill_information') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ localize('exam_title') }}</td>
                            <td class="text-end border-right">{{ $academicInfos[0]->exam_title }}</td>
                            <td class="border-left">{{ localize('skill_type') }}</td>
                            <td class="text-end">{{ $employee->skill_type }}</td>
                        </tr>

                        <tr>
                            <td>{{ localize('institute_name') }}</td>
                            <td class="text-end border-right">{{ $academicInfos[0]->institute_name }}</td>
                            <td class="border-left">{{ localize('skill_name') }}</td>
                            <td class="text-end">{{ $employee->skill_name }}</td>
                        </tr>
                        <tr>
                            <td>{{ localize('result') }}</td>
                            <td class="text-end border-right">
                                {{ $academicInfos[0]->result }}</td>
                            <td class="border-left">{{ localize('certificate_type') }}</td>
                            <td class="text-end">{{ $employee->certificate_type }}</td>
                        </tr>
                        <tr>
                            <td> {{ localize('graduation_year') }}</td>
                            <td class="text-end border-right">{{ $academicInfos[0]->graduation_year }}</td>
                            <td class="border-left">{{ localize('certificate_name') }}</td>
                            <td class="text-end">{{ $employee->certificate_name }}</td>
                        </tr>
                    </tbody>
                </table>

                <table class="footer-bg">
                    <tbody tyle="border: solid 1px #ddd;">
                        <tr>
                            <td style="width: 33.33%; padding-left:20px">
                                <p class="mb-0">{{ app_setting()->email }}</p>
                            </td>
                            <td class="text-center" style="width: 33.33%">
                                <p class="mb-0">{{ app_setting()->website }}</p>
                            </td>
                            <td style="width: 33.33%; padding-right:20px" class="text-end">
                                <p class="mb-0">{{ app_setting()->phone }}</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
