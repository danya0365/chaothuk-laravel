@extends('layouts.app')

@section('template_title')
    {{ $userNotification->name ?? "{{ __('Show') User Notification" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} User Notification</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('user-notifications.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Title:</strong>
                            {{ $userNotification->title }}
                        </div>
                        <div class="form-group">
                            <strong>Message:</strong>
                            {{ $userNotification->message }}
                        </div>
                        <div class="form-group">
                            <strong>Notification Type:</strong>
                            {{ $userNotification->notification_type }}
                        </div>
                        <div class="form-group">
                            <strong>Review Id:</strong>
                            {{ $userNotification->review_id }}
                        </div>
                        <div class="form-group">
                            <strong>Author Id:</strong>
                            {{ $userNotification->author_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
