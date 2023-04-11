@extends('template.default')
@section('title', 'entrar no sistema')
@section('content')
    <div class="ui contianer">
        <form class="ui form" method="post" action="{{ route('logInSubmit') }}">

            <div class="ui field">
                <label>E-mail</label>
                <div class="ui input icon">
                    <i class="icon-mail-2 icon"></i>
                    <input type="text" name="logIn[email]" required />
                </div>
            </div>

            <div class="ui field">
                <label>Senha</label>
                <div class="ui input icon">
                    <i class="icon-lock icon"></i>
                    <input type="text" name="logIn[password]" required />
                </div>
            </div>

            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input class="ui button submit" type="submit" name="logIn[submit]" value="entrar" />

        </form>
    </div>
@stop
