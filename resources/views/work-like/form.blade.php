<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('work_id') }}
            {{ Form::text('work_id', $workLike->work_id, ['class' => 'form-control' . ($errors->has('work_id') ? ' is-invalid' : ''), 'placeholder' => 'Work Id']) }}
            {!! $errors->first('work_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('author_id') }}
            {{ Form::text('author_id', $workLike->author_id, ['class' => 'form-control' . ($errors->has('author_id') ? ' is-invalid' : ''), 'placeholder' => 'Author Id']) }}
            {!! $errors->first('author_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>