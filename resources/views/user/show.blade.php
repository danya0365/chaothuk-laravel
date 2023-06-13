@extends('layouts.app')

@section('template_title')
    {{ $user->name ?? "{{ __('Show') User" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} User</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('users.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $user->name }}
                        </div>
                        <div class="form-group">
                            <strong>Email:</strong>
                            {{ $user->email }}
                        </div>
                        <div class="form-group">
                            <strong>Profile Image:</strong>
                            {{ $user->profile_image }}
                        </div>
                        <div class="form-group">
                            <strong>Cover Image:</strong>
                            {{ $user->cover_image }}
                        </div>
                        <div class="form-group">
                            <strong>First Name:</strong>
                            {{ $user->first_name }}
                        </div>
                        <div class="form-group">
                            <strong>Last Name:</strong>
                            {{ $user->last_name }}
                        </div>
                        <div class="form-group">
                            <strong>Birth Date:</strong>
                            {{ $user->birth_date }}
                        </div>
                        <div class="form-group">
                            <strong>Mobile Phone:</strong>
                            {{ $user->mobile_phone }}
                        </div>
                        <div class="form-group">
                            <strong>Location:</strong>
                            {{ $user->location }}
                        </div>
                        <div class="form-group">
                            <strong>Biography:</strong>
                            {{ $user->biography }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
