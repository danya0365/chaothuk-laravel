@extends('layouts.app')

@section('template_title')
    {{ $subDistrict->name ?? "{{ __('Show') Sub District" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Sub District</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('sub-districts.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Zip Code:</strong>
                            {{ $subDistrict->zip_code }}
                        </div>
                        <div class="form-group">
                            <strong>Name Th:</strong>
                            {{ $subDistrict->name_th }}
                        </div>
                        <div class="form-group">
                            <strong>Name En:</strong>
                            {{ $subDistrict->name_en }}
                        </div>
                        <div class="form-group">
                            <strong>Amphure Id:</strong>
                            {{ $subDistrict->amphure_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
