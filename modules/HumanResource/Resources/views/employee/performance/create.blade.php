@extends('backend.layouts.app')
@section('title', 'Employee Performance Create')
@push('css')
    <link href="{{ asset('backend/assets/custom.css') }}" rel="stylesheet">
@endpush
@section('content')
    <!--/.Content Header (Page header)-->
    <div class="body-content" style="max-width: 80%; margin:0 auto;">
        @include('backend.layouts.common.validation')
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('Add New Action') }}</h6>
                    </div>
                    <div class="text-end">
                        <div class="actions">
                            <a href="{{ route('employee-performances.index') }}" class="btn btn-success btn-sm"><i
                                    class="fa fa-list"></i>&nbsp;{{ localize('Employee Performance List') }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h3>{{ date('Y') }} {{ strtoupper(localize('PERFORMANCE APPRAISAL INTERVIEW FORM')) }}</h3>
                    </div>
                    <div class="col-md-12 text-center">
                        <p class="fs-17 mt-3 fs-i text-danger">
                            {{ localize('all_field_are_required_except_comments') }}
                        </p>
                    </div>
                </div>
                <form action="{{ route('employee-performances.store') }}" method="POST">
                    @csrf
                    <div class="row mt-5">
                        <div class="col-md-6 mb-20">
                            <div class="d-flex align-items-center">
                                <label class="col-md-3">{{ localize('Name of Employee') }} :</label>
                                <select name="employee_id" class="form-control basic-single" tabindex="-1"
                                    aria-hidden="true" autocomplete="off" required>
                                    <option value="">{{ localize('Select Employee') }}</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-20">
                            <div class="input-group d-flex align-items-center">
                                <span class="col-md-3 fw-700">{{ localize('Review Period') }} :</span>
                                <input type="number" name="review_period" class="form-control"
                                    placeholder="Review Period In Months" aria-describedby="basic-addon1" required
                                    autocomplete="off">
                            </div>
                        </div>
                        <div class="col-xs-12 mt-3">
                            <div class="input-group d-flex align-items-center">
                                <span
                                    class="col-md-4 fw-700">{{ localize('Name and Position of Supervisor/Head of Department') }}
                                    :</span>
                                <input type="text" name="position_of_supervisor" class="form-control" required
                                    placeholder="Name and Position of Supervisor/Head of Department"
                                    aria-describedby="basic-addon1" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <p class="fs-17 mt-3 fs-i">
                                {{ localize('Please provide a critical assessment of the performance of the employee within the review period using the following rating scale. Provide examples where applicable. Please use a separate sheet if required.') }}
                            </p>
                        </div>
                    </div>
                    <table class="table table-bordered w-65">
                        <thead>
                            <tr>
                                @foreach ($performance_evaluations as $key => $item)
                                    <td>{{ $key }}</td>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @foreach ($performance_evaluations as $item)
                                    <td>{{ $item }}</td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                    <div class="row">
                        <h3 class="mt-3">
                            {{ strtoupper(localize('A. ASSESSMENT OF GOALS/OBJECTIVES SET DURING THE REVIEW PERIOD')) }}
                        </h3>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ localize('Criteria') }}</th>
                                    <th>P (0)</th>
                                    <th>NI (3)</th>
                                    <th>G (6)</th>
                                    <th>VG (9)</th>
                                    <th>E (12)</th>
                                    <th>{{ localize('Score') }}</th>
                                    <th>{{ localize('Comments and Examples') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Demonstrated Knowledge of duties &amp; Quality of Work</td>
                                    <td class="text-center">
                                        <input type="radio" id="demonstrated_p" class="demonstrated" name="demonstrated"
                                            checked="checked" value="0" autocomplete="off">
                                    </td>
                                    <td class="text-center"><input type="radio" id="demonstrated_ni" class="demonstrated"
                                            name="demonstrated" value="3" autocomplete="off">
                                    </td>
                                    <td class="text-center"><input type="radio" id="demonstrated_g" class="demonstrated"
                                            name="demonstrated" value="6" autocomplete="off"></td>
                                    <td class="text-center"><input type="radio" id="demonstrated_vg" class="demonstrated"
                                            name="demonstrated" value="9" autocomplete="off"></td>
                                    <td class="text-center"><input type="radio" id="demonstrated_e" class="demonstrated"
                                            name="demonstrated" value="12" autocomplete="off"></td>
                                    <td><input type="number" id="demonstrated_score" name="demonstrated_score"
                                            class="form-control review-table assessment_a" aria-describedby="basic-addon1"
                                            value="0" autocomplete="off"></td>
                                    <td><input type="text" name="demonstrated_comments"
                                            class="form-control review-table" aria-describedby="basic-addon1"
                                            autocomplete="off"></td>
                                </tr>
                                <tr>
                                    <td>Timeliness of Delivery</td>
                                    <td class="text-center"><input type="radio" id="timeliness_p" class="timeliness"
                                            name="timeliness" checked="checked" value="0" autocomplete="off"></td>
                                    <td class="text-center"><input type="radio" id="timeliness_ni" class="timeliness"
                                            name="timeliness" value="3" autocomplete="off">
                                    </td>
                                    <td class="text-center"><input type="radio" id="timeliness_g" class="timeliness"
                                            name="timeliness" value="6" autocomplete="off">
                                    </td>
                                    <td class="text-center"><input type="radio" id="timeliness_vg" class="timeliness"
                                            name="timeliness" value="9" autocomplete="off">
                                    </td>
                                    <td class="text-center"><input type="radio" id="timeliness_e" class="timeliness"
                                            name="timeliness" value="12" autocomplete="off">
                                    </td>
                                    <td><input type="number" id="timeliness_score" name="timeliness_score"
                                            class="form-control review-table assessment_a" aria-describedby="basic-addon1"
                                            value="0" autocomplete="off"></td>
                                    <td><input type="text" name="timeliness_score_commnets"
                                            class="form-control review-table" aria-describedby="basic-addon1"
                                            autocomplete="off"></td>
                                </tr>
                                <tr>
                                    <td>Impact of Achievement</td>
                                    <td class="text-center"><input type="radio" id="impact_p" class="impact"
                                            name="impact" checked="checked" value="0" autocomplete="off"></td>
                                    <td class="text-center"><input type="radio" id="impact_ni" class="impact"
                                            name="impact" value="3" autocomplete="off"></td>
                                    <td class="text-center"><input type="radio" id="impact_g" class="impact"
                                            name="impact" value="6" autocomplete="off"></td>
                                    <td class="text-center"><input type="radio" id="impact_vg" class="impact"
                                            name="impact" value="9" autocomplete="off"></td>
                                    <td class="text-center"><input type="radio" id="impact_e" class="impact"
                                            name="impact" value="12" autocomplete="off"></td>
                                    <td><input type="number" id="impact_score" name="impact_score"
                                            class="form-control review-table assessment_a" aria-describedby="basic-addon1"
                                            value="0" autocomplete="off"></td>
                                    <td><input type="text" name="impact_score_commnets"
                                            class="form-control review-table" aria-describedby="basic-addon1"
                                            autocomplete="off"></td>
                                </tr>
                                <tr>
                                    <td>Overall Achievement of Goals/Objectives</td>
                                    <td class="text-center"><input type="radio" id="overall_p" class="overall"
                                            name="overall" checked="checked" value="0" autocomplete="off"></td>
                                    <td class="text-center"><input type="radio" id="overall_ni" class="overall"
                                            name="overall" value="3" autocomplete="off"></td>
                                    <td class="text-center"><input type="radio" id="overall_g" class="overall"
                                            name="overall" value="6" autocomplete="off"></td>
                                    <td class="text-center"><input type="radio" id="overall_vg" class="overall"
                                            name="overall" value="9" autocomplete="off"></td>
                                    <td class="text-center"><input type="radio" id="overall_e" class="overall"
                                            name="overall" value="12" autocomplete="off"></td>
                                    <td><input type="number" id="overall_score" name="overall_score"
                                            class="form-control review-table assessment_a" aria-describedby="basic-addon1"
                                            value="0" autocomplete="off"></td>
                                    <td><input type="text" name="overall_score_commnets"
                                            class="form-control review-table" aria-describedby="basic-addon1"
                                            autocomplete="off"></td>
                                </tr>
                                <tr>
                                    <td>Going beyond the call of Duty</td>
                                    <td colspan="5">Extra (6, 9, or 12) bonus points to be <br> earned for going
                                        beyond the call of duty</td>
                                    <td><input type="number" id="beyond_duty" name="beyond_duty"
                                            class="form-control review-table assessment_a" aria-describedby="basic-addon1"
                                            value="0" autocomplete="off"></td>
                                    <td><input type="text" name="beyond_duty_commnets"
                                            class="form-control review-table" aria-describedby="basic-addon1"
                                            autocomplete="off"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-end" colspan="5">{{ localize('Total Score (Maximum = 60)') }}</td>
                                    <td><input type="number" id="assessment_a_total_score"
                                            name="assessment_a_total_score" class="form-control review-table"
                                            aria-describedby="basic-addon1" value="0" readonly=""
                                            autocomplete="off"></td>
                                    <td><input type="text"
                                            class="form-control review-table assesment_total_score_commnets"
                                            aria-describedby="basic-addon1" autocomplete="off"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <h3 class="mt-3">
                            {{ strtoupper(localize('B. ASSESSMENT OF OTHER PERFORMANCE STANDARDS AND INDICATORS')) }}
                        </h3>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ localize('Criteria') }}</th>
                                    <th>P (2)</th>
                                    <th>NI (4)</th>
                                    <th>G (6)</th>
                                    <th>VG (9)</th>
                                    <th>E (10)</th>
                                    <th>{{ localize('Score') }}</th>
                                    <th>{{ localize('Comments and Examples') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Interpersonal skills &amp; ability to work in a team environment</td>
                                    <td class="text-center"><input type="radio" id="interpersonal_p"
                                            class="interpersonal" name="interpersonal" checked="checked" value="2"
                                            autocomplete="off"></td>
                                    <td class="text-center"><input type="radio" id="interpersonal_ni"
                                            class="interpersonal" name="interpersonal" value="4"
                                            autocomplete="off"></td>
                                    <td class="text-center"><input type="radio" id="interpersonal_g"
                                            class="interpersonal" name="interpersonal" value="6"
                                            autocomplete="off"></td>
                                    <td class="text-center"><input type="radio" id="interpersonal_vg"
                                            class="interpersonal" name="interpersonal" value="9"
                                            autocomplete="off"></td>
                                    <td class="text-center"><input type="radio" id="interpersonal_e"
                                            class="interpersonal" name="interpersonal" value="10"
                                            autocomplete="off"></td>
                                    <td><input type="number" id="interpersonal_score" name="interpersonal_score"
                                            class="form-control review-table assessment_b" aria-describedby="basic-addon1"
                                            value="0" autocomplete="off">
                                    </td>
                                    <td><input type="text" name="interpersonal_commnets"
                                            class="form-control review-table" aria-describedby="basic-addon1"
                                            autocomplete="off"></td>
                                </tr>
                                <tr>
                                    <td>Attendance and Punctuality</td>
                                    <td class="text-center"><input type="radio" id="attendance_p" class="attendance"
                                            name="attendance" checked="checked" value="2" autocomplete="off"></td>
                                    <td class="text-center"><input type="radio" id="attendance_ni" class="attendance"
                                            name="attendance" value="4" autocomplete="off">
                                    </td>
                                    <td class="text-center"><input type="radio" id="attendance_g" class="attendance"
                                            name="attendance" value="6" autocomplete="off">
                                    </td>
                                    <td class="text-center"><input type="radio" id="attendance_vg" class="attendance"
                                            name="attendance" value="9" autocomplete="off">
                                    </td>
                                    <td class="text-center"><input type="radio" id="attendance_e" class="attendance"
                                            name="attendance" value="10" autocomplete="off">
                                    </td>
                                    <td><input type="number" id="attendance_score" name="attendance_score"
                                            class="form-control review-table assessment_b" aria-describedby="basic-addon1"
                                            value="0" autocomplete="off">
                                    </td>
                                    <td><input type="text" name="attendance_commnets"
                                            class="form-control review-table" aria-describedby="basic-addon1"
                                            autocomplete="off"></td>
                                </tr>
                                <tr>
                                    <td>Communication Skills</td>
                                    <td class="text-center"><input type="radio" id="communication_p"
                                            class="communication" name="communication" checked="checked" value="2"
                                            autocomplete="off"></td>
                                    <td class="text-center"><input type="radio" id="communication_ni"
                                            class="communication" name="communication" value="4"
                                            autocomplete="off"></td>
                                    <td class="text-center"><input type="radio" id="communication_g"
                                            class="communication" name="communication" value="6"
                                            autocomplete="off"></td>
                                    <td class="text-center"><input type="radio" id="communication_vg"
                                            class="communication" name="communication" value="9"
                                            autocomplete="off"></td>
                                    <td class="text-center"><input type="radio" id="communication_e"
                                            class="communication" name="communication" value="10"
                                            autocomplete="off"></td>
                                    <td><input type="number" id="communication_score" name="communication_score"
                                            class="form-control review-table assessment_b" aria-describedby="basic-addon1"
                                            value="0" autocomplete="off">
                                    </td>
                                    <td><input type="text" name="communication_commnets"
                                            class="form-control review-table" aria-describedby="basic-addon1"
                                            autocomplete="off"></td>
                                </tr>
                                <tr>
                                    <td>Contributing to company mission</td>
                                    <td class="text-center"><input type="radio" id="contributing_p"
                                            class="contributing" name="contributing" checked="checked" value="2"
                                            autocomplete="off"></td>
                                    <td class="text-center"><input type="radio" id="contributing_ni"
                                            class="contributing" name="contributing" value="4" autocomplete="off">
                                    </td>
                                    <td class="text-center"><input type="radio" id="contributing_g"
                                            class="contributing" name="contributing" value="6" autocomplete="off">
                                    </td>
                                    <td class="text-center"><input type="radio" id="contributing_vg"
                                            class="contributing" name="contributing" value="9" autocomplete="off">
                                    </td>
                                    <td class="text-center"><input type="radio" id="contributing_e"
                                            class="contributing" name="contributing" value="10" autocomplete="off">
                                    </td>
                                    <td><input type="number" id="contributing_score" name="contributing_score"
                                            class="form-control review-table assessment_b" aria-describedby="basic-addon1"
                                            value="0" autocomplete="off">
                                    </td>
                                    <td><input type="text" name="contributing_commnets"
                                            class="form-control review-table" aria-describedby="basic-addon1"
                                            autocomplete="off"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td colspan="5">{{ localize('Total Score (Maximum = 40)') }}</td>
                                    <td><input type="number" id="assessment_b_total_score"
                                            name="assessment_b_total_score" class="form-control review-table"
                                            aria-describedby="basic-addon1" value="0" readonly=""
                                            autocomplete="off"></td>
                                    <td><input type="text"
                                            class="form-control review-table assesment_total_score_commnets"
                                            aria-describedby="basic-addon1" autocomplete="off"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="row">
                        <h3 class="mt-3">{{ strtoupper(localize('C. TOTAL SCORE')) }}</h3>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ localize('Total Score (Score A + Score B)') }}</th>
                                    <th>{{ localize('Overall Comments / Recommendations by Reviewer') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex">
                                            <p class="fw-700" id="score_a">0</p>
                                            <span>&nbsp;+</span>
                                            <p class="pl-15 fw-700" id="score_b">0</p>
                                            <span>&nbsp;=</span>
                                            <p class="pl-15 fw-700" id="score_final">0</p>
                                        </div>
                                        <div>
                                            <p class="fw-700">{{ localize('Classification of Employee') }}:</p>
                                        </div>
                                        <div class="d-flex">
                                            <p class="fw-700">EE <br> (80-100)</p>
                                            <p class="pl-15 fw-700">AE <br> (75-85)</p>
                                            <p class="pl-15 fw-700">UE <br> (0-70)</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <p class="fw-700">{{ localize('Name') }}:</p>
                                        </div>
                                        <div>
                                            <p class="fw-700">{{ localize('Signature') }}:</p>
                                        </div>
                                        <div>
                                            <p class="fw-700">{{ localize('Date') }}:</p>
                                        </div>
                                        <div>
                                            <p class="fw-700">{{ localize('Next Review Period') }}:</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <h3 class="mt-3">{{ strtoupper(localize('D. COMMENTS BY EMPLOYEE')) }}</h3>
                        <textarea name="employee_comments" class="form-control" required placeholder="Maximum 500 words" autocomplete="off"></textarea>
                    </div>
                    <div class="row">
                        <h3 class="mt-3">{{ strtoupper(localize('E. DEVELOPMENT PLAN')) }}</h3>
                        <table class="table table-bordered" id="request_table_dev_plan">
                            <thead>
                                <tr>
                                    <th width="20%">{{ localize('Recommended Areas for Improvement / Development') }}
                                    </th>
                                    <th width="20%">{{ localize('Expected Outcome(s)') }}</th>
                                    <th width="20%">
                                        {{ localize('Responsible Person(s) to assist in the achievement of the Plan') }}
                                    </th>
                                    <th width="13%">{{ localize('Start date') }}</th>
                                    <th width="13%">{{ localize('End date') }}</th>
                                    <th width="13%">{{ localize('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody id="key_dev_plan_item">
                                <tr>
                                    <td>
                                        <textarea name="recommend_areas[]" class="form-control" placeholder="" required autocomplete="off"></textarea>
                                    </td>
                                    <td>
                                        <textarea name="expected_outcomes[]" class="form-control" placeholder="" required autocomplete="off"></textarea>
                                    </td>
                                    <td><input type="text" id="responsible_person" name="responsible_person[]"
                                            class="form-control" required autocomplete="off"></td>
                                    <td><input type="date" id="start_date" name="start_date[]"
                                            class="form-control datepicker" required autocomplete="off"></td>
                                    <td><input type="date" id="end_date" name="end_date[]"
                                            class="form-control datepicker" required autocomplete="off"></td>
                                    <td width="100">
                                        <a id="add_dev_plan" class="btn btn-info btn-sm" name="add-invoice-item"
                                            onclick="add_dev_plans('request_item')" tabindex="9"><i
                                                class="fa fa-plus-square" aria-hidden="true"></i></a>
                                        <a class="btn btn-danger btn-sm" value="Delete" onclick="deleteDevPlanRow(this)"
                                            tabindex="3"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <h3 class="mt-3">{{ strtoupper(localize('F. KEY GOALS FOR NEXT REVIEW PERIOD')) }}</h3>
                        <table class="table table-bordered" id="request_table">
                            <thead>
                                <tr>
                                    <th>{{ localize('Goal (s) Set and Agreed on with Employee') }}</th>
                                    <th>{{ localize('Proposed Completion Period') }}</th>
                                    <th class="text-center">{{ localize('Action') }} <i class="text-danger"></i></th>
                                </tr>
                            </thead>
                            <tbody id="key_goals_item">
                                <tr>
                                    <td>
                                        <textarea id="key_goals" name="key_goals[]" class="form-control" rows="2" placeholder="" tabindex="10"
                                            required autocomplete="off"></textarea>
                                    </td>
                                    <td>
                                        <input type="date" id="end_date" name="completion_period[]"
                                            class="form-control datepicker" required autocomplete="off">
                                    </td>
                                    <td width="100">
                                        <a id="add_key_goals" class="btn btn-info btn-sm" name="add-invoice-item"
                                            onclick="add_key_goals('request_item')" tabindex="9"><i
                                                class="fa fa-plus-square" aria-hidden="true"></i></a>
                                        <a class="btn btn-danger btn-sm" value="Delete" onclick="deleteRow(this)"
                                            tabindex="3"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group form-group-margin text-right">
                            <button type="submit" class="btn btn-success w-md m-b-5"
                                autocomplete="off">{{ localize('Save') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--/.body content-->
@endsection
@push('js')
    <script src="{{ module_asset('HumanResource/js/performance.js') }}"></script>
@endpush
