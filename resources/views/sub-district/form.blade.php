<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('zip_code') }}
            {{ Form::text('zip_code', $subDistrict->zip_code, ['class' => 'form-control' . ($errors->has('zip_code') ? ' is-invalid' : ''), 'placeholder' => 'Zip Code']) }}
            {!! $errors->first('zip_code', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('name_th') }}
            {{ Form::text('name_th', $subDistrict->name_th, ['class' => 'form-control' . ($errors->has('name_th') ? ' is-invalid' : ''), 'placeholder' => 'Name Th']) }}
            {!! $errors->first('name_th', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('name_en') }}
            {{ Form::text('name_en', $subDistrict->name_en, ['class' => 'form-control' . ($errors->has('name_en') ? ' is-invalid' : ''), 'placeholder' => 'Name En']) }}
            {!! $errors->first('name_en', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('amphure_id') }}
            {{ Form::text('amphure_id', $subDistrict->amphure_id, ['class' => 'form-control' . ($errors->has('amphure_id') ? ' is-invalid' : ''), 'placeholder' => 'Amphure Id']) }}
            {!! $errors->first('amphure_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>