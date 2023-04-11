@extends('template.mail', $template)

@section('content')

    <strong>
        {{ ownName(message('common', 'hello')) }} {!! ownName($user) !!}!
    </strong>
    <br>

    {!! message('common', 'mail.budget-created.text-one') !!}

@stop
    