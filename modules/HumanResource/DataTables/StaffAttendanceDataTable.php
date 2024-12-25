<?php

namespace Modules\HumanResource\DataTables;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\HumanResource\Entities\Employee;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class StaffAttendanceDataTable extends DataTable
{
    /**
     * Build DataTable Class
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $query = (new EloquentDataTable($query))

            ->addIndexColumn()

            ->addColumn('staff_name', function ($row) {
                return '<a href="' . route('reports.staff-attendance-detail', $row->id) . '">' . $row->full_name . '</a>';
            })
            ->filterColumn('staff_name', function ($query, $keyword) {
                $query->where('full_name', 'like', "%{$keyword}%");
            })

            ->editColumn('employee_id', function ($row) {
                return $row->employee_id;
            })
            ->filterColumn('employee_id', function ($query, $keyword) {
                $query->where('employee_id', 'like', "%{$keyword}%");
            })

            ->addColumn('department', function ($row) {
                return $row->department?->department_name;
            })
            ->filterColumn('department', function ($query, $keyword) {
                $query->whereHas('department', function ($query) use ($keyword) {
                    $query->where('department_name', 'like', "%{$keyword}%");
                });
            })

            ->addColumn('position', function ($row) {
                return $row->position?->position_name;
            })
            ->filterColumn('position', function ($query, $keyword) {
                $query->whereHas('position', function ($query) use ($keyword) {
                    $query->where('position_name', 'like', "%{$keyword}%");
                });
            })

            ->addColumn('time_in', function ($row) {
                return $row->attendances?->min('time');
            })

            ->addColumn('time_out', function ($row) {
                // max time is odd then return "--"
                return ($row->attendance_count % 2 == 0) ? $row->attendances?->max('time') : '--';
            })
            ->addColumn('status', function ($row) {
                if ($row->attendance_count > 0) {
                    return '<span class="badge badge-success-soft sale-badge-ft-13">' . localize('present') . '</span>';
                } else if (count($row->leave) > 0) {
                    return '<span class="badge badge-warning-soft sale-badge-ft-13">' . localize('leave') . '</span>';
                } else {
                    return '<span class="badge badge-danger-soft sale-badge-ft-13">' . localize('absent') . '</span>';
                }
            })
            ->addColumn('late', function ($row) {
                if ($row->attendances) {
                    $start_time = Carbon::parse($row->attendance_time?->start_time);
                    $in_time = $row->attendances?->min('time');

                    if ($start_time && $in_time) {
                        $totalDuration = $start_time->diffInSeconds(Carbon::parse($in_time));
                        if ($start_time->format('H:i:s') < Carbon::parse($in_time)->format('H:i:s')) {
                            return '<span class="badge badge-danger-soft sale-badge-ft-13">Late ' . ' (' . gmdate('H:i:s', $totalDuration) . ') </span>';
                        }
                    }
                }
            });

        return $query->escapeColumns([]);
    }

    /**
     * Get query source of dataTable.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Employee $model)
    {
        $department = $this->request->get('department_id');
        $position   = $this->request->get('position_id');

        $date = $this->request->get('date') ?? Carbon::today()->format('Y-m-d');

        $query = $model->newQuery()

            ->with(['leave' => function ($query) use ($date) {
                $query->where('is_approved', true)->whereDate('leave_approved_start_date', '<=', $date)
                    ->whereDate('leave_approved_end_date', '>=', $date);
            }, 'attendance_time'])

            ->when($department, function ($q) use ($department) {
                return $q->where('department_id', $department);
            })
            ->when($position, function ($q) use ($position) {
                return $q->where('position_id', $position);
            })
            ->withCount([
                'attendance' => function ($query) use ($date) {
                    $query->whereDate('time', '=', $date);
                },
            ])
            ->with(['attendances' => function ($query) use ($date) {
                $query->whereDate('time', '=', $date);
            }]);

        return $query;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $print_admin_and_time = localize('print_date') . Carbon::now()->format('d-m-Y h:i:sa') . ", User: " . auth()->user()->full_name;
        return $this->builder()
            ->setTableId('staff-attendance-table')
            ->setTableAttribute('class', 'table table-hover table-bordered align-middle')
            ->columns($this->getColumns())
            ->serverSide(false)
            ->minifiedAjax()
            ->language([
                'processing' => '<div class="lds-spinner">
                <div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>',
            ])
            ->responsive(true)
            ->selectStyleSingle()
            ->lengthMenu([[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']])
            ->dom("<'row mb-3'<'col-md-4'l><'col-md-4 text-center'B><'col-md-4'f>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>")
            ->footerCallback('function ( row, data, start, end, display ) {
                var api = this.api(), data;
                var present_count = Object.values(data).filter(v => v.attendances.length > 0).length;
                var leave_count = Object.values(data).filter(v => v.leave.length > 0).length;
                var absent_count = data.length - (present_count + leave_count);

                $(api.column( 1).footer() ).html(`<span class="text-end d-block">Present</span>`);
                $(api.column( 2 ).footer() ).html(present_count);
                $(api.column( 3).footer() ).html(`<span class="text-end d-block">Absent</span>`);
                $(api.column( 4 ).footer() ).html(absent_count);
                $(api.column( 5).footer() ).html(`<span class="text-end d-block">Leave</span>`);
                $(api.column( 6 ).footer() ).html(leave_count);
                $(api.column( 7).footer() ).html(`<span class="text-end d-block">Late</span>`);
                $(api.column( 8 ).footer() ).html(Object.values(data).filter(v => v.late).length);
            }')

            ->buttons([

                Button::make('csv')
                    ->className('btn btn-secondary buttons-csv buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-csv"></i> CSV')->exportOptions(['columns' => [0, 1, 2, 3, 4, 5, 6, 7, 8]]),
                Button::make('excel')
                    ->className('btn btn-secondary buttons-excel buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-excel"></i> Excel')
                    ->extend('excelHtml5')->exportOptions(['columns' => [0, 1, 2, 3, 4, 5, 6, 7, 8]]),
                Button::make('print')
                    ->className('btn btn-secondary buttons-print btn-sm prints')
                    ->text('<i class="fa fa-print"></i> Print')->exportOptions(['columns' => [0, 1, 2, 3, 4, 5, 6, 7, 8]])
                    ->footer(true)
                    ->customize(
                        'function(win) {

                            $(win.document.body).css(\'padding\',\'20px\');
                            $(win.document.body).find(\'table\').addClass(\'print-table-border\',\'fs-10\');

                            //date range
                            var date_range = $(\'#date\').val();
                            if(date_range == \'\'){
                                date_range = \'All\';
                            }
                            //remove header
                            $(win.document.body).find(\'h1\').remove();


                           //add print date and time
                            $(win.document.body)
                                .prepend(
                                    \'<p class="fs-10 mb-0 pb-0">' . $print_admin_and_time . '</p>\'+
                                    \'<div class="text-center mt-0 pt-0"><img src="' . logo_64_data() . '" alt="logo" width="135"></div>\'+
                                    \'<p class="text-center fs-12 mt-0 pt-0">' . app_setting()->address . '</p>\'+
                                    \'<h5 class="text-center">Daily Attendance Report</h5>\'+
                                    \'<p class="text-end mb-0 fs-10">Date Range: \'+date_range+\'</p>\'
                                );

                        }'
                    ),
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

            Column::make('staff_name')
                ->title(localize('employee_name'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('employee_id')
                ->title(localize('employee_id'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('department')
                ->title(localize('department'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('position')
                ->title(localize('position'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('time_in')
                ->title(localize('time_in'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('time_out')
                ->title(localize('time_out'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('status')
                ->title(localize('present') . '/' . localize('absent'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('late')
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
        return 'StaffAttendance_' . date('YmdHis');
    }

    //pdf table style
    private function pdfCustomizeTableHeader(): string
    {
        return '
        // Ensure doc.content[5] exists
        if (doc.content[5] && doc.content[5].table && doc.content[5].table.body) {
            // Set row widths
            doc.content[5].table.widths = Array(doc.content[5].table.body[0].length + 1).join("*").split("");
            doc.content[5].table.widths[2] = 100;

            // Header column CSS
            doc.content[5].table.body[0][0].alignment = "center";
            doc.content[5].table.body[0][1].alignment = "center";
            doc.content[5].table.body[0][2].alignment = "center";

            // Change body column CSS
            doc.content[5].table.body.forEach(function(row) {
                row[0].alignment = "center";
                row[1].alignment = "center";
                row[2].alignment = "center";
                row[3].alignment = "center";
                row[4].alignment = "center";
                row[5].alignment = "center";
                row[6].alignment = "center";
                row[7].alignment = "right";
                row[8].alignment = "right";
            });
        }
    ';
    }

    //pdf export design
    private function pdfDesign(): string
    {
        $print_admin_and_time = localize('print_date') . Carbon::now()->format('d-m-Y h:i:sa') . ", User: " . auth()->user()->full_name;
        return '
        // PDF margin
        doc.pageMargins = [15, 5, 15, 10];

        // Admin name top of the page left
        doc.content.splice(0, 0, {
            margin: [0, 0, 0, 0],
            alignment: "left",
            text: [{ text: "' . $print_admin_and_time . '", fontSize: 7, bold: false }]
        });

        // PDF header add logo
        doc.content.splice(1, 0, {
            margin: [0, 0, 0, 0],
            alignment: "center",
            width: 110,
            image: "' . logo_64_data() . '"
        });

        // PDF header add address
        doc.content.splice(2, 0, {
            margin: [0, 0, 0, 10],
            alignment: "center",
            text: [{ text: "' . app_setting()->address . '", fontSize: 8, bold: false }]
        });

        // Page title size
        if (doc.content[3] && doc.content[3].text) {
            doc.content[3].text = "Daily Attendance Report";
        }

        // Date range
        var date_range = $("#sale_date").val();
        if (date_range === "") {
            date_range = "All";
        }
        doc.content.splice(4, 0, {
            margin: [0, -10, 0, 5],
            alignment: "right",
            text: [{ text: "Date Range: " + date_range }]
        });

        if (doc.content[5] && doc.content[5].table && doc.content[5].table.body) {
            // Change table font size & table fill color
            doc.content[5].table.body.forEach(function(row) {
                row.forEach(function(cell) {
                    cell.fontSize = 8;
                    cell.fillColor = "#fff";
                });
            });

            // Change header text color
            doc.content[5].table.body[0].forEach(function(cell) {
                cell.color = "#000";
            });

            // Change footer text color
            doc.content[5].table.body[doc.content[5].table.body.length - 1].forEach(function(cell) {
                cell.color = "#000";
                cell.alignment = "right";
            });
        }

        // Define table layout
        var objLayout = {};
        objLayout["hLineWidth"] = function(i) { return .5; };
        objLayout["vLineWidth"] = function(i) { return .5; };
        objLayout["hLineColor"] = function(i) { return "#000"; };
        objLayout["vLineColor"] = function(i) { return "#000"; };
        objLayout["paddingLeft"] = function(i) { return 4; };
        objLayout["paddingRight"] = function(i) { return 4; };
        if (doc.content[5]) {
            doc.content[5].layout = objLayout;
        }
    ';
    }
}
