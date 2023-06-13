<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('review_id') }}
            {{ Form::text('review_id', $reviewLike->review_id, ['class' => 'form-control' . ($errors->has('review_id') ? ' is-invalid' : ''), 'placeholder' => 'Review Id']) }}
            {!! $errors->first('review_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('author_id') }}
            {{ Form::text('author_id', $reviewLike->author_id, ['class' => 'form-control' . ($errors->has('author_id') ? ' is-invalid' : ''), 'placeholder' => 'Author Id']) }}
            {!! $errors->first('author_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>