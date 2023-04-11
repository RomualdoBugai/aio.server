@extends('template.mail', $template)

@section('content')

    <strong>
        {{ ownName(message('common', 'hi')) }} {!! ownName($user->name) !!}!
    </strong>

    <br />
    <br />

    {!! message('issue', 'notification.user.text') !!}

    <br />
    <br />

    <p>

        <strong>
            {{ $issue['name'] }}
        </strong>
        <br />
        {{ $issue['text'] }}

    </p>

    {!! message('issue', 'notification.user.extra') !!}

@stop
