@extends('backend.layouts.app')
@section('title', 'Employee Performance')
@section('content')
    <!--/.Content Header (Page header)-->
    <div class="body-content">
        @include('backend.layouts.common.validation')
        <div class="container" id="print-table">
            <div class="card card-default mb-4">
                <div class="card-body">
                    <div class="text-end d-print-none" id="print">
                        <button type="button" class="btn btn-warning" id="btnPrint">
                            <i class="fa fa-print"></i>
                        </button>
                    </div>
                    <div id="printArea">
                        <div style="padding: 20px;">
                            <div class="row">
                                <div class="col-md-3">
                                    <img class="" style="width:80px; margin-top: -15px;"
                                        src="{{ app_setting()->logo ? url('/public/storage/' . app_setting()->logo) : asset('public/newAdmin/assets/dist/img/logo.png') }}"
                                        alt="">
                                </div>
                                <div class="col-md-5">
                                    <h3 style="text-align:center;">{{ date('Y', strtotime($employee_performance->date)) }}
                                        PERFOMANCE APPRAISAL</h3>
                                </div>
                                <div class="col-md-4" style="text-align:right;">
                                    <span>Serial No: #{{ $employee_performance->performance_code }}</span>
                                </div>
                            </div>
                            <div class="row mt-5">
                                <div class="col-md-6 mt-3">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <span class="fs-16 fw-700">Name of Employee:</span>
                                        </div>
                                        <div class="col-md-7 d-flex justify-content-center"
                                            style="border-bottom: 1px solid #000;">
                                            <span class="fs-16">{{ $employee_performance->employee?->full_name }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <span class="fs-16 fw-700">Department:</span>
                                        </div>
                                        <div class="col-md-8 d-flex justify-content-center"
                                            style="border-bottom: 1px solid #000;">
                                            <span
                                                class="fs-16">{{ $employee_performance->employee?->department?->department_name }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <span class="fs-16 fw-700">Job Title:</span>
                                        </div>
                                        <div class="col-md-8 d-flex justify-content-center"
                                            style="border-bottom: 1px solid #000;">
                                            <span
                                                class="fs-16">{{ $employee_performance->employee?->position?->position_name }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <span class="fs-16 fw-700">Review Period:</span>
                                        </div>
                                        <div class="col-md-8 d-flex justify-content-center"
                                            style="border-bottom: 1px solid #000;">
                                            <span class="fs-16"> {{ $employee_performance->review_period }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <span class="fs-16 fw-700">Name and Position of Supervisor/Head of Department
                                                :</span>
                                        </div>
                                        <div class="col-md-7 d-flex justify-content-center"
                                            style="border-bottom: 1px solid #000;">
                                            <span class="fs-16"> {{ $employee_performance->position_of_supervisor }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="fs-17 mt-3 fs-i">Please provide a critical assessment of the performance of
                                        the employee within the review period using the following rating scale. Provide
                                        examples where applicable. Please use a separate sheet if required.
                                    </p>
                                </div>
                            </div>
                            <table class="table table-bordered w-65 mt-3">
                                <thead>
                                    <tr>
                                        <th>P</th>
                                        <th>NI</th>
                                        <th>G</th>
                                        <th>VG</th>
                                        <th>E</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Poor</td>
                                        <td>Needs Improvement</td>
                                        <td>Good</td>
                                        <td>Very Good</td>
                                        <td>Excellent</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="row">
                                <h3 class="mt-3">A. ASSESSMENT OF GOALS/OBJECTIVES SET DURING THE REVIEW PERIOD</h3>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Criteria</th>
                                            <th>P <br> (0)</th>
                                            <th>NI <br> (3)</th>
                                            <th>G <br> (6)</th>
                                            <th>VG <br> (9)</th>
                                            <th>E <br> (12)</th>
                                            <th>Score</th>
                                            <th>Comments and Examples</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($asssesment_one as $i => $item)
                                            <tr>
                                                <td>{{ $item->employee_performance_criteria?->title }}</td>
                                                <td><?php if ($item->emp_perform_eval == 0) {
                                                    echo '0';
                                                } ?></td>
                                                <td><?php if ($item->emp_perform_eval == 3) {
                                                    echo '3';
                                                } ?></td>
                                                <td><?php if ($item->emp_perform_eval == 6) {
                                                    echo '6';
                                                } ?></td>
                                                <td><?php if ($item->emp_perform_eval == 9) {
                                                    echo '9';
                                                } ?></td>
                                                <td><?php if ($item->emp_perform_eval == 12) {
                                                    echo '12';
                                                } ?></td>
                                                <td>{{ $item->score }}</td>
                                                <td>{{ $item->comments }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td colspan="5">Total Score (Maximum = 60)</td>
                                            <td>{{ $asssesment_one->sum('score') }}</td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>


                            <div class="row">
                                <h3 class="mt-3">B. ASSESSMENT OF OTHER PERFORMANCE STANDARDS AND INDICATORS</h3>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Criteria</th>
                                            <th>P <br> (2)</th>
                                            <th>NI <br> (4)</th>
                                            <th>G <br> (6)</th>
                                            <th>VG <br> (9)</th>
                                            <th>E <br> (10)</th>
                                            <th>Score</th>
                                            <th>Comments and Examples</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($asssesment_two as $item_two)
                                            <tr>
                                                <td>{{ $item_two->employee_performance_criteria?->title }}</td>
                                                <td><?php if ($item_two->emp_perform_eval == 2) {
                                                    echo '2';
                                                } ?></td>
                                                <td><?php if ($item_two->emp_perform_eval == 4) {
                                                    echo '4';
                                                } ?></td>
                                                <td><?php if ($item_two->emp_perform_eval == 6) {
                                                    echo '6';
                                                } ?></td>
                                                <td><?php if ($item_two->emp_perform_eval == 9) {
                                                    echo '9';
                                                } ?></td>
                                                <td><?php if ($item_two->emp_perform_eval == 10) {
                                                    echo '10';
                                                } ?></td>
                                                <td>{{ $item_two->score }}</td>
                                                <td>{{ $item_two->comments }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td colspan="5">Total Score (Maximum = 60)</td>
                                            <td>{{ $asssesment_two->sum('score') }}</td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="row">
                                <h3 class="mt-3">C. TOTAL SCORE</h3>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Total Score (Score A + Score B)</th>
                                            <th>Overall Comments / Recommendations by Reviewer</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <table style="border: solid white !important;">
                                                    <tr style="border: 0px;">
                                                        <th style="padding: 10px; border: solid white !important;">
                                                            {{ $asssesment_one->sum('score') }} </th>
                                                        <th style="padding: 10px; border: solid white !important;">+</th>
                                                        <th style="padding: 10px; border: solid white !important;">
                                                            {{ $asssesment_two->sum('score') }} </th>
                                                        <th style="padding: 10px; border: solid white !important;">=</th>
                                                        <th style="padding: 10px; border: solid white !important;">
                                                            {{ $asssesment_one->sum('score') + $asssesment_two->sum('score') }}
                                                        </th>
                                                    </tr>
                                                </table>
                                                <div>
                                                    <p class="fw-700">Classification of Employee:</p>
                                                </div>
                                                <table style="border: solid white !important;">
                                                    <tr>
                                                        <th
                                                            style="padding: 5px; text-align: center; border: solid white !important;">
                                                            EE</th>
                                                        <th
                                                            style="padding: 5px; text-align: center; border: solid white !important;">
                                                            AE</th>
                                                        <th
                                                            style="padding: 5px; text-align: center; border: solid white !important;">
                                                            UE</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="padding: 10px; border: solid white !important;">(80-100)
                                                        </th>
                                                        <th style="padding: 10px; border: solid white !important;">(75-85)
                                                        </th>
                                                        <th style="padding: 10px; border: solid white !important;">(0-70)
                                                        </th>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td>
                                                <div>
                                                    <p class="fw-700">Name:</p>
                                                </div>
                                                <div>
                                                    <p class="fw-700">Signature:</p>
                                                </div>
                                                <div>
                                                    <p class="fw-700">Date:</p>
                                                </div>
                                                <div>
                                                    <p class="fw-700">Next Review Period:</p>
                                                </div>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <p class="fs-17 mt-3 fs-i">* If employee is an Unacceptably Performing Employee,
                                        please attach a Performance Improvement Plan (PIP)</p>
                                </div>
                            </div>

                            <br>

                            <div class="row">
                                <h3 class="mt-3">COMMENTS BY EMPLOYEE</h3>
                                <div class="form-group">
                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3">{{ $employee_performance->employee_comments }}</textarea>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <p>Name:</p>
                                </div>
                                <div class="col-md-4">
                                    <p>Signature:</p>
                                </div>
                                <div class="col-md-4">
                                    <p>Date:</p>
                                </div>
                            </div>

                            <br>

                            <div class="row">
                                <h3 class="mt-3">E. DEVELOPMENT PLAN</h3>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="25%">Recommended Areas for Improvement / Development</th>
                                            <th width="20%">Expected Outcome(s)</th>
                                            <th width="35%"> Responsible Person(s) to assist in the achievement of the
                                                Plan</th>
                                            <th width="10%">Start {{ __('language.date') }}</th>
                                            <th width="10%">End {{ __('language.date') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($development_plans as $key => $row)
                                            <tr>
                                                <td>{{ $row->recommend_areas }}</td>
                                                <td>
                                                    {{ $row->expected_outcomes }}
                                                </td>
                                                <td>{{ $row->responsible_person }}</td>
                                                <td>{{ $row->start_date }}</td>
                                                <td>{{ $row->end_date }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="row">
                                <h3 class="mt-3">F. KEY GOALS FOR NEXT REVIEW PERIOD</h3>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Goal (s) Set and Agreed on with Employee</th>
                                            <th>Proposed Completion Period</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($key_goals as $key => $goal)
                                            <tr>
                                                <td>{{ $goal->key_goals }}</td>
                                                <td>{{ $goal->completion_period }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="row mt-20">
                                <h3 class="mt-3">G. REVIEW / COMMENTS</h3>
                                <div class="col-md-4">
                                    <p>Name:</p>
                                </div>
                                <div class="col-md-4">
                                    <p>Signature:</p>
                                </div>
                                <div class="col-md-4">
                                    <p>Date:</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>
    </div>
    <!--/.body content-->
@endsection
@push('js')
    <script src="{{ module_asset('HumanResource/js/employee-performance-show.js') }}"></script>
@endpush
