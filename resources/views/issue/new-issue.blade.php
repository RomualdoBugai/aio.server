@extends('template.mail')
@section('content')

    <h2>
        Olá {!! ownName($user->name) !!}
    </h2>

    <p>
        <h2>
            {!! ucwords($issue['name']) !!}
        </h2>
        <p>
            {{ $issue['text'] }}
        </p>
    </p>

@stop
