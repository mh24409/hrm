@extends('backend.layouts.app')
@section('title', localize('Employee Performance'))
@push('css')
    <link href="{{ asset('public/backend') }}/assets/custom.css" rel="stylesheet">
@endpush
@section('content')
    <!--/.Content Header (Page header)-->
    <div class="body-content" style="max-width: 80%; margin:0 auto;">
        @include('backend.layouts.common.validation')
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('Edit Employee Performance') }}</h6>
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
                        <h3> {{ strtoupper(localize('PERFORMANCE APPRAISAL INTERVIEW FORM')) }}</h3>
                    </div>
                    <div class="col-md-12 text-center">
                        <p class="fs-17 mt-3 fs-i text-danger">
                            {{ localize('all_field_are_required_except_comments') }}
                        </p>
                    </div>
                </div>
                <form action="{{ route('employee-performances.update', $employee_performance->id) }}" method="POST">
                    @method('PATCH')
                    @csrf
                    <div class="row mt-5">
                        <div class="col-md-6 mt-3">
                            <div class="d-flex align-items-center">
                                <label class="col-md-3">{{ localize('Name of Employee') }} :</label>
                                <select name="employee_id" class="form-control basic-single" tabindex="-1"
                                    aria-hidden="true" autocomplete="off">
                                    <option value="">{{ localize('Select Employee') }}</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}"
                                            {{ $employee_performance->employee_id == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="input-group d-flex align-items-center">
                                <span class="col-md-3 fw-700">{{ localize('Review Period') }} :</span>
                                <input type="number" name="review_period" class="form-control"
                                    placeholder="Review Period In Months" aria-describedby="basic-addon1" required=""
                                    autocomplete="off" value="{{ $employee_performance->review_period }}">
                            </div>
                        </div>
                        <div class="col-xs-12 mt-3">
                            <div class="input-group d-flex align-items-center">
                                <span
                                    class="col-md-4 fw-700">{{ localize('Name and Position of Supervisor/Head of Department') }}
                                    :</span>
                                <input type="text" name="position_of_supervisor" class="form-control"
                                    placeholder="Name and Position of Supervisor/Head of Department"
                                    aria-describedby="basic-addon1" autocomplete="off"
                                    value="{{ $employee_performance->position_of_supervisor }}">
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
                            <tbody id="assessment_one">
                                @foreach ($assessment_one as $i => $item)
                                    <tr>
                                        @if ($item->performance_criteria_id == 5)
                                            <td>{{ $item->employee_performance_criteria?->title }}</td>
                                            <td colspan="5">
                                                {{ $item->employee_performance_criteria?->description }}
                                            </td>
                                            <td>
                                                <input type="number" id="demonstrated_score_{{ $i }}"
                                                    name="assessment_one[{{ $i }}][score]"
                                                    class="form-control review-table assessment_a"
                                                    aria-describedby="basic-addon1" value="{{ $item->score }}"
                                                    autocomplete="off">
                                            </td>
                                            <td>
                                                <input type="text" name="assessment_one[{{ $i }}][comments]"
                                                    class="form-control review-table" value="{{ $item->comments }}">
                                                <input type="hidden" name="assessment_one[{{ $i }}][eval_id]"
                                                    value="{{ $item->id }}">
                                            </td>
                                        @else
                                            <td>{{ $item->employee_performance_criteria?->title }}</td>
                                            <td>
                                                <input type="radio" id="demonstrated_{{ $i }}"
                                                    class="demonstrated"
                                                    name="assessment_one[{{ $i }}][performance_eval]"
                                                    {{ $item->emp_perform_eval == 0 ? 'checked' : '' }} value="0">
                                            </td>
                                            <td>
                                                <input type="radio" id="demonstrated_{{ $i }}"
                                                    class="demonstrated"
                                                    name="assessment_one[{{ $i }}][performance_eval]"
                                                    {{ $item->emp_perform_eval == 3 ? 'checked' : '' }} value="3">
                                            </td>
                                            <td>
                                                <input type="radio" id="demonstrated_{{ $i }}"
                                                    class="demonstrated"
                                                    name="assessment_one[{{ $i }}][performance_eval]"
                                                    {{ $item->emp_perform_eval == 6 ? 'checked' : '' }} value="6">
                                            </td>
                                            <td>
                                                <input type="radio" id="demonstrated_{{ $i }}"
                                                    class="demonstrated"
                                                    name="assessment_one[{{ $i }}][performance_eval]"
                                                    {{ $item->emp_perform_eval == 9 ? 'checked' : '' }} value="9">
                                            </td>
                                            <td>
                                                <input type="radio" id="demonstrated_{{ $i }}"
                                                    class="demonstrated"
                                                    name="assessment_one[{{ $i }}][performance_eval]"
                                                    {{ $item->emp_perform_eval == 12 ? 'checked' : '' }} value="12">
                                            </td>
                                            <td>
                                                <input type="number" id="demonstrated_score_{{ $i }}"
                                                    name="assessment_one[{{ $i }}][score]"
                                                    class="form-control review-table assessment_a"
                                                    aria-describedby="basic-addon1" value="{{ $item->score }}"
                                                    autocomplete="off">
                                            </td>
                                            <td>
                                                <input type="text"
                                                    name="assessment_one[{{ $i }}][comments]"
                                                    class="form-control review-table" value="{{ $item->comments }}">
                                                <input type="hidden" name="assessment_one[{{ $i }}][eval_id]"
                                                    value="{{ $item->id }}">
                                            </td>
                                        @endif

                                    </tr>
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td class="text-end" colspan="5">{{ localize('Total Score (Maximum = 60)') }}</td>
                                    <td>
                                        <input type="number" id="assessment_a_total_score"
                                            name="assessment_a_total_score" class="form-control review-table"
                                            aria-describedby="basic-addon1" value="{{ $assessment_one->sum('score') }}"
                                            readonly autocomplete="off">
                                    </td>
                                    <td>
                                        <input type="text"
                                            class="form-control review-table assesment_total_score_commnets"
                                            aria-describedby="basic-addon1" autocomplete="off">
                                    </td>
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
                            <tbody id="assessment_two">
                                @foreach ($assessment_two as $i => $item_two)
                                    <tr>
                                        <td>{{ $item_two->employee_performance_criteria?->title }}</td>
                                        <td class="text-center">
                                            <input type="radio" id="interpersonal_p{{ $i }}"
                                                class="interpersonal"
                                                name="assessment_two[{{ $i }}][performance_eval]"
                                                {{ $item_two->emp_perform_eval == 2 ? 'checked' : '' }} value="2">
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" id="interpersonal_p{{ $i }}"
                                                class="interpersonal"
                                                name="assessment_two[{{ $i }}][performance_eval]"
                                                {{ $item_two->emp_perform_eval == 4 ? 'checked' : '' }} value="4">
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" id="interpersonal_p{{ $i }}"
                                                class="interpersonal"
                                                name="assessment_two[{{ $i }}][performance_eval]"
                                                {{ $item_two->emp_perform_eval == 6 ? 'checked' : '' }} value="6">
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" id="interpersonal_p{{ $i }}"
                                                class="interpersonal"
                                                name="assessment_two[{{ $i }}][performance_eval]"
                                                {{ $item_two->emp_perform_eval == 9 ? 'checked' : '' }} value="9">
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" id="interpersonal_p{{ $i }}"
                                                class="interpersonal"
                                                name="assessment_two[{{ $i }}][performance_eval]"
                                                {{ $item_two->emp_perform_eval == 12 ? 'checked' : '' }} value="12">
                                        </td>
                                        <td>
                                            <input type="number" id="interpersonal_score"
                                                name="assessment_two[{{ $i }}][score]"
                                                class="form-control review-table assessment_b"
                                                aria-describedby="basic-addon1" value="{{ $item_two->score }}"
                                                autocomplete="off">
                                        </td>
                                        <td>
                                            <input type="text" name="assessment_two[{{ $i }}][comments]"
                                                class="form-control review-table" aria-describedby="basic-addon1"
                                                autocomplete="off">
                                            <input type="hidden" name="assessment_two[{{ $i }}][eval_id]"
                                                value="{{ $item_two->id }}">
                                        </td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td></td>
                                    <td colspan="5">{{ localize('Total Score (Maximum = 40)') }}</td>
                                    <td>
                                        <input type="number" id="assessment_b_total_score"
                                            name="assessment_b_total_score" class="form-control review-table"
                                            aria-describedby="basic-addon1" value="{{ $assessment_two->sum('score') }}"
                                            readonly autocomplete="off">
                                    </td>
                                    <td>
                                        <input type="text"
                                            class="form-control review-table assesment_total_score_commnets"
                                            aria-describedby="basic-addon1" autocomplete="off">
                                    </td>
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
                                            <p class="fw-700" id="score_a">{{ $assessment_one->sum('score') }}</p>
                                            <span>&nbsp;+</span>
                                            <p class="pl-15 fw-700" id="score_b">{{ $assessment_two->sum('score') }}
                                            </p>
                                            <span>&nbsp;=</span>
                                            <p class="pl-15 fw-700" id="score_final">
                                                {{ $assessment_one->sum('score') + $assessment_two->sum('score') }}</p>
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
                        <textarea name="employee_comments" class="form-control" placeholder="Maximum 500 words" autocomplete="off"> {{ $employee_performance->employee_comments }} </textarea>
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
                                @foreach ($development_plans as $key => $development_plan)
                                    <tr>
                                        <td>
                                            <input type="hidden" name="development_id[]"
                                                value="{{ $development_plan->id }}">
                                            <textarea name="recommend_areas[]" class="form-control" placeholder="" required="" autocomplete="off">{{ $development_plan->recommend_areas }}</textarea>
                                        </td>
                                        <td>
                                            <textarea name="expected_outcomes[]" class="form-control" placeholder="" required="" autocomplete="off">{{ $development_plan->expected_outcomes }}</textarea>
                                        </td>
                                        <td>
                                            <input type="text" id="responsible_person" name="responsible_person[]"
                                                class="form-control" required="" autocomplete="off"
                                                value="{{ $development_plan->responsible_person }}">
                                        </td>
                                        <td><input type="date" id="start_date" name="start_date[]"
                                                class="form-control datepicker" required="" autocomplete="off"
                                                value="{{ $development_plan->start_date }}"></td>
                                        <td><input type="date" id="end_date" name="end_date[]"
                                                class="form-control datepicker" required="" autocomplete="off"
                                                value="{{ $development_plan->end_date }}"></td>

                                        <td width="100">
                                            <a id="add_dev_plan" class="btn btn-info btn-sm" name="add-invoice-item"
                                                onclick="add_dev_plans('request_item')" tabindex="9">
                                                <i class="fa fa-plus-square" aria-hidden="true"></i>
                                            </a>
                                            <a class="btn btn-danger btn-sm" value="Delete"
                                                onclick="deleteDevPlanRow(this)" tabindex="3">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
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
                                @foreach ($key_goals as $goal)
                                    <tr>
                                        <td>
                                            <input type="hidden" name="key_goal_id[]" value="{{ $goal->id }}">
                                            <textarea id="key_goals" name="key_goals[]" class="form-control" rows="1" placeholder="" required=""
                                                autocomplete="off">{{ $goal->key_goals }}</textarea>
                                        </td>
                                        <td>
                                            <input type="date" id="end_date" name="completion_period[]"
                                                class="form-control datepicker" required="" autocomplete="off"
                                                value="{{ $goal->completion_period }}">
                                        </td>
                                        <td width="100">
                                            <a id="add_key_goals" class="btn btn-info btn-sm" name="add-invoice-item"
                                                onclick="add_key_goals('request_item')"><i class="fa fa-plus-square"
                                                    aria-hidden="true"></i></a>
                                            <a class="btn btn-danger btn-sm" value="Delete" onclick="deleteRow(this)"><i
                                                    class="fa fa-trash" aria-hidden="true"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="form-group form-group-margin text-right">
                            <button type="submit" class="btn btn-success w-md m-b-5"
                                autocomplete="off">{{ localize('update') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--/.body content-->
@endsection
@push('js')
    <script src="{{ module_asset('HumanResource/js/performance-edit.js') }}"></script>
@endpush
