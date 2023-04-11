@extends('template.default')
@section('title', 'confirmar')
@section('content')

<div class="ui container">

    <form action="/account/user/certificate/done" method="post" autocomplete="off">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="ui segment">
            Empresa
            <h1>
                {{ $enterprise->name }}
            </h1>
            <p>
                {{ $enterprise->fantasy_name }}
            </p>
            <p>
                {{ $enterprise->national_code }}
            </p>
        </div>

        <div class="ui segment">
            {{ $address->street }} - {{ $address->number }}, {{ $address->district }}<br />
            {{ $address->city }} - {{ $address->state }}<br />
            CEP {{ $address->postal_code }}<br />
        </div>

        <div class="ui segment">

            <div class="ui message">
                Irá expirar em {{ $valid }} dia(s).
            </div>

            {{ $certificate->name }}<br />
            {{ $certificate->password }}<br />
            {{ $certificate->valid_from }}<br />
            {{ $certificate->valid_to }}<br />

        </div>


        <input class="ui button" type="submit" name="checkUserCertificate[submit]" value="entrar" />

    </form>

</div>

@stop
