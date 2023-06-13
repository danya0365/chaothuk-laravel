<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('title') }}
            {{ Form::text('title', $recruit->title, ['class' => 'form-control' . ($errors->has('title') ? ' is-invalid' : ''), 'placeholder' => 'Title']) }}
            {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('description') }}
            {{ Form::text('description', $recruit->description, ['class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''), 'placeholder' => 'Description']) }}
            {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('primary_image') }}
            {{ Form::text('primary_image', $recruit->primary_image, ['class' => 'form-control' . ($errors->has('primary_image') ? ' is-invalid' : ''), 'placeholder' => 'Primary Image']) }}
            {!! $errors->first('primary_image', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('images') }}
            {{ Form::text('images', $recruit->images, ['class' => 'form-control' . ($errors->has('images') ? ' is-invalid' : ''), 'placeholder' => 'Images']) }}
            {!! $errors->first('images', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('budget') }}
            {{ Form::text('budget', $recruit->budget, ['class' => 'form-control' . ($errors->has('budget') ? ' is-invalid' : ''), 'placeholder' => 'Budget']) }}
            {!! $errors->first('budget', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('display_priority') }}
            {{ Form::text('display_priority', $recruit->display_priority, ['class' => 'form-control' . ($errors->has('display_priority') ? ' is-invalid' : ''), 'placeholder' => 'Display Priority']) }}
            {!! $errors->first('display_priority', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('recruit_status') }}
            {{ Form::text('recruit_status', $recruit->recruit_status, ['class' => 'form-control' . ($errors->has('recruit_status') ? ' is-invalid' : ''), 'placeholder' => 'Recruit Status']) }}
            {!! $errors->first('recruit_status', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('province_id') }}
            {{ Form::text('province_id', $recruit->province_id, ['class' => 'form-control' . ($errors->has('province_id') ? ' is-invalid' : ''), 'placeholder' => 'Province Id']) }}
            {!! $errors->first('province_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('work_type_id') }}
            {{ Form::text('work_type_id', $recruit->work_type_id, ['class' => 'form-control' . ($errors->has('work_type_id') ? ' is-invalid' : ''), 'placeholder' => 'Work Type Id']) }}
            {!! $errors->first('work_type_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('author_id') }}
            {{ Form::text('author_id', $recruit->author_id, ['class' => 'form-control' . ($errors->has('author_id') ? ' is-invalid' : ''), 'placeholder' => 'Author Id']) }}
            {!! $errors->first('author_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>