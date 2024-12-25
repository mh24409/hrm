<!-- Modal -->
<div class="modal fade" id="create-setup_rule" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">
                    {{ localize('new_rule') }}
                </h5>
            </div>
            <form id="setup_rule" class="validateForm" action="{{ route('setup_rules.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group row mb-2">
                        <label for="type" class="col-sm-3 col-form-label">{{ localize('type') }}<span
                                class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <select name="type" id="rule_type" required class="select-basic-single">
                                <option value="">{{ localize('select_type') }}</option>
                                @foreach (config('humanresource.setup_rules') as $key => $rule)
                                    <option value="{{ $rule }}">{{ $key }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('type'))
                                <div class="error text-danger text-start">{{ $errors->first('type') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row mb-2">
                        <label for="name" class="col-sm-3 col-form-label">{{ localize('name') }} <span
                                class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input class="form-control" type="text" name="name" value="" required>
                        </div>
                    </div>
                    <div class="form-group row mb-2 amount">
                        <label for="amount" class="col-sm-3 col-form-label">{{ localize('amount') }} <span
                                class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input class="form-control" type="number" name="amount" value="" required>
                        </div>
                    </div>
                    {{-- Add start_time and end_time fields --}}
                    <div class="form-group row mb-2 start_date">
                        <label for="start_time" class="col-sm-3 col-form-label">{{ localize('start_time') }} <span
                                class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input class="form-control" type="time" name="start_time" value="">
                        </div>
                    </div>
                    <div class="form-group row mb-2 end_date">
                        <label for="end_time" class="col-sm-3 col-form-label">{{ localize('end_time') }} <span
                                class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input class="form-control" type="time" name="end_time" value="">
                        </div>
                    </div>

                    <div class="form-group row mb-2 text-start is_percent">
                        <label for=""
                            class="col-sm-3 col-form-label font-weight-600">{{ localize('is_percent') }}</label>
                        <div class="col-sm-9">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" name="is_percent" id="yes" class="custom-control-input"
                                    value="1">
                                <label class="custom-control-label" for="yes">{{ localize('yes') }}</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" name="is_percent" id="no" class="custom-control-input"
                                    value="0" checked="">
                                <label class="custom-control-label" for="no">{{ localize('no') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-2 text-start effect_on">
                        <label for=""
                            class="col-sm-3 col-form-label font-weight-600">{{ localize('effect_on') }}</label>
                        <div class="col-sm-9">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" name="effect_on" id="on_basic" class="custom-control-input"
                                    value="on_basic" checked="">
                                <label class="custom-control-label" for="on_basic">{{ localize('on_basic') }} </label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" name="effect_on" id="on_gross" class="custom-control-input"
                                    value="on_gross">
                                <label class="custom-control-label" for="on_gross"
                                    for="on_gross">{{ localize('on_gross') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-2 text-start">
                        <label for="is_active"
                            class="col-sm-3 col-form-label font-weight-600">{{ localize('is_active') }}</label>
                        <div class="col-sm-9">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" name="is_active" id="active" class="custom-control-input"
                                    value="1" checked="">
                                <label class="custom-control-label" for="active">{{ localize('active') }}</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="inactive" name="is_active" class="custom-control-input"
                                    value="0">
                                <label class="custom-control-label" for="inactive">{{ localize('inactive') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger"
                        data-bs-dismiss="modal">{{ localize('close') }}</button>
                    <button class="btn btn-success submit_button">{{ localize('save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
