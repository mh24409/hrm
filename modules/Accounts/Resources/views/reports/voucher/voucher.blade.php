<div class="modal fade" id="show-voucher" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-body" id="voucherData">


            </div>
            <div class="modal-footer d-print-none">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ localize('close') }}</button>
            </div>
        </div>
    </div>
</div>
@push('js')
    <script src="{{ module_asset('Accounts/js/voucher-show.js?v_' . date('h_i')) }}"></script>
@endpush
