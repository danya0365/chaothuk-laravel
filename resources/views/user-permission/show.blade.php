@extends('layouts.app')

@section('template_title')
    {{ $userPermission->name ?? "{{ __('Show') User Permission" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} User Permission</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('user-permissions.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Is Can Create Recruit:</strong>
                            {{ $userPermission->is_can_create_recruit }}
                        </div>
                        <div class="form-group">
                            <strong>Is Can Create Work:</strong>
                            {{ $userPermission->is_can_create_work }}
                        </div>
                        <div class="form-group">
                            <strong>Is Can Review Work:</strong>
                            {{ $userPermission->is_can_review_work }}
                        </div>
                        <div class="form-group">
                            <strong>Is Can Reply Review:</strong>
                            {{ $userPermission->is_can_reply_review }}
                        </div>
                        <div class="form-group">
                            <strong>Is Can Access Supervisor:</strong>
                            {{ $userPermission->is_can_access_supervisor }}
                        </div>
                        <div class="form-group">
                            <strong>Is Can Access Admin:</strong>
                            {{ $userPermission->is_can_access_admin }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
