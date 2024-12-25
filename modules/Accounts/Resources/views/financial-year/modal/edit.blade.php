<!-- Modal -->
<div class="modal fade" id="edit-financial-year-{{ $financialYear->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">
                    Edit Financial Year
                </h5>
            </div>
            <form id="leadForm" action="{{ route('financial-years.update', $financialYear->uuid) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="row">
                        @input(['input_name' => 'financial_year', 'required' => true, 'value' => $financialYear->financial_year])
                        @input(['input_name' => 'start_date', 'required' => true, 'type' => 'text', 'value' => $financialYear->start_date, 'additional_class' => 'date_picker'])
                        @input(['input_name' => 'end_date', 'required' => true, 'type' => 'text', 'value' => $financialYear->end_date, 'additional_class' => 'date_picker'])
                        @radio(['input_name' => 'status', 'required' => true, 'data_set' => [1 => 'Active', 0 => 'Inactive'], 'value' => $financialYear->status])
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
