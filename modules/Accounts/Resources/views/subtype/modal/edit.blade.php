<form class="validateEditForm" action="{{ route('subtypes.update', $type->uuid) }}" method="POST">
    @csrf
    @method('PATCH')
    <div class="modal-body">
        <div class="row">
            <div class="form-group mb-2 mx-0 row">
                <label for="subtype_name" class="col-lg-3 col-form-label ps-0 label_subtype_name">
                    {{ localize('account_subtype_name') }}
                    <span class="text-danger">*</span>
                </label>
                <div class="col-lg-9">
                    <input type="text" value="{{ $type->subtype_name }}"
                        placeholder="{{ localize('account_subtype_name') }}" class="form-control" required>
                </div>
            </div>
            @radio(['input_name' => 'status', 'data_set' => [1 => 'Active', 0 => 'Inactive'], 'value' => $type->status])
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ localize('close') }}</button>
        <button class="btn btn-primary submit_button" id="create_submit">{{ localize('update') }}</button>
    </div>
</form>
