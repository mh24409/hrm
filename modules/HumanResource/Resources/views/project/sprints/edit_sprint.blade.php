@method('PUT')
<div class="row ps-4 pe-4">

    <div class="col-md-12 mt-3">
        <div class="row">
            <label for="sprint_name"
                class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('sprint_name') }}<span
                    class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-9">
                <input type="text" class="form-control" name="sprint_name"
                    placeholder="{{ localize('sprint_name') }}" value="{{ old('sprint_name') ?? $sprint_data->sprint_name }}"
                    required>
            </div>
        </div>
    </div>

    <div class="col-md-12 mt-3">
        <div class="row">
            <label for="duration"
                class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('duration') }}<span
                    class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-9">
                <input type="number" class="form-control" name="duration"
                    placeholder="{{ localize('duration') }}"
                    value="{{ old('duration') ?? $sprint_data->duration }}"
                    required>
            </div>
        </div>
    </div>

    <div class="col-md-12 mt-3">
        <div class="row">
            <label for="start_date"
                class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('start_date') }}<span
                    class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-9">
                <input type="text" class="form-control date_picker" name="start_date"
                    placeholder="{{ localize('start_date') }}"
                    value="{{ old('start_date') ?? $sprint_data->start_date }}"
                    required>
            </div>
        </div>
    </div>

    <div class="col-md-12 mt-3">
        <div class="row">
            <label for="sprint_goal"
                class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('sprint_goal') }}<span
                    class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-9">
                <textarea  class="form-control" id="sprint_goal" name="sprint_goal" placeholder="{{ localize('sprint_goal') }}" rows ="3" required>{{ old('sprint_goal') ?? $sprint_data->sprint_goal }}</textarea>
            </div>

            @if ($errors->has('sprint_goal'))
                <div class="error text-danger m-2">{{ $errors->first('sprint_goal') }}</div>
            @endif
        </div>
    </div>

    @if($close_open)
    <div class="col-md-12 mt-3">
        <div class="row">
            <label for="sprint_status"
                class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('status') }}<span
                    class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-9">
                <select name="sprint_status" class="form-control select-basic-single" id="sprint_status" onchange="change_sprint_status(this,<?php echo $sprint_id;?>)">
                        <option value=""> {{localize('select_status')}}</option>
                        <option value="1">{{localize('close')}}</option>
                </select>
            </div>

            @if ($errors->has('sprint_status'))
                <div class="error text-danger m-2">{{ $errors->first('sprint_status') }}</div>
            @endif
        </div>
    </div>
    @endif

</div>
