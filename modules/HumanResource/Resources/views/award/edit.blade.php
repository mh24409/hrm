@method('PUT')
<div class="row">
    <div class="col-md-12 mt-3">
        <div class="row">
            <label for="name"
                class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('award_name') }}
                <span class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-9">
                <input type="text" class="form-control" id="name" name="name"
                    placeholder="{{ localize('award_name') }}" value="{{ $award->name }}" required>
            </div>

            @if ($errors->has('name'))
                <div class="error text-danger m-2">{{ $errors->first('name') }}</div>
            @endif
        </div>
    </div>
    <div class="col-md-12 mt-3">
        <div class="row">
            <label for="description"
                class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('award_description') }}</label>
            <div class="col-sm-9 col-md-12 col-xl-9">
                <textarea class="form-control" name="description" id="description" placeholder="{{ localize('award_description') }}">{{ $award->description }}</textarea>
            </div>

            @if ($errors->has('description'))
                <div class="error text-danger m-2">{{ $errors->first('description') }}</div>
            @endif
        </div>
    </div>
    <div class="col-md-12 mt-3">
        <div class="row">
            <label for="gift"
                class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('gift_item') }}
                <span class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-9">
                <input type="text" class="form-control" id="gift" name="gift"
                    placeholder="{{ localize('gift_item') }}" value="{{ $award->gift }}" required>
            </div>

            @if ($errors->has('gift'))
                <div class="error text-danger m-2">{{ $errors->first('gift') }}</div>
            @endif
        </div>
    </div>
    <div class="col-md-12 mt-3">
        <div class="row">
            <label for="date" class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('date') }}
                <span class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-9">
                <input type="text" name="date" id="date" class="form-control date_picker"
                    placeholder="{{ localize('date') }}" value="{{ $award->date }}" required>
            </div>

            @if ($errors->has('date'))
                <div class="error text-danger m-2">{{ $errors->first('date') }}</div>
            @endif
        </div>
    </div>
    <div class="col-md-12 mt-3">
        <div class="row">
            <label for="employee_id"
                class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('employee_name') }}
                <span class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-9">
                <select name="employee_id" id="employee_id" class="select-basic-single" required>
                    <option value="" selected disabled>{{ localize('employee_name') }}</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}" @if ($employee->id == $award->employee_id) selected @endif>
                            {{ $employee->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            @if ($errors->has('employee_id'))
                <div class="error text-danger m-2">{{ $errors->first('employee_id') }}</div>
            @endif
        </div>
    </div>
    <div class="col-md-12 mt-3">
        <div class="row">
            <label for="awarded_by"
                class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('award_by') }}
                <span class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-9">
                <input type="text" class="form-control" name="awarded_by" id="awarded_by"
                    placeholder="{{ localize('award_by') }}" value="{{ $award->awarded_by }}" required>
            </div>

            @if ($errors->has('awarded_by'))
                <div class="error text-danger m-2">{{ $errors->first('awarded_by') }}</div>
            @endif
        </div>
    </div>
</div>
