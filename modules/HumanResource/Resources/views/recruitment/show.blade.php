@extends('backend.layouts.app')
@section('title', localize('candidate_details'))
@push('css')
@endpush
@section('content')

<div class="row">
    <div class="col-sm-12 col-md-4 candidate-cv">
        <div class="card-header">
            <div class="text-center">
                @if($candidate->picture)
                    <img src="{{ asset('storage/' . $candidate->picture) }}" width="150" height="150" style="border-radius: 8px; border: 1px solid #e1e9f1;">
                @else
                    <img src="{{ asset('backend/assets/dist/img/nopreview.jpeg') }}" width="150" height="150" style="border-radius: 8px; border: 2px solid #e1e9f1;">
                @endif
            </div>
        </div>
        <div class="card-content">
            <div class="card-content-member">
                <h4 class="m-t-0">{{ $candidate->first_name }} {{ $candidate->last_name }}</h4>
                <p class="m-0"><i class="fa fa-phone" aria-hidden="true"></i> {{ $candidate->phone }}</p>
            </div>
            <div class="card-content-languages">
                <div class="card-content-languages-group"></div>
                <div class="card-content-languages-group">
                    <table class="table table-hover" width="100%">
                        <tr>
                            <td colspan="2" class="text-center">
                                <h5>{{ localize('personal_information') }}</h5>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ localize('name') }}</th>
                            <td>{{ $candidate->first_name }} {{ $candidate->last_name }}</td>
                        </tr>
                        <tr>
                            <th>{{ localize('phone') }}</th>
                            <td>{{ $candidate->phone }}</td>
                        </tr>
                        <tr>
                            <th>{{ localize('email_address') }}</th>
                            <td>{{ $candidate->email }}</td>
                        </tr>
                        <tr>
                            <th>{{ localize('present_address') }}</th>
                            <td>{{ $candidate->present_address }}</td>
                        </tr>
                        <tr>
                            <th>{{ localize('parmanent_address') }}</th>
                            <td>{{ $candidate->permanent_address }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="card-footer p-0">
                <div class="card-footer-stats"> 
                    <div>
                        <p></p><span class="stats-small"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-8 candidate-cv-info">
        <div class="row">
            <div class="col-sm-12 col-md-4 rating-block">
                <h1><center><i class="fa fa-graduation-cap" aria-hidden="true"></i></center><center>{{ localize('education') }}</center></h1>
            </div>
            <div class="col-sm-12 col-md-8 rating-block">
                <table width="100%" class="table table-hover">
                    <thead>
                        <tr>
                            <th>{{ localize('obtained_degree') }}</th>
                            <th>{{ localize('institute_name') }}</th>
                            <th>{{ localize('result') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($candidate->educations))
                            @foreach ($candidate->educations as $education)
                                <tr>
                                    <td>{{ $education->degree }}</td>
                                    <td>{{ $education->university }}</td>
                                    <td>{{ $education->cgpa }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-4 rating-block">
                <h1><center><i class="fa fa-laptop" aria-hidden="true"></i></center><br><center>{{ localize('past_experience') }}</center></h1>
            </div>
            <div class="col-sm-12 col-md-8 rating-block">
                <table width="100%" class="table table-hover">
                    <thead>
                        <tr>
                            <th>{{ localize('company_name') }}</th>
                            <th>{{ localize('working_period') }}</th>
                            <th>{{ localize('position') }}</th>
                            <th>{{ localize('supervisor') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($candidate->workExperiences))
                            @foreach ($candidate->workExperiences as $workExperience)
                                <tr>
                                    <td>{{ $workExperience->company_name }}</td>
                                    <td>{{ $workExperience->working_period }}</td>
                                    <td>{{ $workExperience->duties }}</td>
                                    <td>{{ $workExperience->supervisor }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

    
@endsection
@push('js')

@endpush
