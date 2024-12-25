<!-- Modal -->
<div class="modal fade" id="editBank{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">
                    {{ localize('edit_bank') }}
                </h5>
            </div>
            <form id="leadForm" action="{{ route('bank.update', $data->uuid) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mt-3">
                            <div class="row">
                                <label for="bank_name"
                                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('bank_name') }}<span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-9 col-md-12 col-xl-9">
                                    <input type="text" class="form-control" id="bank_name" name="bank_name"
                                        placeholder="{{ localize('bank_name') }}"
                                        value="{{ old('bank_name') ?? $data->bank_name }}" required>
                                </div>

                                @if ($errors->has('bank_name'))
                                    <div class="error text-danger m-2">{{ $errors->first('bank_name') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <div class="row">
                                <label for="branch_name"
                                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('branch_name') }}<span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-9 col-md-12 col-xl-9">
                                    <input type="text" class="form-control" id="branch_name" name="branch_name"
                                        placeholder="{{ localize('branch_name') }}"
                                        value="{{ old('branch_name') ?? $data->branch_name }}" required>
                                </div>

                                @if ($errors->has('branch_name'))
                                    <div class="error text-danger m-2">{{ $errors->first('branch_name') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <div class="row">
                                <label for="account_name"
                                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('account_name') }}<span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-9 col-md-12 col-xl-9">
                                    <input type="text" class="form-control" id="account_name" name="account_name"
                                        placeholder="{{ localize('account_name') }}"
                                        value="{{ old('account_name') ?? $data->account_name }}" required>
                                </div>

                                @if ($errors->has('account_name'))
                                    <div class="error text-danger m-2">{{ $errors->first('account_name') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <div class="row">
                                <label for="account_number"
                                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('account_number') }}<span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-9 col-md-12 col-xl-9">
                                    <input type="text" class="form-control" id="account_number" name="account_number"
                                        placeholder="{{ localize('account_number') }}"
                                        value="{{ old('account_number') ?? $data->account_number }}" required>
                                </div>

                                @if ($errors->has('account_number'))
                                    <div class="error text-danger m-2">{{ $errors->first('account_number') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger"
                        data-bs-dismiss="modal">{{ localize('close') }}</button>
                    <button class="btn btn-primary submit_button" id="create_submit">{{ localize('update') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
