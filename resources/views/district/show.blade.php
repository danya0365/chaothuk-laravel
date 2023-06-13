@extends('layouts.app')

@section('template_title')
    {{ $district->name ?? "{{ __('Show') District" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} District</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('districts.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Code:</strong>
                            {{ $district->code }}
                        </div>
                        <div class="form-group">
                            <strong>Name Th:</strong>
                            {{ $district->name_th }}
                        </div>
                        <div class="form-group">
                            <strong>Name En:</strong>
                            {{ $district->name_en }}
                        </div>
                        <div class="form-group">
                            <strong>Province Id:</strong>
                            {{ $district->province_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
