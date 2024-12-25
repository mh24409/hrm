@extends('backend.layouts.app')
@section('title', localize('project_report'))
@push('css')
    <link href="{{ module_asset('HumanResource/css/project.css') }}" rel="stylesheet">
@endpush
@section('content')
    @include('backend.layouts.common.validation')
    @include('backend.layouts.common.message')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('project_report') }}</h6>
                </div>
            </div>
        </div>
        <div class="card-body">


            <div class="row">
                <div class="col-sm-12 col-md-6 donut-chart">

                    <div class="panel panel-bd lobidisable">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h4>Donut chart for {{ $project_info->project_name }}</h4>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div id="doughnutChart"></div>
                        </div>
                    </div>

                </div>

                <div class="col-sm-12 col-md-6 bar-chart">

                    <div class="panel panel-bd lobidisable">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h4>Bar chart</h4>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div id="barChart"></div>
                        </div>
                    </div>

                </div>
            </div>


            <div class="row">
                <div class="col-sm-12 col-md-6 pie-chart">

                    <div class="panel panel-bd lobidisable">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h4>Pie chart for {{ $project_info->project_name }}</h4>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div id="pieChart"></div>
                        </div>
                    </div>

                </div>

                <div class="col-sm-12 col-md-6 two-dimentional">

                    <div class="panel panel-bd lobidrag">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h4>Two Dimentional</h4>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">{{ localize('team_member') }}</th>
                                            <th class="text-center">{{ localize('to_do') }}</th>
                                            <th class="text-center">{{ localize('in_progress') }}</th>
                                            <th class="text-center">{{ localize('done') }}</th>
                                            <th class="text-center">{{ localize('total') }}</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @foreach ($table as $item)
                                            <tr>
                                                <td class="text-center">
                                                    {{ $item['employee'] }}</td>
                                                <td class="text-center">{{ $item['to_do'] }}</td>
                                                <td class="text-center">{{ $item['in_progress'] }}</td>
                                                <td class="text-center">{{ $item['done'] }}</td>
                                                <td class="text-center">{{ $item['total'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <td class="text-center"><b>{{ localize('grand_total') }}</b></td>
                                            <td class="text-center">{{ $total_to_do }}</td>
                                            <td class="text-center">{{ $total_in_progress }}</td>
                                            <td class="text-center">{{ $total_done }}</td>
                                            <td class="text-center">{{ $grand_total }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hidden routes -->
            <input type="hidden" id="project_remaining" value="{{ route('project.project-remaining-completed') }}">

            <input type="hidden" id="project_all_employees_name"
                value="{{ route('project.project-all-employees-name') }}">
            <input type="hidden" id="task_to_do_by_employee" value="{{ route('project.task-to-do-by-employee') }}">
            <input type="hidden" id="task_in_progress_by_employee"
                value="{{ route('project.task-in-progress-by-employee') }}">
            <input type="hidden" id="task_done_by_employee" value="{{ route('project.task-done-by-employee') }}">

            <input type="hidden" id="project_various_status_tasks"
                value="{{ route('project.project-various-status-tasks') }}">
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ asset('backend/assets/plugins/apexcharts/dist/apexcharts.js') }}"></script>
    <script src="{{ module_asset('HumanResource/js/project_report.js') }}"></script>
@endpush
