<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('title') }}
            {{ Form::text('title', $userNotification->title, ['class' => 'form-control' . ($errors->has('title') ? ' is-invalid' : ''), 'placeholder' => 'Title']) }}
            {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('message') }}
            {{ Form::text('message', $userNotification->message, ['class' => 'form-control' . ($errors->has('message') ? ' is-invalid' : ''), 'placeholder' => 'Message']) }}
            {!! $errors->first('message', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('notification_type') }}
            {{ Form::text('notification_type', $userNotification->notification_type, ['class' => 'form-control' . ($errors->has('notification_type') ? ' is-invalid' : ''), 'placeholder' => 'Notification Type']) }}
            {!! $errors->first('notification_type', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('review_id') }}
            {{ Form::text('review_id', $userNotification->review_id, ['class' => 'form-control' . ($errors->has('review_id') ? ' is-invalid' : ''), 'placeholder' => 'Review Id']) }}
            {!! $errors->first('review_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('author_id') }}
            {{ Form::text('author_id', $userNotification->author_id, ['class' => 'form-control' . ($errors->has('author_id') ? ' is-invalid' : ''), 'placeholder' => 'Author Id']) }}
            {!! $errors->first('author_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>