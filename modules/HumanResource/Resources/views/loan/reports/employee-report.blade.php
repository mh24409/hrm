<div class="table-responsive">
    <table class="table display table-bordered table-striped table-hover" id="staff-attendance-table">
        <div>
            <h4 class="text-center">{{ $employee?->full_name }}</h4>
            <p class="text-center">{{ localize('loan_history') }} ( From {{ $fromDate }} To {{ $toDate }} )
            </p>
            <div class="d-flex justify-content-between">
                <div>
                    <span style="color: #000; font-weight: 700">{{ localize('position_name') }}
                        :&nbsp;</span>{{ $employee?->position?->position_name }}<br>
                    <span style="color: #000; font-weight: 700">{{ localize('department_name') }}
                        :&nbsp;</span>{{ $employee?->department?->department_name }}<br>
                    <span style="color: #000; font-weight: 700">{{ localize('employee_id') }} :&nbsp;</span>
                    {{ $employee?->employee_id }}
                </div>

            </div>
        </div>
        <br>

        <thead>
            <tr>
                <th width="5%">{{ localize('sl') }}</th>
                <th width="10%">{{ localize('loan_code') }}</th>
                <th width="10%">{{ localize('disburse_amount') }}</th>
                <th width="10%">{{ localize('disburse_date') }}</th>
                <th width="10%">{{ localize('due_for_r_l_d') }}</th>
                <th width="10%">{{ localize('repay_month') }}</th>
                <th width="10%">{{ localize('repay_amount') }}</th>
                <th width="10%">{{ localize('out_standing') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($loans as $key => $loan)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $loan->loan_no }}</td>
                    <td>{{ $loan->amount }}</td>
                    <td>{{ $loan->approved_date }}</td>
                    <td></td>
                    <td>{{ \Carbon\Carbon::parse($loan->repayment_start_date)->format('F') }}</td>
                    <td>{{ $loan->repayment_amount }}</td>
                    <td></td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">{{ localize('no_data_found') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
