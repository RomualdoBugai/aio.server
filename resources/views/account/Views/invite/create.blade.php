@extends('template.default')
@section('title', 'convidar usuário conta')
@section('content')

    <div class="ui container">


        <form class="ui form" method="post" create-account action="/account/user/invite/store">

            <h1>
                Adicionar conta de usuário vinculado a esta conta
            </h1>

            <p>
                Preencha os campos abaixo
            </p>

            <div class="field">
                <label>Nome</label>
                <div class="ui icon input">
                    <i class="icon-user icon"></i>
                    <input type="text" name="createUserInvite[name]" required />
                </div>
            </div>

            <div class="field">
                <label>E-mail</label>
                <div class="ui icon input">
                    <i class="icon-mail icon"></i>
                    <input type="email" check-mail-address name="createUserInvite[email]" required />
                </div>
            </div>

            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <input class="ui button" type="submit" name="createUserAccount[submit]" value="entrar" />

        </form>

    </div>

        <script type="text/javascript">
            $(function(){
                var token = $('input[name="_token"]').val();

                $("[check-mail-address]").on('blur', function(event){
                    var $this = $(this);
                    var value = $this.val();

                    if (value.length < 8)
                    {
                        return false;
                    }
                    $.ajax({
                        url : "/account/user/check-unique-mail-address",
                        type: "POST",
                        data: {
                            email : value
                        },
                        datatype: 'JSON',
                        headers: {'X-CSRF-TOKEN': token},
                        beforeSend: function(){

                        },
                        success: function(response){
                            if (response.status == false)
                            {
                                $this.val("");
                                $this
                                .closest("form")
                                .append("<div id=\"mail-check\"/>")
                                .find("#mail-check")
                                .html(response.message);
                            }
                        }
                    });

                });

            });
        </script>





    </div>

@stop
