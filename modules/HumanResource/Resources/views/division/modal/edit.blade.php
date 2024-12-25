<div class="modal-header">
    <h5 class="modal-title" id="staticBackdropLabel">
        {{ localize('edit_sub_department') }}
    </h5>
</div>
<form id="leadForm" action="{{ route('divisions.update', $division->uuid) }}" method="POST" enctype="multipart/form-data">
    @method('PATCH')
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="form-group mb-2 mx-0 row">
                <label for="division_name"
                    class="col-lg-3 col-form-label ps-0 label_division_name">{{ localize('sub_department') }}<span
                        class="text-danger">*</span></label>
                <div class="col-lg-9">
                    <input type="text" name="division_name" id="" value="{{ $division->department_name }}"
                        placeholder="{{ localize('edit_sub_department') }}" class="form-control">
                </div>
            </div>

            <div class="form-group mb-2 mx-0 row">
                <label for="department" class="col-sm-3 col-form-label ps-0">{{ localize('department') }}<span
                        class="text-danger">*</span></label>
                <div class="col-lg-9">
                    <select name="parent_id" class="form-select" id="departments">
                        <option value="">{{ localize('select_department') }}</option>
                        @foreach ($departments as $key => $department)
                            <option value="{{ $department->id }}"
                                {{ $division->parent_id == $department->id ? 'selected' : '' }}>
                                {{ $department->department_name }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('parent_id'))
                        <div class="error text-danger text-start">{{ $errors->first('parent_id') }}</div>
                    @endif
                </div>
            </div>
            @radio(['input_name' => 'is_active', 'data_set' => [1 => 'Active', 0 => 'Inactive'], 'value' => $division->is_active])
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ localize('close') }}</button>
        <button type="submit" class="btn btn-primary" id="update_submit">{{ localize('save') }}</button>
    </div>
</form>
