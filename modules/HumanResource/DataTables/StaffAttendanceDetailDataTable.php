<?php

namespace Modules\HumanResource\DataTables;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\HumanResource\Entities\Attendance;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class StaffAttendanceDetailDataTable extends DataTable
{
    /**
     * Build DataTable Class
     *
     * @param  mixed  $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $query = (new EloquentDataTable($query))

            ->addIndexColumn()

            ->editColumn('date', function ($row) {
                return Carbon::parse($row->time)->format('Y-m-d');
            })

            ->editColumn('machine_id', function ($row) {
                return $row->machine_id;
            })

            ->editColumn('time', function ($row) {
                return Carbon::parse($row->time)->format('H:i:s');
            })

            ->editColumn('status', function ($row) {
                return $row;
            });

        return $query->escapeColumns([]);
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Attendance $model)
    {
        $date = $this->request->get('date') ?? Carbon::today()->format('Y-m-d');
        $query = $model->newQuery()
            ->whereDate('time', '=', $date)
            ->where('employee_id', 3);

        return $query;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('staff-attendance-detail-table')
            ->setTableAttribute('class', 'table table-hover table-bordered align-middle table-sm')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->language([
                //change preloader icon
                'processing' => '<div class="lds-spinner">
                <div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>',
            ])
            ->responsive(true)
            ->selectStyleSingle()
            ->lengthMenu([[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']])
            ->dom("<'row mb-3'<'col-md-4'l><'col-md-4 text-center'B><'col-md-4'f>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>")

            ->buttons([

                Button::make('csv')
                    ->className('btn btn-secondary buttons-csv buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-csv"></i> CSV')->exportOptions(['columns' => [0, 1, 2, 3, 4]])->footer(true),
                Button::make('excel')
                    ->className('btn btn-secondary buttons-excel buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-excel"></i> Excel')
                    ->extend('excelHtml5')->exportOptions(['columns' => [0, 1, 2, 3, 4]])->footer(true),
                Button::make('pdf')
                    ->className('btn btn-secondary buttons-pdf buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-pdf"></i> PDF')
                    ->extend('pdfHtml5')->exportOptions(['columns' => [0, 1, 2, 3, 4]])->footer(true),
                Button::make('print')
                    ->className('btn btn-secondary buttons-print btn-sm prints')
                    ->text('<i class="fa fa-print"></i> Print')->exportOptions(['columns' => [0, 1, 2, 3, 4]])
                    ->customize(
                        'function(win) {
                            $(win.document.body).find(\'h1\').remove();
                            $(win.document.body).prepend(
                                `<div style="display: flex; justify-content: space-between;margin-bottom: 1rem;">
                                    <div style="font-size: 13px;">
                                    <strong style="color: #000;text-transform: uppercase;font-size: 17px;">Four Nine Gold and Jewelery Company</strong><br>
                                    <div style="color: #000;font-size: 16px;">Mahmoud Saeed Mall - third-floor office no</div>
                                    <span style="color: #000; font-weight: 700">Tax number:&nbsp;</span>210840985100003 <br>
                                    <span style="color: #000; font-weight: 700">Tel:&nbsp;</span>0535001616<br>
                                    <span style="color: #000; font-weight: 700">Fax:&nbsp;</span>0126445678<br>
                                    <span style="color: #000; font-weight: 700">Commercial Reg:&nbsp;</span>4030404864<br>
                                    </div>
                                    <div style="text-align: right;font-size: 16px;">
                                    <div>
                                        <strong style="color: #000;text-transform: uppercase;font-size: 19px;">شركة فور ناين للذهب والمجوهرات</strong><br>
                                        <div style="color: #000;font-size: 17px;">محمود سعيد مول - الدور الثالث مكتب رقم</div>
                                        <span style="color: #000; font-weight: 700">الرقم الضريبي:&nbsp;</span>٢١٠٨٤٠٩٨٥١٠٠٠٠٣ <br>
                                        <span style="color: #000; font-weight: 700">تليفون :&nbsp;</span>٠٥٣٥٠٠١٦١٦<br>
                                        <span style="color: #000; font-weight: 700">فاكس:&nbsp;</span>٠١٢٦٤٤٥٦٧٨<br>
                                        <span style="color: #000; font-weight: 700">السجل التجاري:&nbsp;</span>٤٠٣٠٤٠٤٨٦٤<br>
                                    </div>
                                    </div>
                                </div>
                                <hr style="border-width: 2px; border-color: #000;">`
                            );
                        }'
                    )
                    ->footer(true),
            ]);
    }

    /**
     * Get columns.
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

            Column::make('machine_id')
                ->title(localize('device_id'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('time')
                ->title(localize('punch_time'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('status')
                ->title(localize('status'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

        ];
    }

    /**
     * Get filename for export.
     */
    protected function filename(): string
    {
        return 'StaffAttendanceDetail_' . date('YmdHis');
    }
}
