<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('customer_message') }}
            {{ Form::text('customer_message', $recruitBooking->customer_message, ['class' => 'form-control' . ($errors->has('customer_message') ? ' is-invalid' : ''), 'placeholder' => 'Customer Message']) }}
            {!! $errors->first('customer_message', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('mobile_phone') }}
            {{ Form::text('mobile_phone', $recruitBooking->mobile_phone, ['class' => 'form-control' . ($errors->has('mobile_phone') ? ' is-invalid' : ''), 'placeholder' => 'Mobile Phone']) }}
            {!! $errors->first('mobile_phone', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('latitude') }}
            {{ Form::text('latitude', $recruitBooking->latitude, ['class' => 'form-control' . ($errors->has('latitude') ? ' is-invalid' : ''), 'placeholder' => 'Latitude']) }}
            {!! $errors->first('latitude', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('longitude') }}
            {{ Form::text('longitude', $recruitBooking->longitude, ['class' => 'form-control' . ($errors->has('longitude') ? ' is-invalid' : ''), 'placeholder' => 'Longitude']) }}
            {!! $errors->first('longitude', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('booking_status') }}
            {{ Form::text('booking_status', $recruitBooking->booking_status, ['class' => 'form-control' . ($errors->has('booking_status') ? ' is-invalid' : ''), 'placeholder' => 'Booking Status']) }}
            {!! $errors->first('booking_status', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('customer_confirm_status') }}
            {{ Form::text('customer_confirm_status', $recruitBooking->customer_confirm_status, ['class' => 'form-control' . ($errors->has('customer_confirm_status') ? ' is-invalid' : ''), 'placeholder' => 'Customer Confirm Status']) }}
            {!! $errors->first('customer_confirm_status', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('worker_confirm_status') }}
            {{ Form::text('worker_confirm_status', $recruitBooking->worker_confirm_status, ['class' => 'form-control' . ($errors->has('worker_confirm_status') ? ' is-invalid' : ''), 'placeholder' => 'Worker Confirm Status']) }}
            {!! $errors->first('worker_confirm_status', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('author_id') }}
            {{ Form::text('author_id', $recruitBooking->author_id, ['class' => 'form-control' . ($errors->has('author_id') ? ' is-invalid' : ''), 'placeholder' => 'Author Id']) }}
            {!! $errors->first('author_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('recruit_id') }}
            {{ Form::text('recruit_id', $recruitBooking->recruit_id, ['class' => 'form-control' . ($errors->has('recruit_id') ? ' is-invalid' : ''), 'placeholder' => 'Recruit Id']) }}
            {!! $errors->first('recruit_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>