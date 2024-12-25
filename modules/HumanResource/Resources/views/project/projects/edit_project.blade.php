@method('PUT')

@if($project_lead_check)

    <div class="row ps-4 pe-4">

        <div class="col-md-12 mt-3">
            <div class="row">
                <label for="project_name"
                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('project_name') }}<span
                        class="text-danger">*</span></label>
                <div class="col-sm-9 col-md-12 col-xl-9">
                    <input type="text" class="form-control" id="project_name" name="project_name"
                        placeholder="{{ localize('project_name') }}" value="{{ old('project_name') ?? $project->project_name }}"
                        required>
                </div>

                @if ($errors->has('project_name'))
                    <div class="error text-danger m-2">{{ $errors->first('project_name') }}</div>
                @endif
            </div>
        </div>

        <div class="col-md-12 mt-3">
            <div class="row">
                <label for="client_id"
                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('client') }}<span
                        class="text-danger">*</span></label>
                <div class="col-sm-9 col-md-12 col-xl-9">

                    <select name="client_id" id="client_id" class="form-control select-basic-single" required>
                        <option value="">{{ localize('client') }}</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}" {{ $client->id == $project->client_id ? 'selected' : '' }} >{{ $client->client_name }}
                            </option>
                        @endforeach
                    </select>

                </div>

                @if ($errors->has('client_id'))
                    <div class="error text-danger m-2">{{ $errors->first('client_id') }}</div>
                @endif
            </div>
        </div>

        <div class="col-md-12 mt-3">
            <div class="row">
                <label for="project_lead"
                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('project_lead') }}<span
                        class="text-danger">*</span></label>
                <div class="col-sm-9 col-md-12 col-xl-9">

                    <select name="project_lead" id="project_lead" class="form-control select-basic-single" required>
                        <option value="">{{ localize('project_lead') }}</option>
                        @foreach ($project_leads as $project_lead)
                            <option value="{{ $project_lead->id }}" {{ $project_lead->id == $project->project_lead ? 'selected' : '' }}>{{ $project_lead->full_name }}
                            </option>
                        @endforeach
                    </select>

                </div>

                @if ($errors->has('project_lead'))
                    <div class="error text-danger m-2">{{ $errors->first('project_lead') }}</div>
                @endif
            </div>
        </div>

        <div class="col-md-12 mt-3">
            <div class="row">
                <label for="team_members"
                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('team_members') }}<span
                        class="text-danger">*</span></label>
                <div class="col-sm-9 col-md-12 col-xl-9">

                    <select name="team_members[]" id="team_members" class="form-control select-basic-single" multiple="multiple" placeholder=">{{ localize('select_team_members') }}" required>
                        <option value=""></option>
                        @foreach ($team_members as $team_member)
                            <option value="{{ $team_member->id }}" {{ in_array($team_member->id, $existing_team_members) ? 'selected' : '' }}>
                                {{ $team_member->full_name }}
                            </option>
                        @endforeach
                    </select>

                </div>

                @if ($errors->has('team_members'))
                    <div class="error text-danger m-2">{{ $errors->first('team_members') }}</div>
                @endif
            </div>
        </div>

        <div class="col-md-12 mt-3">
            <div class="row">
                <label for="approximate_tasks"
                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('approximate_tasks') }}<span
                        class="text-danger">*</span></label>
                <div class="col-sm-9 col-md-12 col-xl-9">
                    <input type="number" class="form-control" id="approximate_tasks" name="approximate_tasks"
                        placeholder="{{ localize('approximate_tasks') }}" value="{{ old('approximate_tasks') ?? $project->approximate_tasks }}"
                        required>
                </div>

                @if ($errors->has('approximate_tasks'))
                    <div class="error text-danger m-2">{{ $errors->first('approximate_tasks') }}</div>
                @endif
            </div>
        </div>

        <div class="col-md-12 mt-3">
            <div class="row">
                <label for="summary"
                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('summary') }}<span
                        class="text-danger">*</span></label>
                <div class="col-sm-9 col-md-12 col-xl-9">
                    <input type="text" class="form-control" id="project_summary" name="project_summary"
                        placeholder="{{ localize('summary') }}" value="{{ old('project_summary') ?? $project->project_summary }}"
                        required>
                </div>

                @if ($errors->has('summary'))
                    <div class="error text-danger m-2">{{ $errors->first('summary') }}</div>
                @endif
            </div>
        </div>

        <div class="col-md-12 mt-3">
            <div class="row">
                <label for="start_date"
                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('start_date') }}<span
                        class="text-danger">*</span></label>
                <div class="col-sm-9 col-md-12 col-xl-9">
                    <input type="text" class="form-control date_picker" id="project_start_date" name="project_start_date"
                        placeholder="{{ localize('start_date') }}" value="{{ old('project_start_date') ?? $project->project_start_date }}"
                        required>
                </div>

                @if ($errors->has('start_date'))
                    <div class="error text-danger m-2">{{ $errors->first('start_date') }}</div>
                @endif
            </div>
        </div>

        <div class="col-md-12 mt-3">
            <div class="row">
                <label for="project_duration"
                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('project_duration') }}<span
                        class="text-danger">*</span></label>
                <div class="col-sm-9 col-md-12 col-xl-9">
                    <input type="number" class="form-control" id="project_duration" name="project_duration"
                        placeholder="{{ localize('project_duration') }}" value="{{ old('project_duration') ?? $project->project_duration }}"
                        required>
                </div>

                @if ($errors->has('project_duration'))
                    <div class="error text-danger m-2">{{ $errors->first('project_duration') }}</div>
                @endif
            </div>
        </div> 

        <div class="col-md-12 mt-3">
            <div class="row">
                <label for="reward_points"
                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('reward_points') }}<span
                        class="text-danger">*</span></label>
                <div class="col-sm-9 col-md-12 col-xl-9">
                    <input type="number" class="form-control" id="project_reward_point" name="project_reward_point"
                        placeholder="{{ localize('reward_points') }}" value="{{ old('project_reward_point') ?? $project->project_reward_point }}"
                        required>
                </div>

                @if ($errors->has('reward_points'))
                    <div class="error text-danger m-2">{{ $errors->first('reward_points') }}</div>
                @endif
            </div>
        </div> 


        @if ($project_sprints_count > 0)

        <div class="col-md-12 mt-3">
            <div class="row">
                <label for="status"
                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('status') }}<span
                        class="text-danger">*</span></label>
                <div class="col-sm-9 col-md-12 col-xl-9">

                    <select name="is_completed" id="is_completed" class="form-control select-basic-single" required>
                        <option value="">{{ localize('select_status') }}</option>
                        <option value="1" {{ (int)$project->is_completed == 1 ? 'selected' : '' }}>{{ localize('close') }}
                            </option>
                    </select>
                </div>

                @if ($errors->has('project_lead'))
                    <div class="error text-danger m-2">{{ $errors->first('project_lead') }}</div>
                @endif
            </div>
        </div>

        @endif

    </div>
@else

<span class="text-danger text-center">{{localize('you_are_not_suppose_to_access_this_project')}}</span>

@endif
