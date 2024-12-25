<!-- Modal -->
<div class="modal fade" id="employeeIdShow" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">
                    {{ localize('employee_id_show') }}
                </h5>
            </div>
            <div class="modal-body text-start">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">{{ localize('employee_name') }}</th>
                                <th scope="col" class="text-center">#{{ localize('id') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employee as $employeeValue)
                                <tr>
                                    <td>{{ ucwords($employeeValue->full_name) }}</th>
                                    <td class="text-center">{{ $employeeValue->id }}</td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ localize('close') }}</button>
            </div>
        </div>
    </div>
</div>
