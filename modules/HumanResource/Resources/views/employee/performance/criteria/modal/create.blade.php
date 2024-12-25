<!-- Modal -->
<div class="modal fade" id="create-criteria" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">
                New Performance Criteria
            </h5>
        </div>
        <form id="leadForm" action="{{route('performance-criterias.store')}}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="row">
                @input(['input_name' => 'title'])
                <div class="cust_border form-group mb-3 mx-0 pb-3 row">
                    <label for="performance_type" class="col-sm-3 col-form-label ps-0">Performance Type</label>
                    <div class="col-lg-9 p-0">
                      <select name="performance_type_id" class="form-select">
                          <option value="">Select Performance Type</option>
                          @foreach($performance_types as $key => $type)
                            <option value="{{ $type->id }}">{{$type->title}}</option>
                          @endforeach
                      </select>
                    @if ($errors->has('performance_type_id'))
                        <div class="error text-danger text-start">{{ $errors->first('performance_type_id') }}</div>
                    @endif
                    </div>
                </div>
                @input(['input_name'=>'description', 'required' => false])

                <div class="cust_border form-group mb-3 mx-0 pb-3 row">
                  <label for="evaluation_type" class="col-sm-3 col-form-label ps-0">Evaluation Type</label>
                  <div class="col-lg-9 p-0">
                    <select name="evaluation_type_id" class="form-select">
                        <option value="">Select Evaluation Type</option>
                        @foreach($evaluation_types as $key => $type)
                          <option value="{{ $type->id }}">{{$type->type_name}}</option>
                        @endforeach
                    </select>
                  @if ($errors->has('evaluation_type_id'))
                      <div class="error text-danger text-start">{{ $errors->first('evaluation_type_id') }}</div>
                  @endif
                  </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">@lang('language.close')</button>
            <button type="reset" class="btn btn-primary" id="reset">{{__('language.reset')}}</button>
            <button type="submit" class="btn btn-primary" id="create_submit">@lang('language.add')</button>
          </div>
        </form>
      </div>
    </div>
  </div>
