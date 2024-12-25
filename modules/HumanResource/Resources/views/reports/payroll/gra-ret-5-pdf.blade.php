<style type="text/css">
     table.payrollDatatableReport {       
        border-collapse: collapse;
        border: 0;
        text-align: left;
    }
    table.payrollDatatableReport td, table.payrollDatatableReport th {
        padding: 6px 15px;
        text-align: left;
    }
    table.payrollDatatableReport td, table.payrollDatatableReport th {
        border: 1px solid #ededed;
        border-collapse: collapse;
        text-align: left;
    }
table.payrollDatatableReport td.noborder {
    border: none;
    padding-top: 40px;
}
</style>


@if(isset($gra_ret_5_data) && !empty($gra_ret_5_data))

<div class="panel panel-bd"> 
    
    <div class="panel-body">

    <div id="printArea">

        <div style="padding:20px;">

            <div class="row mb-10">
                <table class="table" style="width: 100%;text-align: center;">
                    <thead>
                        <tr>
                            <td class="text-center" style="border:none;text-align: center;">
                                <img src="{{$setting->logo}}">
                            </td>
                        </tr>
                        <tr>
                            <th class="text-center fs-20" style="border:none;text-align: center;">{{localize('EMPLOYERS_SCHEDULE_OF_MONTHLY_PAYE_DEDUCTIONS_FROM_EMPLOYEES_REMUNERATION')}}</th>
                        </tr>
                    </thead>
                </table>
            </div> 

            <br>

            <div class="row mb-10">
                <table class="info-section" cellspacing="0" cellpadding="1" border="0" width="100%">
                    <tbody>
                    <tr>
                            <td width="33%" style="border-top:none;">{{localize('EMPLOYERs_TIN')}}:</td>
                            <td width="33%" style="border-top:none;text-align: center;">1810082912</td>
                            <td width="33%" style="border-top:none;padding-left: 20px;">GRA RET 5 <br>YEAR:{{$year}}</td>
                        </tr>
                        <tr>
                            <td width="33%" style="border-top:none;">{{localize('EMPLOYER_NAME')}}:</td>
                            <td width="33%" style="border-top:none;text-align: center;">{{$setting->title}}</td>
                            <td width="33%" style="border-top:none;"></td>
                        </tr>
                        <tr>
                            <td width="33%" style="border-top:none;">{{localize('ADDRESS')}}:</td>
                            <td width="33%" style="border-top:none;text-align: center;">{{$setting->address}}</td>
                            <td width="33%" style="border-top:none;"></td>
                        </tr>
                        <tr>
                            <td width="33%" style="border-top:none;">{{localize('FOR_MONTH_OF')}}: </td>
                            <td width="33%" style="border-top:none;text-align: center;">{{$month.'-'.$year}}</td>
                            <td width="33%" style="border-top:none;"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <br>

            <div class="row">
                <table width="99%" class="payrollDatatableReport table table-striped table-bordered table-hover" style="text-align: left !important;">
                    <thead>
                        <tr style="background-color: #c3bfbf;color: #000;text-align: center;font-weight: bold;font-size: 14px;">
                            <td width="10%">{{localize('Employee_TIN')}}</td>
                            <td width="10%">{{localize('name_of_employee')}}</td>
                            <td width="20%">{{localize('gross_salary')}}</td>
                            <td width="20%">{{localize('cumulative_salary_up_to_date')}}</td>
                            <td width="20%">{{localize('monthly_tax_deduction')}}</td>
                            <td width="20%">{{localize('cumulative_tax_deduction_up_to_date')}}</td>
                        </tr>

                    </thead>
                    <tbody>

                        @php 

                        $total_gross             = 0.0;
                        $total_cumulative_gross  = 0.0;

                        $total_state_income_tax             = 0.0;
                        $total_cumulative_state_income_tax  = 0.0;

                        @endphp

                        @foreach ($gra_ret_5_data as $key => $row)

                            @php

                                $gra_ret_5_monthly = @$gra_ret_5_report_monthly[$row->employee_id];

                                $gross_salary = isset($gra_ret_5_monthly['gross_salary'])?$gra_ret_5_monthly['gross_salary']:0;
                                $income_tax   = isset($gra_ret_5_monthly['income_tax'])?$gra_ret_5_monthly['income_tax']:0;
                                $tin_no       = isset($gra_ret_5_monthly['tin_no'])?$gra_ret_5_monthly['tin_no']:0;

                                $total_gross             = $total_gross + floatval($gross_salary);
                                $total_state_income_tax  = $total_state_income_tax + floatval($income_tax);

                                $total_cumulative_gross              = $total_cumulative_gross + floatval($row->cumilative_gross_salary);
                                $total_cumulative_state_income_tax  = $total_cumulative_state_income_tax + floatval($row->cumilative_income_tax);

                            @endphp

                            <tr style="text-align: center;">
                                <td style="text-align: center;">{{$tin_no}}</td>
                                <td>{{$row->first_name.' '.$row->last_name}}</td>
                                <td>{{$gross_salary}}</td>
                                <td>{{$row->cumilative_gross_salary}}</td>
                                <td>{{$income_tax}}</td>
                                <td>{{$row->cumilative_income_tax}}</td>
                            </tr>

                         @endforeach

                        <tr style="background-color: #c3bfbf;color: #000;text-align: center;font-weight: bold;font-size: 14px;">
                            <td>Totals</td>
                            <td></td>
                            <td>{{$total_gross}}</td>
                            <td>{{$total_cumulative_gross}}</td>
                            <td>{{$total_state_income_tax}}</td>
                            <td>{{$total_cumulative_state_income_tax}}</td>
                        </tr>
                    </tbody>

                    <tfoot>
                       <tr>
                            <td colspan="7" class="noborder">
                                <table border="0" width="100%" style="padding-top: 30px;border: none !important;">                                                
                                <tr style="height:50px;padding-top: 30px;border-left: none !important;">
                                    <td align="left" class="noborder" width="30%">
                                        <div class="border-top">{{localize('prepared_by')}}: <b>{{$user_info->full_name}}</b></div>
                                    </td>
                                    <td align="left"  class="noborder" width="30%"> <div class="border-top">{{localize('checked_by')}}</div>
                                    </td>  
                                     <td align="left"  class="noborder" width="20%">
                                        <div class="border-top">{{localize('authorised_by')}}</div>
                                    </td>
                                </tr>  
                             </table>  
                            </td>                    
                        </tr> 
                   </tfoot>

                </table>

            </div>

        </div>

    </div>

    </div>
