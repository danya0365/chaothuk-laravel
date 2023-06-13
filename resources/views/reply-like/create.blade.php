@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Reply Like
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Create') }} Reply Like</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('reply-likes.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('reply-like.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
