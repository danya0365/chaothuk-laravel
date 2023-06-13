@extends('layouts.app')

@section('template_title')
    {{ $replyLike->name ?? "{{ __('Show') Reply Like" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Reply Like</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('reply-likes.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Reply Id:</strong>
                            {{ $replyLike->reply_id }}
                        </div>
                        <div class="form-group">
                            <strong>Author Id:</strong>
                            {{ $replyLike->author_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
