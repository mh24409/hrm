<!-- Modal -->
<div class="modal fade" id="close-financial-year" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">
                    @section('title', localize('debit_voucher_list'))Close Financial Year
                </h5>
            </div>
            {{-- {{ route('close-years.store') }} --}}
            <form id="leadForm" action="{{ route('financial-years.close') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 text-start">
                            <div class="row mt-3">
                                <label for="close_year"
                                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold required">{{ localize('financial_year') }}<span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-9 col-md-12 col-xl-9">
                                    <select name="financial_year_id" class="select-basic-single" required>
                                        <option value="" selected disabled>{{ localize('select_one') }}</option>
                                        @foreach ($activeYears as $value)
                                            <option value="{{ $value->id }}">{{ $value->financial_year }}</option>
                                        @endforeach

                                    </select>

                                    @if ($errors->has('close_year'))
                                        <div class="error text-danger m-2">{{ $errors->first('close_year') }}</div>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger"
                        data-bs-dismiss="modal">{{ localize('close') }}</button>
                    <button class="btn btn-primary">{{ localize('close_year') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
