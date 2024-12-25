<!-- Modal -->
<div class="modal fade" id="viewMessage{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">
                    {{ localize('message') }}
                </h5>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-md-12 mt-3">
                        <div class="row">
                            <label for="receiver_name"
                                class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('receiver_name') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9 col-md-12 col-xl-9">

                                <select name="receiver_id" id="receiver_id" class="form-control select-basic-single" required>
                                    @foreach ($employees as $employee)

                                        <option value="{{ $employee->id }}" {{ $employee->id == $data->receiver_id ? 'selected' : '' }} disabled >{{ $employee->full_name }}
                                        </option>

                                    @endforeach
                                </select>

                            </div>

                            @if ($errors->has('receiver_id'))
                                <div class="error text-danger m-2">{{ $errors->first('receiver_id') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-12 mt-3">
                        <div class="row">
                            <label for="subject"
                                class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('subject') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9 col-md-12 col-xl-9">
                                <input type="text" class="form-control" id="subject" name="subject"
                                    placeholder="{{ localize('subject') }}" value="{{ old('subject') ?? $data->subject }}"
                                    readonly>
                            </div>

                            @if ($errors->has('subject'))
                                <div class="error text-danger m-2">{{ $errors->first('subject') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-12 mt-3">
                        <div class="row">
                            <label for="message"
                                class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('message') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9 col-md-12 col-xl-9">

                                <textarea  class="form-control" id="message" name="message"
                                    placeholder="{{ localize('message') }}" rows ="5" readonly >{{ $data->message }}</textarea>
                            </div>

                            @if ($errors->has('message'))
                                <div class="error text-danger m-2">{{ $errors->first('message') }}</div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger"
                    data-bs-dismiss="modal">{{ localize('close') }}</button>
            </div>
        </div>
    </div>
</div>
