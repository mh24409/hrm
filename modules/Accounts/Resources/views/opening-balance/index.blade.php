@extends('backend.layouts.app')
@section('title', localize('opening_balance_list'))
@push('css')
@endpush
@section('content')
    @include('backend.layouts.common.validation')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('opening_balance_list') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        @can('create_opening_balance')
                            <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#import-balanace-sheet"><i
                                    class="fa fa-plus-circle"></i> {{ localize('bulk_upload') }}</button>
                            <a href="{{ route('opening-balances.create') }}" class="btn btn-success btn-sm"><i
                                    class="fa fa-plus-circle"></i>&nbsp;{{ localize('add_new_opening_balance') }}</a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table_customize">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="import-balanace-sheet" tabindex="-1" aria-labelledby="import-balanace-sheetLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="leadForm" action="{{ route('import.opening.balance') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h6 class="modal-title" id="import-balanace-sheetLabel">{{ localize('bulk_upload') }}</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <span class="text-warning pt-3">{{ localize('downloaded_instruction') }}</span><br>
                        <span class="text-info pt-4">
                            The correct column order is (Financial Year,Open Date, Account Code, Account Name, Debit,
                            Credit).
                        </span><br>
                        <a class="btn btn-info mt-4" href="{{ asset('assets/import/opening_balance.xlsx') }}"
                            target="_blank">
                            <i class="fa fa-download"></i>
                            {{ localize('download_sample_file') }}
                        </a>
                        <div class="row mb-2 mt-4">
                            <div class="col-12">
                                <div class="form-group row">
                                    <label for=""
                                        class="col-md-4 col-12 col-form-label fw-semibold">{{ localize('type_csv_xls_xlsx') }}</label>
                                    <div class="col-md-8">
                                        <div class="input-group mb-3">
                                            <input type="file" class="form-control" name="upload_csv_file"
                                                id="upload_csv_file" required>
                                        </div>

                                        @if ($errors->has('upload_csv_file'))
                                            <div class="error text-danger m-2">
                                                {{ $errors->first('upload_csv_file') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger"
                            data-bs-dismiss="modal">{{ localize('close') }}</button>
                        <button type="button" type="submit" id="uploadOb"
                            class="btn btn-success">{{ localize('upload') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script src="{{ module_asset('Accounts/js/opening-balance.js') }}"></script>
@endpush
