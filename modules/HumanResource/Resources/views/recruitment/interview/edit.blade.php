@method('PUT')
<div class="row ps-4 pe-4">
    <div class="col-md-6 mt-3">
        <div class="row">
            <label for="candidate_id" class="col-form-label col-sm-3 col-md-12 col-xl-4 fw-semibold">{{ localize('candidate_name') }}
                <span class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-8">
                <select name="candidate_id" id="candidate_id" class="form-control select-basic-single" required>
                    <option value="" selected disabled>{{ localize('select_candidate') }}</option>
                    @foreach ($candidates as $candidate)
                        <option value="{{ $candidate->id }}" @if ($candidate->id == $interview->candidate_id) selected @endif >{{ $candidate->first_name.' '.$candidate->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @if ($errors->has('candidate_id'))
                <div class="error text-danger m-2">{{ $errors->first('candidate_id') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-6 mt-3">
        <div class="row">
            <label for="position_name" class="col-form-label col-sm-3 col-md-12 col-xl-4 fw-semibold" style="text-align: right">{{ localize('job_position') }}
                <span class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-8">
                <input type="text" name="position_name" id="position_name"  class="form-control" placeholder="{{ localize('job_position') }}"
                    value="{{ $position->position_name }}" readonly required>
                <input type="hidden" name="position_id" id="position_id" value="{{ $position->id }}">
            </div>
            @if ($errors->has('position_name'))
                <div class="error text-danger m-2">{{ $errors->first('position_name') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-6 mt-3">
        <div class="row">
            <label for="interview_date" class="col-form-label col-sm-3 col-md-12 col-xl-4 fw-semibold">{{ localize('interview_date') }}
                <span class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-8">
                <input type="text" name="interview_date" id="interview_date"  class="form-control date_picker" placeholder="{{ localize('interview_date') }}"
                    value="{{ $interview->interview_date }}" required>
            </div>

            @if ($errors->has('interview_date'))
                <div class="error text-danger m-2">{{ $errors->first('interview_date') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-6 mt-3">
        <div class="row">
            <label for="interviewer" class="col-form-label col-sm-3 col-md-12 col-xl-4 fw-semibold" style="text-align: right">{{ localize('interviewer') }}
                <span class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-8">
                <input type="text" name="interviewer" id="interviewer"  class="form-control" placeholder="{{ localize('interviewer') }}"
                    value="{{ $interview->interviewer }}" required>
            </div>
            @if ($errors->has('interviewer'))
                <div class="error text-danger m-2">{{ $errors->first('interviewer') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-6 mt-3">
        <div class="row">
            <label for="interview_marks" class="col-form-label col-sm-3 col-md-12 col-xl-4 fw-semibold">{{ localize('viva_marks') }}
                <span class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-8">
                <input type="number" name="interview_marks" id="interview_marks"  class="form-control" step="any" placeholder="{{ localize('interview_marks') }}"
                    value="{{ $interview->interview_marks }}" required>
            </div>

            @if ($errors->has('interview_marks'))
                <div class="error text-danger m-2">{{ $errors->first('interview_marks') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-6 mt-3">
        <div class="row">
            <label for="written_marks" class="col-form-label col-sm-3 col-md-12 col-xl-4 fw-semibold" style="text-align: right">{{ localize('written_total_marks ') }}
                <span class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-8">
                <input type="number" name="written_marks" id="written_marks"  class="form-control" step="any" placeholder="{{ localize('written_total_marks') }}"
                    value="{{ $interview->written_marks }}" required>
            </div>
            @if ($errors->has('written_marks'))
                <div class="error text-danger m-2">{{ $errors->first('written_marks') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-6 mt-3">
        <div class="row">
            <label for="mcq_marks" class="col-form-label col-sm-3 col-md-12 col-xl-4 fw-semibold">{{ localize('mcq_total_marks') }}
                <span class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-8">
                <input type="number" name="mcq_marks" id="mcq_marks"  class="form-control" step="any" placeholder="{{ localize('mcq_total_marks') }}"
                    value="{{ $interview->mcq_marks }}" required>
            </div>
            @if ($errors->has('mcq_marks'))
                <div class="error text-danger m-2">{{ $errors->first('mcq_marks') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-6 mt-3">
        <div class="row">
            <label for="total_marks" class="col-form-label col-sm-3 col-md-12 col-xl-4 fw-semibold" style="text-align: right">{{ localize('total_marks') }}
                <span class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-8">
                <input type="number" name="total_marks" id="total_marks"  class="form-control" step="any" placeholder="{{ localize('total_marks') }}"
                    value="{{ $interview->total_marks }}" required>
            </div>
            @if ($errors->has('total_marks'))
                <div class="error text-danger m-2">{{ $errors->first('total_marks') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-6 mt-3">
        <div class="row">
            <label for="recommandation" class="col-form-label col-sm-3 col-md-12 col-xl-4 fw-semibold">{{ localize('recommandation') }}</label>
            <div class="col-sm-9 col-md-12 col-xl-8">
                <input type="text" name="recommandation" id="recommandation"  class="form-control" placeholder="{{ localize('recommandation') }}"
                    value="{{ $interview->recommandation }}">
            </div>

            @if ($errors->has('recommandation'))
                <div class="error text-danger m-2">{{ $errors->first('recommandation') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-6 mt-3">
        <div class="row">
            <label for="selection" class="col-form-label col-sm-3 col-md-12 col-xl-4 fw-semibold" style="text-align: right">{{ localize('selection') }}
                <span class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-8">
                <select name="selection" id="selection" class="form-control select-basic-single" required>
                    <option value="">{{ localize('selection_option') }}</option>
                    <option value="1" @if ($interview->selection == 1) selected @endif >{{ localize('selected') }}</option>
                    <option value="0" @if ($interview->selection == 0) selected @endif >{{ localize('deselected') }}</option>
                </select>
            </div>
            @if ($errors->has('selection'))
                <div class="error text-danger m-2">{{ $errors->first('selection') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-12 mt-3">
        <div class="row">
            <label for="details" class="col-form-label col-sm-3 col-md-12 col-xl-2 fw-semibold">{{ localize('details') }}</label>
            <div class="col-sm-9 col-md-12 col-xl-10">
                <textarea name="details" id="details" rows="2" class="form-control" placeholder="{{ localize('details') }}">{{ $interview->details }}</textarea>
            </div>
            @if ($errors->has('details'))
                <div class="error text-danger m-2">{{ $errors->first('details') }}</div>
            @endif
        </div>
    </div>
    
</div>
                