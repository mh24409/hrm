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


@if(isset($iicf3_contribution_data) && !empty($iicf3_contribution_data))

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
                            <th class="text-center fs-20" style="border:none;text-align: center;">{{localize('social_security_and_housing_finance_corporation_remittance_adivce_form')}}</th>
                        </tr>
                    </thead>
                </table>
            </div> 

            <br>

            <div class="row mb-10">
                <table class="info-section" cellspacing="0" cellpadding="1" border="0" width="100%">
                    <tbody>
                        <tr>
                            <td width="33%" style="border-top:none;">{{localize('EMPLOYER_REGISTRATION_NUMBER')}}:</td>
                            <td width="33%" style="border-top:none;text-align: center;">5968</td>
                            <td width="33%" style="border-top:none;padding-left: 20px;">IICF 3 <br>YEAR:{{$year}}</td>
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
                            <td width="33%" style="border-top:none;">{{localize('CONTRIBUTION_FOR_MONTH_OF')}}: </td>
                            <td width="33%" style="border-top:none;text-align: center;">{{$month.'-'.$year}}</td>
                            <td width="33%" style="border-top:none;"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <br>

            <div class="row">
                <table  width="99%" class="payrollDatatableReport table table-striped table-bordered table-hover">
                    <thead>
                        <tr style="background-color: #c3bfbf;color: #000;text-align: center;font-weight: bold;font-size: 14px;">
                            <td>{{localize('employee')}}</td>
                            <td>{{localize('employee')}}</td>
                            <td>{{localize('basic')}}</td>
                            <td>1% {{localize('contr')}}</td>
                            <td>{{localize('employees_in')}}</td>
                            <td>{{localize('remarks')}}</td>
                        </tr>

                        <tr style="background-color: #c3bfbf;color: #000;text-align: center;font-weight: bold;font-size: 14px;">
                            <td>S{{localize('sos_sec')}}.#</td>
                            <td>{{localize('full_name')}}</td>
                            <td>{{localize('pay')}}</td>
                            <td>{{localize('max')}} D15</td>
                            <td colspan="2">{{localize('employer_s_s_no')}}.</td>
                        </tr>

                    </thead>
                    <tbody>

                        @php 

                        $total_basic             = 0.0;
                        $total_max_d15           = 0.0;

                        @endphp

                        @foreach ($iicf3_contribution_data as $key => $row)
                            @php 
                                $total_basic              = $total_basic + floatval($row->basic_salary_pro_rated);
                                $total_max_d15            = $total_max_d15 + floatval($row->icf_amount);

                            @endphp 

                            <tr style="text-align: center;">
                                <td style="text-align: center;">{{$row->social_security_no}}</td>
                                <td>{{$row->first_name.' '.$row->last_name}}</td>
                                <td>{{$row->basic_salary_pro_rated}}</td>
                                <td>{{$row->icf_amount}}</td>
                                <td></td>
                                <td></td>
                            </tr>

                        @endforeach

                        <tr style="background-color: #c3bfbf;color: #000;text-align: center;font-weight: bold;font-size: 14px;">
                            <td></td>
                            <td></td>
                            <td>{{$total_basic}}</td>
                            <td>{{$total_max_d15}}</td>
                            <td></td>
                            <td></td>
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

    @if(!isset($iicf3_contribution_data))

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