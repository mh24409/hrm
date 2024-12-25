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


@if(isset($tax_data) && !empty($tax_data))

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
                            <td width="33%" style="border-top:none;padding-left: 20px;">NPF 3 <br>YEAR:{{$year}}</td>
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
                <table width="99%" class="payrollDatatableReport table table-striped table-bordered table-hover">
                    <thead>
                        <tr style="background-color: #c3bfbf;color: #000;text-align: center;font-weight: bold;font-size: 14px;">
                            <td>{{localize('Employee')}}</td>
                            <td>{{localize('Employee')}}</td>
                            <td>{{localize('Basic')}}</td>
                            <td>5%</td>
                            <td>10%</td>
                            <td>15%</td>
                            <td>{{localize('NEW_EMPLOYEES_INDICATE')}}</td>
                        </tr>

                        <tr style="background-color: #c3bfbf;color: #000;text-align: center;font-weight: bold;font-size: 14px;">
                            <td>{{localize('Soc_Sec')}}.#</td>
                            <td>{{localize('Full_Name')}}</td>
                            <td>{{localize('Pay')}}</td>
                            <td>{{localize('Contr')}}.</td>
                            <td>{{localize('Contr')}}.</td>
                            <td>{{localize('Contr')}}.</td>
                            <td>{{localize('LAST_EMPLOYER_S_S_NO')}}.</td>
                        </tr>

                    </thead>
                    <tbody>

                        @php 

                        $total_basic             = 0.0;
                        $total_soc_sec           = 0.0;
                        $total_empr_contr        = 0.0;
                        $total_soc_sec_and_contr = 0.0;

                        @endphp

                        @foreach ($tax_data as $key => $row)
                            @php 
                                $total_contribution = 0.0;
                                $total_contribution = floatval($row->soc_sec_npf_tax) + floatval($row->employer_contribution);

                                $total_basic              = $total_basic + floatval($row->basic_salary_pro_rated);
                                $total_soc_sec            = $total_soc_sec + floatval($row->soc_sec_npf_tax);
                                $total_empr_contr         = $total_empr_contr + floatval($row->employer_contribution);
                                $total_soc_sec_and_contr  = $total_soc_sec_and_contr + floatval($total_contribution);
                            @endphp

                            <tr style="text-align: center;">
                                <th style="text-align: left;"><{{$row->social_security_no}}</th>
                                <td>{{$row->first_name.' '.$row->last_name}}</td>
                                <td>{{$row->basic_salary_pro_rated}}</td>
                                <td>{{$row->soc_sec_npf_tax}}</td>
                                <td>{{$row->employer_contribution}}</td>
                                <td>{{$total_contribution}}</td>
                                <td></td>
                            </tr>

                         @endforeach

                            <tr style="background-color: #c3bfbf;color: #000;text-align: center;font-weight: bold;font-size: 14px;">
                                <th></th>
                                <td></td>
                                <td>{{$total_basic}}</td>
                                <td>{{$total_soc_sec}}</td>
                                <td>{{$total_empr_contr}}</td>
                                <td>{{$total_soc_sec_and_contr}}</td>
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

    @if(!isset($tax_data))

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