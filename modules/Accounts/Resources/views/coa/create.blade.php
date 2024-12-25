@extends('backend.layouts.app')
@section('title', localize('coa'))
@push('css')
    <link href="{{ asset('backend/assets/plugins/vakata-jstree/dist/themes/default/style.min.css') }}" rel="stylesheet">
    <link href="{{ module_asset('Accounts/jqueryui/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ module_asset('Accounts/css/dailog.css') }}" rel="stylesheet">
@endpush

@section('content')
    @include('backend.layouts.common.message')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('accounts') }}</h6>
                </div>
                <div class="text-start">
                </div>
            </div>
        </div>
        <div class="card-body">
            @include('accounts::coa.subblade.confirm')
            <div class="row">
                <div class="col-6">
                    <div class="search mb-2">
                        <div class="search__inner tree-search">
                            <input id="treesearch" type="text" class="form-control search__text"
                                placeholder="Tree Search..." autocomplete="off">
                            <i class="typcn typcn-zoom-outline search__helper" data-sa-action="search-close"></i>
                        </div>
                    </div>
                    @include('accounts::coa.subblade.coatree')
                </div>
                <div class="col-6">
                    @include('accounts::coa.subblade.coafrom')
                </div>
            </div>
        </div>
        <input type="hidden" id="url" value="{{ url('') }}">
        <input type="hidden" id="accsubType" value="{{ json_encode($accSubType) }}">
    </div>

@endsection
@push('js')
    <script src="{{ asset('backend/assets/plugins/vakata-jstree/dist/jstree.min.js?v=1') }}"></script>
    <script src="{{ asset('backend/assets/dist/js/pages/tree-view.active.js?v=1') }}"></script>
    <script src="{{ module_asset('Accounts/js/account.js?v=1') }}"></script>
    <script src="{{ module_asset('Accounts/jqueryui/jquery-ui.min.js?v=1') }}"></script>
@endpush
