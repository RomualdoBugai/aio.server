@extends('template.default')
@section('title', 'criar conta')
@section('content')
    <div class="ui container">
        <form class="ui form" method="post" create-account action="/account/user/create-account-from-invite">

            <h1>
                Agora é só definir sua senha.
            </h1>

            <p>

                Quem te convidou: {{ $invited->name }}
            </p>

            <div class="field">
                <label>Senha</label>
                <div class="ui icon labeled input">
                    <i class="icon-key icon"></i>
                    <input type="pasword" name="createUserAccount[password]" required />
                </div>
                <label id="password-strenght"></label>
            </div>

            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <input type="hidden" name="createUserAccount[invite]" value="{{ $invite->id }}">

            <input class="ui button" type="submit" name="createUserAccount[submit]" value="entrar" />

        </form>
    </div>

    <script type="text/javascript">
        $(function(){
            'use strict';

            var $password = $('input[name="createUserAccount[password]"]');
            var $passwordStrong = $("#password-strenght");

            $password.on('keyup', function()
            {
                checkStrength($(this).val());
            });

            function checkStrength(password)
            {
                var strength = 0
                if (password.length < 6)
                {
                    $passwordStrong
                    .removeClass()
                    .addClass('short');
                }
                if (password.length > 7) strength += 1
                if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/))  strength += 1
                if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/))  strength += 1
                if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/))  strength += 1
                if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1

                if (strength < 2 )
                {
                    $passwordStrong
                    .removeClass()
                    .addClass('weak')
                    .html("fraco");
                }
                else if (strength == 2 )
                {
                    $passwordStrong
                    .removeClass()
                    .addClass('good')
                    .html("bom");
                }
                else
                {
                    $passwordStrong
                    .removeClass()
                    .addClass('strong')
                    .html('forte');
                }
            }

        });
    </script>

@stop
