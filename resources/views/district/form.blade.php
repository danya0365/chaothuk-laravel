<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('code') }}
            {{ Form::text('code', $district->code, ['class' => 'form-control' . ($errors->has('code') ? ' is-invalid' : ''), 'placeholder' => 'Code']) }}
            {!! $errors->first('code', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('name_th') }}
            {{ Form::text('name_th', $district->name_th, ['class' => 'form-control' . ($errors->has('name_th') ? ' is-invalid' : ''), 'placeholder' => 'Name Th']) }}
            {!! $errors->first('name_th', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('name_en') }}
            {{ Form::text('name_en', $district->name_en, ['class' => 'form-control' . ($errors->has('name_en') ? ' is-invalid' : ''), 'placeholder' => 'Name En']) }}
            {!! $errors->first('name_en', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('province_id') }}
            {{ Form::text('province_id', $district->province_id, ['class' => 'form-control' . ($errors->has('province_id') ? ' is-invalid' : ''), 'placeholder' => 'Province Id']) }}
            {!! $errors->first('province_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>