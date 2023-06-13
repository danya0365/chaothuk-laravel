<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('code') }}
            {{ Form::text('code', $work->code, ['class' => 'form-control' . ($errors->has('code') ? ' is-invalid' : ''), 'placeholder' => 'Code']) }}
            {!! $errors->first('code', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('title') }}
            {{ Form::text('title', $work->title, ['class' => 'form-control' . ($errors->has('title') ? ' is-invalid' : ''), 'placeholder' => 'Title']) }}
            {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('description') }}
            {{ Form::text('description', $work->description, ['class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''), 'placeholder' => 'Description']) }}
            {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('details') }}
            {{ Form::text('details', $work->details, ['class' => 'form-control' . ($errors->has('details') ? ' is-invalid' : ''), 'placeholder' => 'Details']) }}
            {!! $errors->first('details', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('primary_image') }}
            {{ Form::text('primary_image', $work->primary_image, ['class' => 'form-control' . ($errors->has('primary_image') ? ' is-invalid' : ''), 'placeholder' => 'Primary Image']) }}
            {!! $errors->first('primary_image', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('images') }}
            {{ Form::text('images', $work->images, ['class' => 'form-control' . ($errors->has('images') ? ' is-invalid' : ''), 'placeholder' => 'Images']) }}
            {!! $errors->first('images', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('price') }}
            {{ Form::text('price', $work->price, ['class' => 'form-control' . ($errors->has('price') ? ' is-invalid' : ''), 'placeholder' => 'Price']) }}
            {!! $errors->first('price', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('avg_review_rating') }}
            {{ Form::text('avg_review_rating', $work->avg_review_rating, ['class' => 'form-control' . ($errors->has('avg_review_rating') ? ' is-invalid' : ''), 'placeholder' => 'Avg Review Rating']) }}
            {!! $errors->first('avg_review_rating', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('display_priority') }}
            {{ Form::text('display_priority', $work->display_priority, ['class' => 'form-control' . ($errors->has('display_priority') ? ' is-invalid' : ''), 'placeholder' => 'Display Priority']) }}
            {!! $errors->first('display_priority', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('work_status') }}
            {{ Form::text('work_status', $work->work_status, ['class' => 'form-control' . ($errors->has('work_status') ? ' is-invalid' : ''), 'placeholder' => 'Work Status']) }}
            {!! $errors->first('work_status', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('province_id') }}
            {{ Form::text('province_id', $work->province_id, ['class' => 'form-control' . ($errors->has('province_id') ? ' is-invalid' : ''), 'placeholder' => 'Province Id']) }}
            {!! $errors->first('province_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('work_type_id') }}
            {{ Form::text('work_type_id', $work->work_type_id, ['class' => 'form-control' . ($errors->has('work_type_id') ? ' is-invalid' : ''), 'placeholder' => 'Work Type Id']) }}
            {!! $errors->first('work_type_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('author_id') }}
            {{ Form::text('author_id', $work->author_id, ['class' => 'form-control' . ($errors->has('author_id') ? ' is-invalid' : ''), 'placeholder' => 'Author Id']) }}
            {!! $errors->first('author_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>