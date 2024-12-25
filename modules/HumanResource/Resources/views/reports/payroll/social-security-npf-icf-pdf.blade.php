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


@if(isset($soc_sec_npf_icf_data) && !empty($soc_sec_npf_icf_data))

<div class="panel panel-bd"> 
    
    <div class="panel-body">

    <div id="printArea">

        <div style="">

            <div class="row mb-10">
                <table class="table" style="width: 100%;">
                    <thead>
                        <tr>
                            <td class="text-left" style="border:none;">
                                <img src="{{$setting->logo}}">
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>

            <div class="row mb-10">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center fs-20">{{localize('Employer_and_Employee_Soc_Sec_NPF_and_ICF_Contrib_Schedule_for')}} {{$sal_month_year}}</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <br>

            <div class="row">
                <table width="99%" class="payrollDatatableReport table table-striped table-bordered table-hover">
                    <thead>
                        <tr style="background-color: #c3bfbf;color: #000;text-align: center;font-weight: bold;font-size: 14px;">
                            <td>{{localize('Employee_ID')}}</td>
                            <td>{{localize('Employee_Name')}}</td>
                            <td>{{localize('social_security_number')}}</td>
                            <td>{{localize('basic_salary')}}</td>
                            <td>{{localize('npf')}} 5%</td>
                            <td>{{localize('npf')}} 10%</td>
                            <td>{{localize('npf')}} 1%</td>
                            <td>{{localize('total_contribution')}}</td>
                        </tr>

                    </thead>
                    <tbody>

                        @php 

                            $total_basic      = 0.0;
                            $total_soc_sec    = 0.0;
                            $total_empr_contr = 0.0;
                            $total_icf_contr  = 0.0;
                            $total_contr      = 0.0;

                            $grand_total_contribution = 0.0;

                        @endphp

                        @foreach ($soc_sec_npf_icf_data as $key => $row)
                            @php

                            $total_contribution = 0.0;
                            $total_contribution = floatval($row->soc_sec_npf_tax) + floatval($row->employer_contribution) + floatval($row->icf_amount);

                            $total_basic         = $total_basic + floatval($row->basic_salary_pro_rated);
                            $total_soc_sec       = $total_soc_sec + floatval($row->soc_sec_npf_tax);
                            $total_empr_contr    = $total_empr_contr + floatval($row->employer_contribution);
                            $total_icf_contr     = $total_icf_contr + floatval($row->icf_amount);

                            $grand_total_contribution =$grand_total_contribution + $total_contribution;

                            @endphp

                        <tr style="text-align: center;">
                            <td style="text-align: center;">{{$row->employee_id}}</td>
                            <th>{{$row->first_name.' '.$row->last_name}}</th>
                            <td>{{$row->social_security_no}}</td>
                            <td>{{$row->basic_salary_pro_rated}}</td>
                            <td>{{$row->soc_sec_npf_tax}}</td>
                            <td>{{$row->employer_contribution}}</td>
                            <td>{{$row->icf_amount}}</td>
                            <td>{{$total_contribution}}</td>
                        </tr>

                         @endforeach

                        <tr style="background-color: #c3bfbf;color: #000;text-align: center;font-weight: bold;font-size: 14px;">
                            <td colspan="3">{{localize('Grand_Total')}}</td>
                            <td>{{$total_basic}}</td>
                            <td>{{$total_soc_sec}}</td>
                            <td>{{$total_empr_contr}}</td>
                            <td>{{$total_icf_contr}}</td>
                            <td>{{$grand_total_contribution}}</td>
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

    @if(!isset($soc_sec_npf_icf_data))

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