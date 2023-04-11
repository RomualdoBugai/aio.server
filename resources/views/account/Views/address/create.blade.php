@extends('template.default')
@section('title', 'cadastrar endereço')
@section('content')

    <div class="ui container">
        <h1>Cadastrar endereço</h1>
        @include("account::address.form-create")
        @include("account::address.form-create-postal-code-script")
        @include("account::address.form-create-submit")
    </div>

@stop
