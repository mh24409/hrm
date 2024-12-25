<div class="modal-header">
    <h5 class="modal-title" id="staticBackdropLabel">
        {{ localize('edit_department') }}
    </h5>
</div>
<form id="leadForm" action="{{ route('departments.update', $department->uuid) }}" method="POST"
    enctype="multipart/form-data">
    @method('PATCH')
    @csrf
    <div class="modal-body">
        <div class="row">
            @input(['input_name' => 'department_name', 'value' => $department->department_name])
            @radio(['input_name' => 'is_active', 'data_set' => [1 => 'Active', 0 => 'Inactive'], 'value' => $department->is_active])
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ localize('close') }}</button>
        <button class="btn btn-primary" id="update_submit">{{ localize('save') }}</button>
    </div>
</form>
