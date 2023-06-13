@extends('layouts.app')

@section('template_title')
    {{ $reviewLike->name ?? "{{ __('Show') Review Like" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Review Like</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('review-likes.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Review Id:</strong>
                            {{ $reviewLike->review_id }}
                        </div>
                        <div class="form-group">
                            <strong>Author Id:</strong>
                            {{ $reviewLike->author_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
