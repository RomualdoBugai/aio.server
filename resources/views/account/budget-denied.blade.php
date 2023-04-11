@extends('template.mail', $template)

@section('content')

    <strong>
        {{ ownName(message('common', 'hello')) }} {!! ownName($user) !!}!
    </strong>
    <br>

    {!! message('common', 'mail.budget-denied.text-one', ['id' => $id, 'created_at' => Carbon\Carbon::parse(($created_at))->format(nationalDate())]) !!}

    <br>
    <br>

    {!! message('common', 'mail.budget-denied.text-two') !!}

    <br>
    <br>

@stop
    