<form id="leadForm" action="{{ route('subcodes.update', $code->uuid) }}" method="POST">
    @csrf
    @method('PATCH')
    <div class="modal-body">
        <div class="row">
            <div class="form-group mb-2 mx-0 row">
                <label for="subtype_id" class="col-sm-3 col-form-label ps-0">{{ localize('subtype') }}<span
                        class="text-danger">*</span></label>
                <div class="col-lg-9">
                    <select name="acc_subtype_id" class="form-select select-basic-single">
                        <option value=""> {{ localize('select_subtype') }}</option>
                        @foreach ($subtypes as $key => $type)
                            <option value="{{ $type->id }}"
                                {{ $type->id == $code->acc_subtype_id ? 'selected' : '' }}>{{ $type->subtype_name }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('acc_subtype_id'))
                        <div class="error text-danger text-start">{{ $errors->first('acc_subtype_id') }}
                        </div>
                    @endif
                </div>
            </div>
            @input(['input_name' => 'name', 'value' => $code->name])
            @radio(['input_name' => 'status', 'data_set' => [1 => 'Active', 0 => 'Inactive'], 'value' => $code->status])
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ localize('close') }}</button>
        <button class="btn btn-primary submit_button" id="create_submit">{{ localize('update') }}</button>
    </div>
</form>
