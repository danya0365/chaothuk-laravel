@extends('layouts.app')

@section('template_title')
    {{ $work->name ?? "{{ __('Show') Work" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Work</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('works.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Code:</strong>
                            {{ $work->code }}
                        </div>
                        <div class="form-group">
                            <strong>Title:</strong>
                            {{ $work->title }}
                        </div>
                        <div class="form-group">
                            <strong>Description:</strong>
                            {{ $work->description }}
                        </div>
                        <div class="form-group">
                            <strong>Details:</strong>
                            {{ $work->details }}
                        </div>
                        <div class="form-group">
                            <strong>Primary Image:</strong>
                            {{ $work->primary_image }}
                        </div>
                        <div class="form-group">
                            <strong>Images:</strong>
                            {{ $work->images }}
                        </div>
                        <div class="form-group">
                            <strong>Price:</strong>
                            {{ $work->price }}
                        </div>
                        <div class="form-group">
                            <strong>Avg Review Rating:</strong>
                            {{ $work->avg_review_rating }}
                        </div>
                        <div class="form-group">
                            <strong>Display Priority:</strong>
                            {{ $work->display_priority }}
                        </div>
                        <div class="form-group">
                            <strong>Work Status:</strong>
                            {{ $work->work_status }}
                        </div>
                        <div class="form-group">
                            <strong>Province Id:</strong>
                            {{ $work->province_id }}
                        </div>
                        <div class="form-group">
                            <strong>Work Type Id:</strong>
                            {{ $work->work_type_id }}
                        </div>
                        <div class="form-group">
                            <strong>Author Id:</strong>
                            {{ $work->author_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
