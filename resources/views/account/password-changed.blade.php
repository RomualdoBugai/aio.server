@extends('template.mail', $template)

@section('content')

    <strong>
        {{ ownName(message('common', 'hi')) }} {!! ownName(firstName($user->name)) !!}!
    </strong>

    <br>
    <br>

    {!! message('common', 'mail.user-password-changed.text') !!}

@stop
