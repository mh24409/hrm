<?php

namespace Modules\HumanResource\DataTables;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Modules\HumanResource\Entities\ApplyLeave;
use Modules\HumanResource\Entities\Attendance;
use Modules\HumanResource\Entities\Employee;
use Modules\HumanResource\Entities\ManualAttendance;
use Yajra\DataTables\CollectionDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AttendanceSummaryDataTable extends DataTable
{
    /**
     * Build DataTable Class
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable(): CollectionDataTable
    {
        $query = (new CollectionDataTable($this->collection()))->addIndexColumn();

        return $query->escapeColumns([]);
    }

    public function collection()
    {

        $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');

        $department = $this->request->get('department_id');

        $date = $this->request->get('date');
        $string = explode('-', $date);

        if ($date) {
            $startDate = date('Y-m-d', strtotime($string[0]));
            $endDate = date('Y-m-d', strtotime($string[1]));
        }

        $startDate = new \DateTime($startDate);
        $endDate = new \DateTime($endDate);

        $active_employees = Employee::where('is_active', true)->where('is_left', false)

            ->when($department, function ($query) use ($department) {
                $query->where('department_id', $department);
            })
            ->get();

        $data = [];

        for ($date = $startDate; $date <= $endDate; $date->modify('+1 day')) {

            $activeEmployees = ManualAttendance::whereDate('time', '=', Carbon::parse($date)->format('Y-m-d'))->whereNotNull('time')

                ->when($department, function ($query) use ($department) {
                    $query->whereHas('employee', function ($q) use ($department) {
                        $q->where('department_id', $department);
                    });
                })
                ->groupBy('employee_id');

            $totalPresentEmployees = $activeEmployees->get()->count();

            $totalLateEmployees = DB::select('select attend_date, sum(if (in_time > latetime,1,0)) latecount
                    from (
                        select a.employee_id, a.machine_id, DATE(`time`) attend_date, min(time(a.time)) as in_time, max(s.latetime) latetime
                        FROM `attendances` AS a
                        left join employees e on a.employee_id = e.id
                        left join ( SELECT
                                distinct employee_group_id,start_time latetime
                            FROM
                                setup_rules
                            INNER JOIN employees ON employees.employee_group_id = setup_rules.id
                        ) s
                        on e.employee_group_id = s.employee_group_id
                        group by a.employee_id, a.machine_id, date(`time`)
                        ) l
                        where attend_date = "' . Carbon::parse($date)->format('Y-m-d') . '"
                        group by attend_date');

            $totalLeaveEmployees = ApplyLeave::where('is_approved', true)
                ->whereDate('leave_approved_start_date', '<=', Carbon::parse($date)->format('Y-m-d'))
                ->whereDate('leave_approved_end_date', '>=', Carbon::parse($date)->format('Y-m-d'))

                ->when($department, function ($query) use ($department) {
                    $query->whereHas('employee', function ($q) use ($department) {
                        $q->where('department_id', $department);
                    });
                })
                ->groupBy('employee_id')
                ->get()->count();

            $totalAbsentEmployees = $active_employees->count() > 0 ? $active_employees->count() - $totalPresentEmployees : 0;

            $data[] = [
                'date' => Carbon::parse($date)->format('Y-m-d'),
                'totalPresentEmployees' => $totalPresentEmployees,
                'totalAbsentEmployees' => $totalAbsentEmployees > 0 ? $totalAbsentEmployees - $totalLeaveEmployees : 0,
                'totalLeaveEmployees' => $totalLeaveEmployees,
                'totalLateEmployees' => $totalLateEmployees ? $totalLateEmployees[0]->latecount : 0,
            ];
        }

        return collect($data);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('attendance-summary-table')
            ->setTableAttribute('class', 'table table-hover table-bordered align-middle')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->language([
                //change preloader icon
                'processing' => '<div class="lds-spinner">
                <div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>',
            ])
            ->responsive(true)
            ->selectStyleSingle()
            ->lengthMenu([[30, 50, 100, -1], [30, 50, 100, 'All']])
            ->dom("<'row mb-3'<'col-md-4'l><'col-md-4 text-center'B><'col-md-4'f>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>")
            ->buttons([

                Button::make('csv')
                    ->className('btn btn-secondary buttons-csv buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-csv"></i> CSV')->exportOptions(['columns' => [0, 1, 2, 3, 4, 5]]),
                Button::make('excel')
                    ->className('btn btn-secondary buttons-excel buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-excel"></i> Excel')
                    ->extend('excelHtml5')->exportOptions(['columns' => [0, 1, 2, 3, 4, 5]]),
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')
                ->title(localize('sl'))
                ->addClass('text-center column-sl')
                ->searchable(false)
                ->orderable(false),

            Column::make('date')
                ->title(localize('date'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('totalPresentEmployees')
                ->title(localize('present'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('totalAbsentEmployees')
                ->title(localize('absent'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('totalLeaveEmployees')
                ->title(localize('leave'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('totalLateEmployees')
                ->title(localize('late'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'attendance-summery' . date('YmdHis');
    }
}
