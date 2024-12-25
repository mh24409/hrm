@extends('backend.layouts.app')
@section('title', localize('projects_list'))
@push('css')

<style type="text/css">

    /*Progressbar css starts*/

    .progress {
        height: 15px !important;
    }

    .progress-bar {
        font-size: 10px !important;
        line-height: 14px !important;
    }
    /*Progressbar css ends*/

</style>
@endpush
@section('content')
    @include('backend.layouts.common.validation')
    @include('backend.layouts.common.message')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('projects_list') }}</h6>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table display table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" width="5%">{{ localize('sl') }}</th>
                            <th width="12%">{{ localize('project_name') }}</th>
                            <th width="12%">{{ localize('client_name') }}</th>
                            <th width="10%">{{ localize('project_lead') }}</th>
                            <th width="10%">{{ localize('approximate_tasks') }}</th>
                            <th width="8%">{{ localize('start_date') }}</th>
                            <th width="8%">{{ localize('end_date') }}</th>
                            <th width="8%">{{ localize('sprint_start') }}</th>
                            <th width="8%">{{ localize('project_duration') }}</th>
                            <th width="5%">{{ localize('status') }}</th>
                            <th width="14%">{{ localize('action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects as $key => $data)

                            @php
                                // Getting progressbar value for approximate_tasks vs completed_tasks
                                $percentage = 100;
                                $complete_percentage = 0;
                                $remaining_percentage = 0;

                                $progress_complete = ""; // Fix: Corrected variable name
                                $progress_remaining = ""; // Fix: Corrected variable name

                                $project_not_started = 'style="width: 100%"';

                                $approximate_tasks = $data->approximate_tasks ?? 0;
                                $complete_tasks = $data->complete_tasks ?? 0;

                                if ($approximate_tasks != 0) {
                                    $complete_percentage = ($complete_tasks / $approximate_tasks) * 100;
                                    $complete_percentage = round($complete_percentage);

                                    $remaining_percentage = $percentage - $complete_percentage;
                                    $remaining_percentage = round($remaining_percentage);

                                    $progress_complete = 'style="width: '.$complete_percentage.'%"'; // Fix: Corrected variable name
                                    $progress_remaining = 'style="width: '.$remaining_percentage.'%"'; // Fix: Corrected variable name
                                }

                                // End of progressbar value for approximate_tasks vs completed_tasks

                                // Getting progressbar value for project_duration vs total days passed from project starts
                                $duration_percentage = 100;
                                $days_passed = 0;
                                $remaining_days = 0;
                                $duration_complete_percentage = 0;
                                $duration_remaining_percentage = 0;

                                $duration_progress_complete = ""; // Fix: Corrected variable name
                                $duration_progress_remaining = ""; // Fix: Corrected variable name

                                if ($data->start_date) {
                                    $now = time();
                                    $your_date = strtotime($data->start_date);
                                    $datediff = $now - $your_date;

                                    $days_passed = round($datediff / (60 * 60 * 24));
                                    $remaining_days = (int) $data->project_duration - (int) $days_passed;
                                }

                                if ($remaining_days > 0 && $data->project_duration != 0) {
                                    $duration_complete_percentage = ($days_passed / $data->project_duration) * 100;
                                    $duration_complete_percentage = round($duration_complete_percentage);

                                    $duration_remaining_percentage = $duration_percentage - $duration_complete_percentage;
                                    $duration_remaining_percentage = round($duration_remaining_percentage);

                                    $duration_progress_complete = 'style="width: '.$duration_complete_percentage.'%"'; // Fix: Corrected variable name
                                    $duration_progress_remaining = 'style="width: '.$duration_remaining_percentage.'%"'; // Fix: Corrected variable name
                                }
                            @endphp

                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td>{{ $data->project_name }}</td>
                                <td>{{ $data->clientDetail->client_name }}</td>
                                <td>{{ $data->projectLead->full_name }}</td>
                                <td>
                                    <!-- {{ $data->approximate_tasks }} -->
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="{{ $complete_percentage }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $complete_percentage }}%">
                                            {{ $complete_percentage }}% Complete
                                        </div>
                                        <div class="progress-bar progress-bar-danger progress-bar-striped active" role="progressbar" aria-valuenow="{{ $remaining_percentage }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $remaining_percentage }}%">
                                            {{ $remaining_percentage }}% Remaining
                                        </div>
                                    </div>

                                </td>

                                <td>{{ $data->project_start_date ? $data->project_start_date : 'Not Started' }}</td>
                                <td>
                                    @if ($data->close_date)
                                        {{ $data->close_date }}
                                    @else
                                        @if ($data->start_date)
                                            Running
                                        @else
                                            Waiting
                                        @endif
                                    @endif
                                </td>
                                <td>{{ $data->start_date ? $data->start_date : 'Not Started' }}</td>

                                <td>
                                    @if (!$data->start_date || strtotime($data->start_date) > strtotime(date("Y-m-d")))
                                        <div class="progress-bar progress-bar-danger progress-bar-striped active" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" {{ $project_not_started }}>
                                            Not Started
                                        </div>
                                    @else
                                        @if ($remaining_days > 0)
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="{{ $duration_complete_percentage }}" aria-valuemin="0" aria-valuemax="100" {{ $duration_progress_complete }}>
                                                    {{ $duration_complete_percentage }} % passed
                                                </div>
                                                <div class="progress-bar progress-bar-danger progress-bar-striped active" role="progressbar" aria-valuenow="{{ $duration_remaining_percentage }}" aria-valuemin="0" aria-valuemax="100" {{ $duration_progress_remaining }}>
                                                    {{ $duration_remaining_percentage }} % remaining
                                                </div>
                                            </div>
                                        @else
                                            <div class="progress-bar progress-bar-danger progress-bar-striped active" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" {{ $project_not_started }}>
                                                0 days remaining
                                            </div>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <p class="btn btn-xs {{ (int)$data->is_completed == 1 ? 'btn-success' : 'btn-danger' }}">
                                        {{ (int)$data->is_completed != 0 ? 'Closed' : 'Open' }}
                                    </p>
                                </td>

                                <td>
                                    @can('update_projects')
                                        <a href="{{ route('project.project-wise-report', $data->id) }}" class="btn btn-success-soft btn-sm me-1" title="{{localize('reports')}}">{{localize('reports')}}</a>
                                    @endcan
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="11" class="text-center">{{ localize('empty_data') }}</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

        </div>

    </div>

@endsection
@push('js')

@endpush
