<!-- Modal -->
<div class="modal fade" id="create-division" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">
                    {{ localize('new_sub_department') }}
                </h5>
            </div>
            <form class="validateForm" action="{{ route('divisions.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group mb-2 mx-0 row">
                            <label for="division_name" class="col-lg-3 col-form-label ps-0">
                                {{ localize('sub_department_name') }}
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-lg-9">
                                <input type="text" name="division_name"
                                    placeholder="{{ localize('sub_department_name') }}" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group mb-2 mx-0 row">
                            <label for="department" class="col-sm-3 col-form-label ps-0">{{ localize('department') }}
                                <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="parent_id" required class="form-select">
                                    <option value="">{{ localize('select_department') }}</option>
                                    @foreach ($departments as $key => $department)
                                        <option value="{{ $department->id }}">{{ $department->department_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('parent_id'))
                                    <div class="error text-danger text-start">{{ $errors->first('parent_id') }}</div>
                                @endif
                            </div>
                        </div>
                        @radio(['input_name' => 'is_active', 'data_set' => [1 => 'Active', 0 => 'Inactive'], 'value' => 1])
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger"
                        data-bs-dismiss="modal">{{ localize('close') }}</button>
                    <button type="submit" class="btn btn-primary submit_button"
                        id="create_submit">{{ localize('save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
