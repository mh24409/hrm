<!-- Modal -->
<div class="modal fade" id="edit-quarter-{{ $quarter->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">
                    {{ localize('edit_quarter') }}
                </h5>
            </div>
            <form id="leadForm" action="{{ route('quarters.update', $quarter->uuid) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group mb-2 mx-0 row">
                            <label for="financial_year_id"
                                class="col-sm-3 col-form-label ps-0">{{ localize('financial_year') }}
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-lg-9">
                                <select name="financial_year_id" class="form-select">
                                    <option value=""> {{ localize('select_financial_year') }}</option>
                                    @foreach ($financial_years as $key => $year)
                                        <option
                                            value="{{ $year->id }}"{{ $year->id == $quarter->financial_year_id ? 'selected' : '' }}>
                                            {{ $year->financial_year }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('financial_year_id'))
                                    <div class="error text-danger text-start">{{ $errors->first('financial_year_id') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        @input(['input_name' => 'quarter', 'required' => true, 'value' => $quarter->quarter])
                        @input(['input_name' => 'start_date', 'required' => true, 'type' => 'text', 'value' => $quarter->start_date, 'additional_class' => 'date_picker'])
                        @input(['input_name' => 'end_date', 'required' => true, 'type' => 'text', 'value' => $quarter->end_date, 'additional_class' => 'date_picker'])
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
