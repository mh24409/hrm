@extends('backend.layouts.app')
@section('title', localize('id_card'))
@push('css')
@endpush
@section('content')
    @include('backend.layouts.common.validation')
    @include('backend.layouts.common.message')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('employee_list_for_id_card') }}</h6>
                </div>

            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table display table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>{{ localize('sl') }}</th>
                            <th>{{ localize('employee_name') }}</th>
                            <th>{{ localize('position') }}</th>
                            <th>{{ localize('contact_no') }}</th>
                            <th>{{ localize('email') }}</th>
                            <th>{{ localize('action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dbData as $key => $data)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    {{ $data?->full_name }}
                                    {{ $data?->middle_name }}
                                    {{ $data?->last_name }}
                                </td>
                                <td>{{ $data->position?->position_name }}</td>
                                <td>{{ $data?->phone }}</td>
                                <td>{{ $data?->email }}</td>
                                <td>
                                    @can('read_id_card')
                                        <a href="{{ route('idprint.employeeshow', $data->uuid) }}"
                                            class="btn btn-success btn-sm me-1" target="_blank" title="Print Id Card"><i
                                                class="fa-regular fa-id-card"></i></a>
                                    @endcan


                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="8" class="text-center">{{ localize('empty_data') }}</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

        </div>


    </div>
@endsection
@push('js')
    <script src="{{ module_asset('HumanResource/js/hrcommon.js') }}"></script>
@endpush
