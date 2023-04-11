@extends('template.mail', $template)

@section('content')

    <br />
    <br />

    {!! message('issue', 'notification.support.text') !!}

    <br />
    <br />

    <p>

        <strong>
            {{ $app->name }}
        </strong>

        <strong>
            {{ $issue['name'] }}
        </strong>
        <br />
        {{ $issue['text'] }}

        <br />

        {!! ownName($user->name) !!}

    </p>

    {!! message('issue', 'notification.support.extra') !!}

@stop
