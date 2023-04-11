@extends('template.mail', $template)

@section('content')

    <strong>
        {{ ownName(message('common', 'hello')) }} {!! ownName($user->name) !!}!
    </strong>
    
    <br>
    <br>

    {!! message('common', 'mail.certificate-notify.text-one', ['name' => ownName($enterprise->name), 'valid_to' => Carbon\Carbon::parse($certificate->valid_to)->format(nationalDate())]) !!}

    <br>
    <br>

    {!! message('common', 'mail.certificate-notify.text-two') !!}

@stop
	