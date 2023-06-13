<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('is_can_create_recruit') }}
            {{ Form::text('is_can_create_recruit', $userPermission->is_can_create_recruit, ['class' => 'form-control' . ($errors->has('is_can_create_recruit') ? ' is-invalid' : ''), 'placeholder' => 'Is Can Create Recruit']) }}
            {!! $errors->first('is_can_create_recruit', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('is_can_create_work') }}
            {{ Form::text('is_can_create_work', $userPermission->is_can_create_work, ['class' => 'form-control' . ($errors->has('is_can_create_work') ? ' is-invalid' : ''), 'placeholder' => 'Is Can Create Work']) }}
            {!! $errors->first('is_can_create_work', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('is_can_review_work') }}
            {{ Form::text('is_can_review_work', $userPermission->is_can_review_work, ['class' => 'form-control' . ($errors->has('is_can_review_work') ? ' is-invalid' : ''), 'placeholder' => 'Is Can Review Work']) }}
            {!! $errors->first('is_can_review_work', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('is_can_reply_review') }}
            {{ Form::text('is_can_reply_review', $userPermission->is_can_reply_review, ['class' => 'form-control' . ($errors->has('is_can_reply_review') ? ' is-invalid' : ''), 'placeholder' => 'Is Can Reply Review']) }}
            {!! $errors->first('is_can_reply_review', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('is_can_access_supervisor') }}
            {{ Form::text('is_can_access_supervisor', $userPermission->is_can_access_supervisor, ['class' => 'form-control' . ($errors->has('is_can_access_supervisor') ? ' is-invalid' : ''), 'placeholder' => 'Is Can Access Supervisor']) }}
            {!! $errors->first('is_can_access_supervisor', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('is_can_access_admin') }}
            {{ Form::text('is_can_access_admin', $userPermission->is_can_access_admin, ['class' => 'form-control' . ($errors->has('is_can_access_admin') ? ' is-invalid' : ''), 'placeholder' => 'Is Can Access Admin']) }}
            {!! $errors->first('is_can_access_admin', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>