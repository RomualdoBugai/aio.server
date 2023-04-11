
    <form class="ui form" method="post" create-account action="/account/user/create-account">

        <h1>
            Crie sua conta agora, é grátis por 30 dias
        </h1>

        <p>
            Preencha os campos abaixo
        </p>

        <div class="field">
            <label>Nome</label>
            <div class="ui icon input">
                <i class="icon-user icon"></i>
                <input type="text" name="createUserAccount[name]" required />
            </div>
        </div>

        <div class="field">
            <label>E-mail</label>
            <div class="ui icon input">
                <i class="icon-mail icon"></i>
                <input type="email" check-mail-address name="createUserAccount[email]" required />
            </div>
        </div>

        <div class="field">
            <label>Senha</label>
            <div class="ui icon labeled input">
                <i class="icon-key icon"></i>
                <input type="pasword" name="createUserAccount[password]" required />
            </div>
            <label id="password-strenght"></label>
        </div>

        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <input class="ui button" type="submit" name="createUserAccount[submit]" value="entrar" />

    </form>
</div>

<script type="text/javascript">
    $(function(){
        var token = $('input[name="_token"]').val();

        var $form = $("[create-account]");

        $form.on("submit", function(event){
            var $this = $(this);
            var url = $this.attr("action");
            var method = $this.attr("method");
            $.ajax({
                url : url,
                type: method,
                data: $this.serializeArray(),
                datatype: 'json',
                headers: {'X-CSRF-TOKEN': token},
                async   : true,
                cache   : false,
                beforeSend: function(){

                },
                success: function(response){
                    console.log(response);
                }
            });
            return false;
        });

        var $ok = $("[confirm-account]");

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
