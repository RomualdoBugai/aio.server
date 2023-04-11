@extends('template.mail')
@section('content')

    <h2>
        {{ ownName(message('common', 'hi')) }}, {{ ownName($user->name) }}
    </h2>

    <p>
    	<h3>
    		{{ $scheduling->title }}
    	</h3>
    	{{ $scheduling->description }}
    </p>

@stop
