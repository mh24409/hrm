<!-- Modal -->
<div class="modal fade" id="edit-gender-{{ $gender->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">
                    Edit Gender
                </h5>
            </div>
            <form action="{{ route('genders.update', $gender->uuid) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="row">
                        @input(['input_name' => 'gender_name', 'value' => $gender->gender_name])
                        @radio(['input_name' => 'is_active', 'data_set' => [1 => 'Active', 0 => 'Inactive'], 'value' => $gender->is_active])
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger"
                        data-bs-dismiss="modal">{{ localize('close') }}</button>
                    <button class="btn btn-primary" id="update_submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
