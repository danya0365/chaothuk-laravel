@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} District
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Create') }} District</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('districts.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('district.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
