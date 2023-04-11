@extends('template.default')
@section('title', $title)

@section('breadcrumb')
    @include("template.breadcrumb", ['breadcrumbs' => $breadcrumbs])
@stop

@section("header")

    <div class="ui grid basic segment margin top bottom none">
        <div class="thirteen wide column computer thirteen wide column tablet thirteen wide mobile column">
            <h2 style="font-size: 20px; line-height: 22px;" class="margin top bottom none">
                {{ ownName($bank_account['name']) }}
            </h2>
        </div>
    </div>

    <div class="ui menu secondary pointing fluid blue margin top bottom none border top dashed">
        <div class="item active" data-tab="data" popup data-content="{{ message('common', 'index') }}" >
            <i class="icon-doc-text  icon"></i>
        </div>
        <div class="item" data-tab="more-options" popup data-content="{{ message('common', 'more') }}&nbsp;{{ message('common', 'options') }}" >
            <i class="icon-cog-5  icon"></i>
        </div>
    </div>

@stop

@section('content')

    <div class="ui tab" data-tab="more-options">
        <div class="ui vertical menu fluid no-border">
            <a class="item" href="{{ $actions['edit']['url'] }}">
                <i class="icon-edit icon"></i>
                {{ message('common', 'edit') }}
            </a>
        </div>
    </div>

    <div class="ui tab active" data-tab="data">
        <div class="ui basic segment">

             <div class="ui grid">
                <div class="ui sixteen wide column mobile">
                    <p>
                        {{ message('bank-account', 'form.attributes.bank_id') }}
                        <br />
                        <strong>{!! $bank_account['bank']['name'] !!}</strong>
                    </p>
                </div>
            </div>
         
            <div class="ui grid">
                <div class="ui eight wide column mobile">
                    <p>
                        {{ message('bank-account', 'form.attributes.opening_at') }}
                        <br />
                        <strong>{!! Carbon\Carbon::parse($bank_account['opening_at'])->format(dateFormat()) !!}</strong>
                    </p>
                </div>
                <div class="ui eight wide column mobile">
                    <p>
                        {{ message('bank-account', 'form.attributes.opening_balance') }}
                        <br />
                        <strong>{{ $bank_account['opening_balance'] }}</strong>
                    </p>
                </div>
            </div>

            <div class="ui grid">
                <div class="ui eight wide column mobile">
                    <p>
                        {{ message('bank-account', 'form.attributes.agency_number') }}
                        <br />
                        <strong>{{ $bank_account['agency_number'] }}</strong>
                        -
                        <strong>{{ $bank_account['agency_number_digit'] }}</strong>
                    </p>
                </div>
                <div class="ui eight wide column mobile">
                    <p>
                        {{ message('bank-account', 'form.attributes.account_number') }}
                        <br />
                        <strong>{{ $bank_account['account_number'] }}</strong>
                        -
                        <strong>{{ $bank_account['account_number_digit'] }}</strong>
                    </p>
                </div>
            </div>

            <div class="ui grid">
                <div class="ui sixteen wide column mobile">
                    <p>
                        <small>
                            {{ message('common', 'created_at') }}
                            <strong>{!! Carbon\Carbon::parse($bank_account['created_at'])->format(dateFormat()) !!}</strong>
                            {{ message('common', 'updated_at') }}
                            <strong>{!! Carbon\Carbon::parse($bank_account['updated_at'])->format(dateFormat()) !!}</strong>
                        </small>
                    </p>
                </div>
            </div>

        </div>
    </div>

@stop

@section('script')
    <script type="text/javascript">
        $(function(){
            'use strict';
        });
    </script>
@stop
