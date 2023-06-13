<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('title') }}
            {{ Form::text('title', $review->title, ['class' => 'form-control' . ($errors->has('title') ? ' is-invalid' : ''), 'placeholder' => 'Title']) }}
            {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('message') }}
            {{ Form::text('message', $review->message, ['class' => 'form-control' . ($errors->has('message') ? ' is-invalid' : ''), 'placeholder' => 'Message']) }}
            {!! $errors->first('message', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('rating') }}
            {{ Form::text('rating', $review->rating, ['class' => 'form-control' . ($errors->has('rating') ? ' is-invalid' : ''), 'placeholder' => 'Rating']) }}
            {!! $errors->first('rating', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('author_id') }}
            {{ Form::text('author_id', $review->author_id, ['class' => 'form-control' . ($errors->has('author_id') ? ' is-invalid' : ''), 'placeholder' => 'Author Id']) }}
            {!! $errors->first('author_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('work_id') }}
            {{ Form::text('work_id', $review->work_id, ['class' => 'form-control' . ($errors->has('work_id') ? ' is-invalid' : ''), 'placeholder' => 'Work Id']) }}
            {!! $errors->first('work_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>