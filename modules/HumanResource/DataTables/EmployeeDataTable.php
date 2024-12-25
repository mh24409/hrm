<?php

namespace Modules\HumanResource\DataTables;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Modules\HumanResource\Entities\Employee;
use Modules\Setting\Entities\DocExpiredSetting;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class EmployeeDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()

            ->editColumn('employee_id', function ($employee) {
                return $employee->employee_id;
            })
            ->editColumn('full_name', function ($employee) {
                return ucwords($employee->full_name);
            })
            ->filterColumn('full_name', function ($query, $keyword) {
                $query->where('first_name', 'like', "%{$keyword}%")
                    ->orWhere('last_name', 'like', "%{$keyword}%")
                    ->orWhere('middle_name', 'like', "%{$keyword}%");
            })
            ->editColumn('position_id', function ($employee) {
                return ucwords($employee->position ? $employee->position->position_name : '');
            })
            ->filterColumn('position_id', function ($query, $keyword) {
                $query->whereHas('position', function ($query) use ($keyword) {
                    $query->where('position_name', 'like', "%{$keyword}%");
                });
            })
            ->addColumn('status', function ($employee) {
                $status = '';

                if ($employee->is_active == 1) {
                    $status .= '<span class="badge badge-success-soft">' . localize('active') . '</span>';
                } else {
                    $status .= '<span class="badge badge-danger-soft">' . localize('inactive') . '</span>';
                }
                return $status;
            })

            ->addColumn('action', function ($employee) {

                $button = '';
                if (auth()->user()->can('read_employee')) {
                    if ($employee->is_active == 1) {
                        $button .= '<a href="' . route('employee.status_change', $employee->id) . '" class="btn btn-danger-soft btn-sm me-1" title="' . localize('inactive') . '"><i class="fa-solid fa-rotate"></i></a>';
                    } else {
                        $button .= '<a href="' . route('employee.status_change', $employee->id) . '" class="btn btn-success-soft btn-sm me-1" title="' . localize('active') . '"><i class="fa-solid fa-rotate"></i></a>';
                    }
                    $button .= '<a href="' . route('employees.show', $employee->id) . '" class="btn btn-primary-soft btn-sm me-1" title="Show"><i class="fa fa-eye"></i></a>';
                }

                if (auth()->user()->can('update_employee')) {
                    $button .= '<a href="' . route('employees.edit', $employee->id) . '" class="btn btn-success-soft btn-sm me-1" title="Edit"><i class="fa fa-edit"></i></a>';
                }

                if (auth()->user()->can('delete_employee')) {
                    $button .= '<a href="javascript:void(0)" class="btn btn-danger-soft btn-sm delete-confirm" data-bs-toggle="tooltip" title="Delete" data-route="' . route('employees.destroy', $employee->id) . '" data-csrf="' . csrf_token() . '"><i class="fas fa-trash-alt"></i></a>';
                }

                return $button;
            })
            ->setRowClass(function ($employee) {

                $hasRed = false;
                $hasSecondary = false;
                $hasYellow = false;

                $doc_expiry_day_setup = DocExpiredSetting::first();

                if (isset($employee->employee_docs) && count($employee->employee_docs) > 0) {
                    foreach ($employee->employee_docs as $docs) {
                        if (check_expiry($docs->expiry_date)) {
                            $hasRed = true;
                        } elseif (check_expiry($docs->expiry_date, $doc_expiry_day_setup->secondary_expiration_alert)) {
                            $hasSecondary = true;
                        } elseif (check_expiry($docs->expiry_date, $doc_expiry_day_setup->primary_expiration_alert)) {
                            $hasYellow = true;
                        }
                    }
                }

                if ($hasRed == true) {
                    return 'alert-danger';
                } elseif ($hasSecondary && ($hasRed == false || $hasYellow == false)) {
                    return 'alert-warning';
                } elseif ($hasYellow && ($hasRed == false || $hasSecondary == false)) {
                    return 'alert-info';
                }
            })

            ->rawColumns(['status', 'action']);
    }

    /**
     * Get query source of dataTable.
     */
    public function query(Employee $model): QueryBuilder
    {
        $name = $this->request->get('employee_name');
        $employee_id = $this->request->get('employee_id');
        $employee_type = $this->request->get('employee_type');
        $department = $this->request->get('department');
        $designation = $this->request->get('designation');
        $blood_group = $this->request->get('blood_group');
        $country = $this->request->get('country');
        $gender = $this->request->get('gender');
        $marital_status = $this->request->get('marital_status');

        return $model->newQuery()
            ->with('department')

            ->when($name, function ($query) use ($name) {
                return $query->where('id', $name);
            })
            ->when($employee_id, function ($query) use ($employee_id) {
                return $query->where('employee_id', $employee_id);
            })
            ->when($employee_type, function ($query) use ($employee_type) {
                return $query->where('employee_type_id', $employee_type);
            })
            ->when($department, function ($query) use ($department) {
                return $query->where('department_id', $department);
            })
            ->when($designation, function ($query) use ($designation) {
                return $query->where('position_id', $designation);
            })
            ->when($blood_group, function ($query) use ($blood_group) {
                return $query->where('blood_group', $blood_group);
            })
            ->when($country, function ($query) use ($country) {
                return $query->where('permanent_address_country', $country);
            })
            ->when($gender, function ($query) use ($gender) {
                return $query->where('gender_id', $gender);
            })
            ->when($marital_status, function ($query) use ($marital_status) {
                return $query->where('marital_status_id', $marital_status);
            })
            ->orderBy('employee_id', orderByData($this->request->get('order')));
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('employee-table')
            ->setTableAttribute('class', 'table table-hover table-bordered align-middle')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1, 'asc')
            ->language([
                //change preloader icon
                'processing' => '<div class="lds-spinner">
                <div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>',
            ])
            ->selectStyleSingle()
            ->lengthMenu([[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']])
            ->dom("<'row mb-3'<'col-md-4'l><'col-md-4 text-center'B><'col-md-4'f>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>")
            ->buttons([
                Button::make('csv')
                    ->className('btn btn-secondary buttons-csv buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-csv"></i> CSV')->exportOptions(['columns' => [0, 1, 2, 3, 4, 5, 6, 7, 8]]),
                Button::make('excel')
                    ->className('btn btn-secondary buttons-excel buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-excel"></i> Excel')->exportOptions(['columns' => [0, 1, 2, 3, 4, 5, 6, 7, 8]]),
            ]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [

            Column::make('DT_RowIndex')
                ->title(localize('sl'))
                ->addClass('text-center')
                ->searchable(false)
                ->orderable(false),

            Column::make('employee_id')
                ->title(localize('employee_id'))
                ->orderable(true),

            Column::make('full_name')
                ->title(localize('name_of_employee'))
                ->orderable(false),

            Column::make('email')
                ->title(localize('email'))
                ->orderable(false),

            Column::make('phone')
                ->title(localize('mobile_no'))
                ->orderable(false),

            Column::make('date_of_birth')
                ->title(localize('date_of_birth'))
                ->orderable(false),

            Column::make('position_id')
                ->title(localize('designation'))
                ->orderable(false),

            Column::make('joining_date')
                ->title(localize('joining_date'))
                ->orderable(true),

            Column::make('status')
                ->title(localize('status'))
                ->orderable(true),

            Column::make('action')
                ->title(localize('action'))->addClass('column-sl')->orderable(false)
                ->searchable(false)
                ->printable(false)
                ->exportable(false),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Employee-' . date('YmdHis');
    }
}
