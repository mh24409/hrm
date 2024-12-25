@extends('setting::settings')
@section('title', localize('tax_setup'))
@push('css')
@endpush
@section('setting_content')

    <div class="card mb-4 border">
        <div class="card-header py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('tax_setup') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive mt-4 table_customize">
                <form action="{{ route('tax-setup.store') }}" method="POST">
                    @csrf
                    <table class="table table-bordered text-end">
                        <thead>
                            <tr>
                                <th class="text-center">{{ localize('start_amount') }}<span class="text-danger">*</span>
                                </th>
                                <th class="text-center">{{ localize('end_amount') }}<span class="text-danger">*</span></th>
                                <th class="text-center">{{ localize('tax_percent') }}<span class="text-danger">*</span></th>
                                <th class="text-center">{{ localize('add_amount') }}</th>
                                <th class="text-center">{{ localize('action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($taxes as $item)
                                <tr>
                                    <td>
                                        <input type="number" name="start_amount[]" onchange="validate(this)" min="0"
                                            id="start_amount_{{ $loop->iteration }}" value="{{ $item->min }}" required
                                            class="form-control text-center" placeholder="{{ localize('start_amount') }}" />
                                        <input type="hidden" name="id[]" id="id_{{ $loop->iteration }}"
                                            value="{{ $item->id }}" />
                                    </td>
                                    <td>
                                        <input type="number" name="end_amount[]" onchange="validate(this)" min="0"
                                            id="end_amount_{{ $loop->iteration }}" value="{{ $item->max }}" required
                                            class="form-control text-center" placeholder="{{ localize('end_amount') }}" />
                                    </td>
                                    <td>
                                        <input type="number" name="tax_percent[]" min="0"
                                            id="tax_percent_{{ $loop->iteration }}" value="{{ $item->tax_percent }}"
                                            required class="form-control text-center"
                                            placeholder="{{ localize('tax_percent') }}" />
                                    </td>
                                    <td>
                                        <input type="number" name="add_amount[]" min="0"
                                            id="add_amount_{{ $loop->iteration }}" value="{{ $item->add_amount }}"
                                            required class="form-control text-center"
                                            placeholder="{{ localize('add_amount') }}" />
                                    </td>
                                    <td class="text-center">
                                        @if ($loop->iteration == 1)
                                            <button class="btn btn-success text-center" type="button"
                                                onclick="addRow(this)"><i class="fa fa-plus"></i></button>
                                        @else
                                            <button class="btn btn-danger text-center" type="button"
                                                onclick="deleteRow(this)"><i class="fa fa-close"></i></button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td>
                                        <input type="number" name="start_amount[]" id="start_amount_1" required
                                            min="0" onchange="validate(this)" class="form-control text-center"
                                            placeholder="{{ localize('start_amount') }}" />
                                        <input type="hidden" name="id[]" id="id_1" />
                                    </td>
                                    <td>
                                        <input type="number" name="end_amount[]" id="end_amount_1" required min="0"
                                            onchange="validate(this)" class="form-control text-center"
                                            placeholder="{{ localize('end_amount') }}" />
                                    </td>
                                    <td>
                                        <input type="number" name="tax_percent[]" id="tax_percent_1" required
                                            min="0" class="form-control text-center"
                                            placeholder="{{ localize('tax_percent') }}" />
                                    </td>
                                    <td>
                                        <input type="number" name="add_amount[]" id="add_amount_1" required min="0"
                                            class="form-control text-center" placeholder="{{ localize('add_amount') }}" />
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-success text-center" type="button" onclick="addRow(this)"><i
                                                class="fa fa-plus"></i></button>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5">
                                    <button type="submit" class="btn btn-success">{{ localize('save') }}</button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </form>
            </div>
        </div>
    </div>


@endsection
@push('js')
    <script>
        function addRow(e) {
            var count = $('tbody tr').length;
            var tr = '<tr>' +
                '<td>' +
                '<input type="number" name="start_amount[]" onchange="validate(this)" min="0" id="start_amount_' + count +
                '" value="" required class="form-control text-center" placeholder="' + localize('start_amount') +
                '" />' +
                '<input type="hidden" name="id[]" id="id_' + count + '" />' +
                '</td>' +
                '<td>' +
                '<input type="number" name="end_amount[]" onchange="validate(this)" min="0" id="end_amount_' + count +
                '" value="" required class="form-control text-center" placeholder="' + localize('end_amount') +
                '" />' +
                '</td>' +
                '<td>' +
                '<input type="number" name="tax_percent[]" min="0" id="tax_percent_' + count +
                '" value="" required class="form-control text-center" placeholder="' + localize('tax_percent') +
                '" />' +
                '</td>' +
                '<td>' +
                '<input type="number" name="add_amount[]" min="0" id="add_amount_' + count +
                '" value="" required class="form-control text-center" placeholder="' + localize('add_amount') +
                '" />' +
                '</td>' +
                '<td class="text-center">' +
                '<button class="btn btn-danger text-center" type="button" onclick="deleteRow(this)"><i class="fa fa-close"></i></button>' +
                '</td>' +
                '</tr>';
            $('tbody').append(tr);
        }

        function deleteRow(e) {
            $(e).closest('tr').remove();
        }

        function validate(e) {

            // Get the current row index
            var rowIndex = e.parentNode.parentNode.rowIndex;

            // Get the value of the current start_amount and end_amount
            var startAmount = parseFloat(e.parentNode.parentNode.querySelector('input[name="start_amount[]"]').value);
            var endAmount = parseFloat(e.parentNode.parentNode.querySelector('input[name="end_amount[]"]').value);

            // Loop through previous rows to check for overlap
            for (var i = 1; i < rowIndex; i++) {
                var prevStartAmount = parseFloat(document.getElementById('start_amount_' + i).value);
                var prevEndAmount = parseFloat(document.getElementById('end_amount_' + i).value);

                // Check for overlap
                if (!((startAmount >= prevEndAmount) || (endAmount <= prevStartAmount))) {
                    // If there is an overlap, display an error message or handle it as needed
                    alert("Overlap detected with row " + i);
                    // Clear the input fields or take other necessary actions
                    e.value = "";
                    // Exit the loop since we found an overlap
                    return;
                }
            }
        }
    </script>
@endpush
