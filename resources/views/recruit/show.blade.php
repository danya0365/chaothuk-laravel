@extends('layouts.app')

@section('template_title')
    {{ $recruit->name ?? "{{ __('Show') Recruit" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Recruit</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('recruits.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Title:</strong>
                            {{ $recruit->title }}
                        </div>
                        <div class="form-group">
                            <strong>Description:</strong>
                            {{ $recruit->description }}
                        </div>
                        <div class="form-group">
                            <strong>Primary Image:</strong>
                            {{ $recruit->primary_image }}
                        </div>
                        <div class="form-group">
                            <strong>Images:</strong>
                            {{ $recruit->images }}
                        </div>
                        <div class="form-group">
                            <strong>Budget:</strong>
                            {{ $recruit->budget }}
                        </div>
                        <div class="form-group">
                            <strong>Display Priority:</strong>
                            {{ $recruit->display_priority }}
                        </div>
                        <div class="form-group">
                            <strong>Recruit Status:</strong>
                            {{ $recruit->recruit_status }}
                        </div>
                        <div class="form-group">
                            <strong>Province Id:</strong>
                            {{ $recruit->province_id }}
                        </div>
                        <div class="form-group">
                            <strong>Work Type Id:</strong>
                            {{ $recruit->work_type_id }}
                        </div>
                        <div class="form-group">
                            <strong>Author Id:</strong>
                            {{ $recruit->author_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
