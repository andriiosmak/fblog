@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ trans('labels.edit_post') }}</div>
                <div class="card-body">
                    <div class="col-md-12">
                        @include('partials.errors')
                    </div>

                    {{ Form::open(['route' => ['post.update', $post->id]]) }}
                    {{ Form::hidden('_method', 'put') }}

                    <div class="form-group row">
                        {{ Form::label('title', trans('labels.title'), ['class' => 'col-sm-4 text-md-right']) }}
                        <div class="col-md-6">
                            {{ Form::text('title', $post->title) }}
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ Form::label('description', trans('labels.description'), ['class' => 'col-sm-4 text-md-right']) }}
                        <div class="col-md-6">
                            {{ Form::text('description', $post->description) }}
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ Form::label('body', trans('labels.body'), ['class' => 'col-sm-4 text-md-right']) }}
                        <div class="col-md-6">
                            {{ Form::textarea('body', $post->body) }}
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-11 text-md-right">
                            <a href="{{ route('post.index') }}" class="btn btn-primary">{{ trans('labels.back') }}</a>
                            {{ Form::submit(trans('labels.submit'), ['class' => 'btn btn-primary']) }}
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
