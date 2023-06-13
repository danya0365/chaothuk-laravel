@extends('layouts.app')

@section('template_title')
    {{ $workBooking->name ?? "{{ __('Show') Work Booking" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Work Booking</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('work-bookings.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Customer Message:</strong>
                            {{ $workBooking->customer_message }}
                        </div>
                        <div class="form-group">
                            <strong>Mobile Phone:</strong>
                            {{ $workBooking->mobile_phone }}
                        </div>
                        <div class="form-group">
                            <strong>Latitude:</strong>
                            {{ $workBooking->latitude }}
                        </div>
                        <div class="form-group">
                            <strong>Longitude:</strong>
                            {{ $workBooking->longitude }}
                        </div>
                        <div class="form-group">
                            <strong>Booking Status:</strong>
                            {{ $workBooking->booking_status }}
                        </div>
                        <div class="form-group">
                            <strong>Customer Confirm Status:</strong>
                            {{ $workBooking->customer_confirm_status }}
                        </div>
                        <div class="form-group">
                            <strong>Worker Confirm Status:</strong>
                            {{ $workBooking->worker_confirm_status }}
                        </div>
                        <div class="form-group">
                            <strong>Author Id:</strong>
                            {{ $workBooking->author_id }}
                        </div>
                        <div class="form-group">
                            <strong>Work Id:</strong>
                            {{ $workBooking->work_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
