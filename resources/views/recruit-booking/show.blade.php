@extends('layouts.app')

@section('template_title')
    {{ $recruitBooking->name ?? "{{ __('Show') Recruit Booking" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Recruit Booking</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('recruit-bookings.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Customer Message:</strong>
                            {{ $recruitBooking->customer_message }}
                        </div>
                        <div class="form-group">
                            <strong>Mobile Phone:</strong>
                            {{ $recruitBooking->mobile_phone }}
                        </div>
                        <div class="form-group">
                            <strong>Latitude:</strong>
                            {{ $recruitBooking->latitude }}
                        </div>
                        <div class="form-group">
                            <strong>Longitude:</strong>
                            {{ $recruitBooking->longitude }}
                        </div>
                        <div class="form-group">
                            <strong>Booking Status:</strong>
                            {{ $recruitBooking->booking_status }}
                        </div>
                        <div class="form-group">
                            <strong>Customer Confirm Status:</strong>
                            {{ $recruitBooking->customer_confirm_status }}
                        </div>
                        <div class="form-group">
                            <strong>Worker Confirm Status:</strong>
                            {{ $recruitBooking->worker_confirm_status }}
                        </div>
                        <div class="form-group">
                            <strong>Author Id:</strong>
                            {{ $recruitBooking->author_id }}
                        </div>
                        <div class="form-group">
                            <strong>Recruit Id:</strong>
                            {{ $recruitBooking->recruit_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
