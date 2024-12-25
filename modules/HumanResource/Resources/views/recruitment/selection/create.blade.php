<div class="row">
    <div class="col-md-12 mt-3">
        <div class="row">
            <label for="candidate_id" class="col-form-label col-sm-3 col-md-12 col-xl-4 fw-semibold">{{ localize('candidate_name') }}
                <span class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-8">
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
            <label for="position_name" class="col-form-label col-sm-3 col-md-12 col-xl-4 fw-semibold">{{ localize('position') }}
                <span class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-8">
                <input type="text" name="position_name" id="position_name"  class="form-control" placeholder="{{ localize('position') }}"
                    value="{{ old('position_name') }}" readonly required>
                <input type="hidden" name="position_id" id="position_id">
            </div>
            @if ($errors->has('position_name'))
                <div class="error text-danger m-2">{{ $errors->first('position_name') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-12 mt-3">
        <div class="row">
            <label for="selection_terms" class="col-form-label col-sm-3 col-md-12 col-xl-4 fw-semibold">{{ localize('selection_terms') }} 
                <span class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-8">
                <textarea name="selection_terms" id="selection_terms" rows="3" class="form-control" placeholder="{{ localize('selection_terms') }}" required></textarea>
            </div>
            @if ($errors->has('selection_terms'))
                <div class="error text-danger m-2">{{ $errors->first('selection_terms') }}</div>
            @endif
        </div>
    </div>
    
</div>
                