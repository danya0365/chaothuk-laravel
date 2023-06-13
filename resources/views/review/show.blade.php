@extends('layouts.app')

@section('template_title')
    {{ $review->name ?? "{{ __('Show') Review" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Review</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('reviews.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Title:</strong>
                            {{ $review->title }}
                        </div>
                        <div class="form-group">
                            <strong>Message:</strong>
                            {{ $review->message }}
                        </div>
                        <div class="form-group">
                            <strong>Rating:</strong>
                            {{ $review->rating }}
                        </div>
                        <div class="form-group">
                            <strong>Author Id:</strong>
                            {{ $review->author_id }}
                        </div>
                        <div class="form-group">
                            <strong>Work Id:</strong>
                            {{ $review->work_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
