@extends('template.default')
@section('title', $title)

@section('breadcrumb')
    @include("template.breadcrumb", ['breadcrumbs' => $breadcrumbs])
@stop

@section('content')

    <div class="ui secondary pointing menu margin bottom none fluid blue">
        <div class="item active">
            <i class="icon-users icon"></i>&nbsp;
            {{ message('common', 'index') }}
        </div>
        <a class="item" href="{{ route("bankAccount.new") }}">
            <i class="icon-doc-new icon"></i>&nbsp;
            {{ message('common', 'new') }}
        </a>
    </div>

    <div class="ui basic segment margin top bottom none">
        <div class="ui form">
            <div class="field">
                <div class="ui input icon">
                    <i class="icon-search-1 icon circular"></i>
                    <input type="search" placeholder="{{ message('common', 'search') }}" />
                </div>
            </div>
        </div>
    </div>

    <div class="ui basic segment margin top none">
        <div class="ui vertical menu fluid">
        @foreach($bankAccounts as $bankAccount)
            <a class="item" href="{{ route('bankAccount.show', ['id' => $bankAccount['id']]) }}" title="{{ message('bankAccount', 'bankAccount.show') }}">
                <strong>{{ $bankAccount['name'] }}</strong><br />
                {{ $bankAccount['bank']['name'] }}
            </a>
        @endforeach
        </div>
    </div>

@stop

@section('script')

@stop
