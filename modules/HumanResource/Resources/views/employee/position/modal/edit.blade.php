<!-- Modal -->
<div class="modal fade" id="update-position-{{ $position->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">
                    {{ localize('edit_position') }}
                </h5>
            </div>
            <form id="leadForm" action="{{ route('positions.update', $position->uuid) }}" method="POST"
                enctype="multipart/form-data">
                @method('PATCH')
                @csrf
                <div class="modal-body">
                    <div class="row">
                        @input(['input_name' => 'position_name', 'value' => $position->position_name, 'required' => 'true'])
                        @input(['input_name' => 'position_details', 'value' => $position->position_details, 'required' => 'true'])
                        @radio(['input_name' => 'is_active', 'data_set' => [1 => 'Active', 0 => 'Inactive'], 'value' => $position->is_active, 'required' => 'true'])
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger"
                        data-bs-dismiss="modal">{{ localize('close') }}</button>
                    <button class="btn btn-primary" id="update_submit">{{ localize('save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
