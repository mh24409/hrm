<!-- Modal -->
<div class="modal fade" id="update-department-{{ $department->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="subDepartment">
                    {{ localize('edit_sub_department') }}
                </h5>
            </div>
            <form id="leadForm" action="{{ route('departments.update', $department->id) }}" method="POST">
                @method('PATCH')
                @csrf
                <div class="modal-body">
                    <div class="row">
                        @input(['input_name' => 'department_name', 'value' => $department->department_name])
                        <div class="form-group mb-2 mx-0 row">
                            <label for="department" class="col-sm-3 col-form-label ps-0">
                                {{ localize('parent_department') }}</label>
                            <div class="col-lg-9">
                                <select name="parent_id" class="form-select">
                                    <option value="">{{ localize('select_parent_department') }}</option>
                                    @foreach ($all_departments as $key => $pdepartment)
                                        <option value="{{ $pdepartment->id }}"
                                            {{ $pdepartment->id == $department->parent_id ? 'selected' : '' }}>
                                            {{ $pdepartment->department_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('parent_id'))
                                    <div class="error text-danger text-start">{{ $errors->first('parent_id') }}</div>
                                @endif
                            </div>
                        </div>
                        @radio(['input_name' => 'is_active', 'data_set' => [1 => 'Active', 0 => 'Inactive'], 'value' => $department->is_active])
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
