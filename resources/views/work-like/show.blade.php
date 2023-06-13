@extends('layouts.app')

@section('template_title')
    {{ $workLike->name ?? "{{ __('Show') Work Like" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Work Like</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('work-likes.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Work Id:</strong>
                            {{ $workLike->work_id }}
                        </div>
                        <div class="form-group">
                            <strong>Author Id:</strong>
                            {{ $workLike->author_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