</div>

@else

    @if(!isset($gra_ret_5_data))

        <div class="panel panel-bd"> 
            
            <div class="panel-body">

                <div class="row mb-10 text-center">
                    <h3 style="color:green;">{{localize('please_select_a_date_to_get_the_report')}} !</h3>
                </div>

            </div>

        </div>

    @else

    <div class="panel panel-bd"> 
        
        <div class="panel-body">

            <div class="row mb-10 text-center">
                <h3 style="color:red;">{{localize('no_data_available_please_check_for_other_date')}} !</h3>
            </div>

        </div>

    </div>

    @endif

@endif

<style type="text/css">
    
    .underline {
        border-bottom: 2px solid #000;
    }

    .justify-content-center {
        justify-content: center;
    }

    .d-flex {
        display: flex;
    }

</style>


<script type="text/javascript">
    
function printPageArea(areaID){
    var printContent = document.getElementById(areaID);
    var WinPrint = window.open('', '', 'width=900,height=650');

    var htmlToPrint = '' +
    '<style type="text/css">' +
    'table.payrollDatatableReport {' +
      'border-collapse: collapse;border: 0;text-align: left;' +
      '}'+
    'table.payrollDatatableReport td, table.payrollDatatableReport th {' +
    'padding: 6px 15px;text-align: left;' +
    '}' +
    'table.payrollDatatableReport td, table.payrollDatatableReport th {' +
    'border: 1px solid #ededed;border-collapse: collapse;text-align: left;' +
    '}' +
    'table.payrollDatatableReport td.noborder {' +
    'border: none;padding-top: 40px;' +
    '}' +
    '</style>';

    htmlToPrint += printContent.innerHTML;

    WinPrint.document.write(htmlToPrint);
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
    WinPrint.close();
}

</script>