<form class="ui form" method="post" create-user-address action="/account/user/create-address" autocomplete="off">

    <div class="field four">
        <label>CEP</label>
        <div class="ui icon input">
            <input type="text" autocomplete-postal-code name="createUserAddress[postal_code]" required autocomplete="off" />
        </div>
    </div>

    <div class="four fields">

        <div class="six wide field">
            <label>Logradouro</label>
            <div class="ui input">
                <input type="text" data-address-item="logradouro" name="createUserAddress[street]" required />
            </div>
        </div>
        <div class="two wide field">
            <label>Número</label>
            <div class="ui input">
                <input type="text" name="createUserAddress[number]" />
            </div>
        </div>
        <div class="four wide  field">
            <label>Bairro</label>
            <div class="ui input">
                <input type="text" data-address-item="bairro" name="createUserAddress[district]" />
            </div>
        </div>

        <div class="four wide field">
            <label>Complemento</label>
            <div class="ui input">
                <input type="text" name="createUserAddress[complement]" />
            </div>
        </div>

    </div>

    <div class="two fields">

        <div class="four wide field">
            <label>Cidade</label>
            <div class="ui input">
                <input type="text" data-address-item="localidade" name="createUserAddress[city]" readonly />
            </div>
        </div>

        <div class="two wide field">
            <label>Estado</label>
            <div class="ui input">
                <input type="text" data-address-item="uf" name="createUserAddress[state]" readonly />
            </div>
        </div>

    </div>

    <input type="hidden" data-address-item="ibge" name="createUserAddress[ibge]" />

    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <input class="ui button" type="submit" name="createUserAddress[submit]" value="entrar" />

</form>
