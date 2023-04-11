@extends('template.mail')
@section('content')

    <h1>
        Olá {{ $invite->name }}
    </h1>

    <p>
        Eu, {{ $invited->name }} estou convidando você a compartilhar dos recursos da minha conta no LOGFISCAL.
    </p>

    <p>
        Clique <a href="{{ $url }}">aqui</a> para confirmação a criação da sua conta.
    </p>
@stop
