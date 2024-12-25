<!-- Modal -->
<div class="modal fade" id="create-subtype" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">
                    New SubType
                </h5>
            </div>
            <form class="validateForm" action="{{ route('subtypes.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group mb-2 mx-0 row">
                            <label for="subtype_name" class="col-lg-3 col-form-label ps-0 label_subtype_name">
                                Account Subtype Name
                                <span class="text-danger">*</span>
                            </label>

                            <div class="col-lg-9">
                                <input type="text" required name="subtype_name" placeholder=" Account Subtype Name "
                                    class="form-control  " aria-describedby="emailHelp" autocomplete="off">
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
