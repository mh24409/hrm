<!-- Modal -->
<div class="modal fade" id="create-quarter" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">
                    {{ localize('new_quarter') }}
                </h5>
            </div>
            <form class="validateForm" action="{{ route('quarters.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group mb-2 mx-0 row">
                            <label for="financial_year_id"
                                class="col-sm-3 col-form-label ps-0">{{ localize('financial_year') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="financial_year_id" required class="form-select">
                                    <option value="">Select {{ localize('financial_year') }}</option>
                                    @foreach ($financial_years as $key => $year)
                                        <option value="{{ $year->id }}">{{ $year->financial_year }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('financial_year_id'))
                                    <div class="error text-danger text-start">{{ $errors->first('financial_year_id') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group mb-2 mx-0 row">
                            <label for="quarter" class="col-lg-3 col-form-label ps-0 label_quarter">
                                {{ localize('quarter') }}
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-lg-9">
                                <input type="text" required name="quarter" placeholder=" Quarter "
                                    class="form-control" autocomplete="off">
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
