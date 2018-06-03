@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h3>{{ ucfirst($post->title) }}</h3>
                    <p>{{ $post->body }}</p>
                    <a href="{{ route('post.index') }}" class="btn btn-primary">{{ trans('labels.back') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
