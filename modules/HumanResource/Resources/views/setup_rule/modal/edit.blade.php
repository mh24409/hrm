<!-- Modal -->
<div class="modal fade" id="update-setup_rule-{{ $setup_rule->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">
                    {{ localize('edit_setup_rule') }}
                </h5>
            </div>
            <form class="validateEditForm" action="{{ route('setup_rules.update', $setup_rule->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group row mb-2">
                        <label for="type" class="col-sm-3 col-form-label">{{ localize('type') }}<span
                                class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" name="type" value="{{ $setup_rule->type }}"
                                readonly required>
                        </div>
                    </div>

                    <div class="form-group row mb-2">
                        <label for="name" class="col-sm-3 col-form-label">{{ localize('name') }} <span
                                class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input class="form-control" type="text" name="name" value="{{ $setup_rule->name }}"
                                required>
                        </div>
                    </div>
                    @if ($setup_rule->type == 'time')
                        <div class="form-group row mb-2">
                            <label for="start_time" class="col-sm-3 col-form-label">{{ localize('start_time') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input class="form-control" type="time" name="start_time"
                                    value="{{ $setup_rule->start_time }}" required>
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label for="end_time" class="col-sm-3 col-form-label">{{ localize('end_time') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input class="form-control" type="time" name="end_time"
                                    value="{{ $setup_rule->end_time }}" required>
                            </div>
                        </div>
                    @else
                        <div class="form-group row mb-2">
                            <label for="amount" class="col-sm-3 col-form-label">{{ localize('amount') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input class="form-control" type="number" name="amount"
                                    value="{{ $setup_rule->amount }}" required>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label for=""
                                class="col-sm-3 col-form-label font-weight-600">{{ localize('is_percent') }}</label>
                            <div class="col-sm-9">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="is_percent" class="custom-control-input" value="1"
                                        {{ $setup_rule->is_percent == 1 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="yes">{{ localize('yes') }}</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="is_percent" class="custom-control-input" value="0"
                                        {{ $setup_rule->is_percent == 0 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="no">{{ localize('no') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label for=""
                                class="col-sm-3 col-form-label font-weight-600">{{ localize('effect_on') }}</label>
                            <div class="col-sm-9">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="effect_on" class="custom-control-input" value="on_basic"
                                        {{ $setup_rule->effect_on == 1 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="on_basic">{{ localize('on_basic') }}
                                    </label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" name="effect_on" class="custom-control-input" value="on_gross"
                                        {{ $setup_rule->effect_on == 0 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="on_gross"
                                        for="on_gross">{{ localize('on_gross') }}</label>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="form-group row mb-2">
                        <label for="is_active"
                            class="col-sm-3 col-form-label font-weight-600">{{ localize('is_active') }}</label>
                        <div class="col-sm-9">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" name="is_active" class="custom-control-input" value="1"
                                    {{ $setup_rule->is_active == 1 ? 'checked' : '' }}>
                                <label class="custom-control-label" for="active">{{ localize('active') }}</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" name="is_active" class="custom-control-input" value="0"
                                    {{ $setup_rule->is_active == 0 ? 'checked' : '' }}>
                                <label class="custom-control-label" for="inactive">{{ localize('inactive') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger"
                        data-bs-dismiss="modal">{{ localize('close') }}</button>
                    <button class="btn btn-primary submit_button">{{ localize('update') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
