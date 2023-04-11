@extends('template.default')
@section('title', 'meus endereços')
@section('content')

    @if($addresses->status)
        @foreach ($addresses->data as $address)
            <div class="ui segment">
                <p>
                    {{ $address->street }} - {{ $address->number }}, {{ $address->district }}<br />
                    {{ $address->city }} - {{ $address->state }}
                </p>
            </div>
        @endforeach
    @else
        <p>
            Nenhum endereço foi encontrado.
        </p>
    @endif

    <a class="ui button green" href="/account/user/address/create">adicionar outro endereço</a>
@stop
