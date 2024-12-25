<!-- Modal -->
<div class="modal fade" id="create-subcode" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">
                    {{ localize('new_sub_account') }}
                </h5>
            </div>
            <form class="validateForm" action="{{ route('subcodes.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group mb-2 mx-0 row">
                            <label for="subtype_id" class="col-sm-3 col-form-label ps-0">{{ localize('subtype') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="acc_subtype_id" required class="form-select select-basic-single">
                                    <option value="">{{ localize('select_subtype') }}</option>
                                    @foreach ($subtypes as $key => $type)
                                        <option value="{{ $type->id }}">{{ $type->subtype_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('acc_subtype_id'))
                                    <div class="error text-danger text-start">{{ $errors->first('acc_subtype_id') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group mb-2 mx-0 row">
                            <label for="name" class="col-lg-3 col-form-label ps-0 label_name">
                                {{ localize('name') }}
                                <span class="text-danger">*</span>
                            </label>

                            <div class="col-lg-9">
                                <input type="text" required name="name" placeholder="{{ localize('name') }}"
                                    class="form-control" autocomplete="off">
                            </div>
                        </div>
                        @radio(['input_name' => 'status', 'data_set' => [1 => 'Active', 0 => 'Inactive'], 'value' => 1])
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger"
                        data-bs-dismiss="modal">{{ localize('close') }}</button>
                    <button class="btn btn-primary submit_button" id="create_submit">{{ localize('save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
