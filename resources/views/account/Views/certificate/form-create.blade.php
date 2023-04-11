<form action="/account/user/certificate/execute" method="post" enctype="multipart/form-data" autocomplete="off">

    <input type="file" name="certificate" required />

    <input type="hidden" name="_token" value="{{ csrf_token() }}">


    <div class="field four">
        <label>Senha</label>
        <div class="ui icon input">
            <input type="password" name="createUserCertificate[password]" required placeholder="&bull;&bull;&bull;&bull;&bull;&bull;" autocomplete="off" />
        </div>
    </div>

    <input class="ui button" type="submit" name="createUserCertificate[submit]" value="entrar" />

</form>
