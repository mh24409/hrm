@extends('backend.layouts.app')
@section('title', localize('loan'))
@section('content')
    @include('humanresource::loan_header')
    @include('backend.layouts.common.validation')
    <div class="card mb-4 fixed-tab-body">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('loan_disburse_list') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        @can('create_loan')
                            <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#create-loan"><i
                                    class="fa fa-plus-circle"></i>&nbsp;{{ localize('add_loan') }}</a>
                            @include('humanresource::loan.modal.create')
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            {{ $dataTable->table() }}
        </div>
    </div>
@endsection
@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script src="{{ module_asset('HumanResource/js/loan.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#reset').on('click', function(e) {
                e.preventDefault();
                $('#employee_id').val('').trigger('change');
                $('#loan-amount').val('').trigger('change');
                $('#approved_date').val('').trigger('change');
                $('#repayment-start-date').val('').trigger('change');
                $('#interest-rate').val('').trigger('change');
                $('#installment-period').val('').trigger('change');
                $('#repayment-amount').val('').trigger('change');
                $('#installment-amount').val('').trigger('change');
            });
        });
    </script>
@endpush
