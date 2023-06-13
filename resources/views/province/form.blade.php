<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('code') }}
            {{ Form::text('code', $province->code, ['class' => 'form-control' . ($errors->has('code') ? ' is-invalid' : ''), 'placeholder' => 'Code']) }}
            {!! $errors->first('code', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('name_th') }}
            {{ Form::text('name_th', $province->name_th, ['class' => 'form-control' . ($errors->has('name_th') ? ' is-invalid' : ''), 'placeholder' => 'Name Th']) }}
            {!! $errors->first('name_th', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('name_en') }}
            {{ Form::text('name_en', $province->name_en, ['class' => 'form-control' . ($errors->has('name_en') ? ' is-invalid' : ''), 'placeholder' => 'Name En']) }}
            {!! $errors->first('name_en', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('geography_id') }}
            {{ Form::text('geography_id', $province->geography_id, ['class' => 'form-control' . ($errors->has('geography_id') ? ' is-invalid' : ''), 'placeholder' => 'Geography Id']) }}
            {!! $errors->first('geography_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>