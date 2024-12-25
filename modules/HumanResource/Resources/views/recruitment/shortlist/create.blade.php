<div class="row">
    <div class="col-md-12 mt-3">
        <div class="row">
            <label for="candidate_id" class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('candidate_name') }}
                <span class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-9">
                <select name="candidate_id" id="candidate_id" class="form-control select-basic-single" required>
                    <option value="" selected disabled>{{ localize('select_candidate') }}</option>
                    @foreach ($candidates as $candidate)
                        <option value="{{ $candidate->id }}">{{ $candidate->first_name.' '.$candidate->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            @if ($errors->has('candidate_id'))
                <div class="error text-danger m-2">{{ $errors->first('candidate_id') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-12 mt-3">
        <div class="row">
            <label for="position_id" class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('job_position') }}
                <span class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-9">
                <select name="position_id" id="position_id" class="form-control select-basic-single" required>
                    @foreach ($positions as $position)
                        <option value="{{ $position->id }}">{{ $position->position_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            @if ($errors->has('position_id'))
                <div class="error text-danger m-2">{{ $errors->first('position_id') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-12 mt-3">
        <div class="row">
            <label for="shortlist_date" class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('shortlist_date') }}
                <span class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-9">
                <input type="text" name="shortlist_date" id="shortlist_date"  class="form-control date_picker" placeholder="{{ localize('shortlist_date') }}"
                    value="{{ old('shortlist_date') }}" required>
            </div>

            @if ($errors->has('shortlist_date'))
                <div class="error text-danger m-2">{{ $errors->first('shortlist_date') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-12 mt-3">
        <div class="row">
            <label for="interview_date" class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('interview_date') }}
                <span class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-9">
                <input type="text" name="interview_date" id="interview_date"  class="form-control date_picker" placeholder="{{ localize('interview_date') }}"
                    value="{{ old('interview_date') }}" required>
            </div>

            @if ($errors->has('interview_date'))
                <div class="error text-danger m-2">{{ $errors->first('interview_date') }}</div>
            @endif
        </div>
    </div>
</div>
                