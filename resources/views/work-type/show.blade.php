@extends('layouts.app')

@section('template_title')
    {{ $workType->name ?? "{{ __('Show') Work Type" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Work Type</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('work-types.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Title:</strong>
                            {{ $workType->title }}
                        </div>
                        <div class="form-group">
                            <strong>Image:</strong>
                            {{ $workType->image }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
