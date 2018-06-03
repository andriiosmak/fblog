{!! Form::open(['method' => 'DELETE', 'route' => [$route, $id], 'style' =>'inline-block']) !!}
{!! Form::submit(trans('labels.delete'), ['class' => 'btn btn-primary']) !!}
{!! Form::close() !!}