@extends('layouts.app')

@section('template_title')
    {{ $province->name ?? "{{ __('Show') Province" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Province</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('provinces.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Code:</strong>
                            {{ $province->code }}
                        </div>
                        <div class="form-group">
                            <strong>Name Th:</strong>
                            {{ $province->name_th }}
                        </div>
                        <div class="form-group">
                            <strong>Name En:</strong>
                            {{ $province->name_en }}
                        </div>
                        <div class="form-group">
                            <strong>Geography Id:</strong>
                            {{ $province->geography_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
