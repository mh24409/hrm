<!-- Modal -->
<div class="modal fade" id="create-financial-year" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">
                    {{ localize('new_financial_year') }}
                </h5>
            </div>
            <form class="validateForm" action="{{ route('financial-years.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group mb-2 mx-0 row">
                            <label for="financial_year" class="col-lg-3 col-form-label ps-0 label_financial_year">
                                {{ localize('financial_year') }}
                                <span class="text-danger">*</span>
                            </label>

                            <div class="col-lg-9">
                                <input type="text" required name="financial_year"
                                    placeholder=" {{ localize('financial_year') }}" class="form-control"
                                    autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group mb-2 mx-0 row">
                            <label for="start_date" class="col-lg-3 col-form-label ps-0 label_start_date">
                                {{ localize('start_date') }}
                                <span class="text-danger">*</span>
                            </label>

                            <div class="col-lg-9">
                                <input type="text" required name="start_date"
                                    placeholder="{{ localize('start_date') }}" class="form-control date_picker"
                                    autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group mb-2 mx-0 row">
                            <label for="end_date" class="col-lg-3 col-form-label ps-0 label_end_date">
                                {{ localize('end_date') }}
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-lg-9">
                                <input type="text" required name="end_date" placeholder="{{ localize('end_date') }}"
                                    class="form-control date_picker" autocomplete="off">
                            </div>
                        </div>
                        @radio(['input_name' => 'status', 'required' => true, 'data_set' => [1 => 'Active', 0 => 'Inactive'], 'value' => 1])
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
