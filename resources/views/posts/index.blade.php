@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            @include('partials.errors')
            @if (\Session::has('success'))
                <div class="alert alert-success">
                    <ul>
                        <li>{!! \Session::get('success') !!}</li>
                    </ul>
                </div>
            @endif
        </div>
    </div>
    <div class="row justify-content-center list-item">
        <div class="col-md-8">
            <a href="{{ route('post.create') }}" class="btn btn-primary">{{ trans('labels.new') }}</a>
        </div>
    </div>
    @foreach ($posts as $post)
        <div class="row justify-content-center list-item">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h3>{{ ucfirst($post->title) }}</h3>
                        <p>{{ trans('labels.author') }}: {{ $post->user->name }}</p>
                        <p>{{ $post->description }}</p>
                        <div class="row buttons-row">
                            <a href="{{ route('post.show', ['id' => $post->id]) }}" class="btn btn-primary">{{ trans('labels.read_more') }}</a>
                            <a href="{{ route('post.edit', ['id' => $post->id]) }}" class="btn btn-primary">{{ trans('labels.edit') }}</a>
                            @include('partials.delete-button', ['id' => $post->id, 'route' => 'post.destroy'])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
