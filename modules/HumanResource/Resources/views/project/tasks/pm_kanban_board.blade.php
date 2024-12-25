@extends('backend.layouts.app')
@section('title', localize('kanban_board'))
@push('css')
<link href="{{ module_asset('HumanResource/css/pm_kanban_board.css') }}" rel="stylesheet">
@endpush
@section('content')
    @include('backend.layouts.common.validation')
    @include('backend.layouts.common.message')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('kanban_board') }}({{$project_sprint->sprint_name}})</h6>
                </div>
            </div>
        </div>

        <div class="card-body">

            <div class="task-board">

                <div class="task-board-cards">
                <?php
                foreach ($statusResult as $statusRow) {

                    $taskResult = DB::table('pm_tasks_list')
                                ->select('pm_tasks_list.*', 'e.first_name', 'e.last_name', 'epid.first_name as ep_firstname', 'epid.last_name as ep_lastname', 'p.project_name', 'pms.sprint_name')
                                ->leftJoin('pm_projects as p', 'pm_tasks_list.project_id', '=', 'p.id')
                                ->leftJoin('pm_sprints as pms', 'pm_tasks_list.sprint_id', '=', 'pms.id')
                                ->leftJoin('employees as e', 'pm_tasks_list.project_lead', '=', 'e.id')
                                ->leftJoin('employees as epid', 'pm_tasks_list.employee_id', '=', 'epid.id')
                                ->where('pm_tasks_list.sprint_id', $sprint_id)
                                ->where('pm_tasks_list.task_status', $statusRow["id"])
                                ->whereNull('pm_tasks_list.deleted_at')
                                ->orderByDesc('pm_tasks_list.id')
                                ->get();
                    ?>
                    <div class="status-card">
                        <div class="kanban-card-header">
                            <span class="card-header-text"><?php echo $statusRow["status_name"]; ?></span>
                        </div>
                            <ul class="sortable ui-sortable"

                                id="sort<?php echo $statusRow["id"]; ?>"
                                data-status-id="<?php echo $statusRow["id"]; ?>">

                                <?php
                                if (! empty($taskResult)) {
                                    foreach ($taskResult as $taskRow) {
                                    ?>

                                        <li class="text-row ui-sortable-handle" data-task-id="<?php echo $taskRow->id; ?>"><?php echo $taskRow->summary; ?></li>

                                    <?php
                                    }
                                }
                                ?>
                            </ul>
                    </div>
                    <?php
                }
                ?>

                </div>    
                </div>
            </div>

        </div>

        <input type="hidden" id="kanban_task_update" value="{{ route('project.kanban-task-update') }}">

    </div>

@endsection

@push('js')

<script src="{{ module_asset('HumanResource/js/project_common.js') }}"></script>
@endpush
