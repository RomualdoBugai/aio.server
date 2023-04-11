@extends('template.default')
@section('title', $title)

@section('breadcrumb')
    @include("template.breadcrumb", ['breadcrumbs' => $breadcrumbs])
@stop

@section("header")

    <div class="ui grid basic segment margin top bottom none">
        <div class="thirteen wide column computer thirteen wide column tablet thirteen wide mobile column">
            <h2 style="font-size: 20px; line-height: 22px;" class="margin top bottom none">
                {{ $expense['name'] }}
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
                {{ $actions['edit']['label'] }}
            </a>

            <a class="item" href="{{ $actions['status']['url'] }}">
                <i class="icon-flash icon"></i>
                {{ $actions['status']['label'] }}
            </a>

            @if($actions['close']['visible'] == true)
            <a class="item" href="{{ $actions['close']['url'] }}">
                <i class="icon-thumbs-up icon"></i>
                {{ $actions['close']['label'] }}
            </a>
            @endif
        </div>
    </div>

    <div class="ui tab active" data-tab="data">
        <div class="ui basic segment">

            <div class="ui grid">
                <div class=" sixteen wide column mobile">
                    <p>
                        {{ message('expense', 'form.attributes.description') }}
                        <br />
                        <strong>{!! $expense['description'] !!}</strong>
                    </p>
                </div>
            </div>
         
            <div class="ui grid">
                <div class="eight wide column mobile">
                    <p>
                        {{ message('expense', 'form.attributes.due_date_at') }}
                        <br />
                        <strong>{!! Carbon\Carbon::parse($expense['due_date_at'])->format(dateFormat()) !!}</strong>
                    </p>
                </div>
                <div class="eight wide column mobile">
                    <p>
                        {{ message('expense', 'form.attributes.bank_account_id') }}
                        <br />
                        <strong>{!! $expense['bank_account']['name'] !!}</strong>
                    </p>
                </div>
            </div>

            <div class="ui grid">
                <div class="eight wide column mobile">
                    <p>
                        {{ message('expense', 'form.attributes.currency_id') }}
                        <br />
                        <strong>{!! $expense['currency']['name'] !!}</strong>
                    </p>
                </div>
                <div class="eight wide column mobile">
                    <p>
                        {{ message('expense', 'form.attributes.amount') }}
                        <br />
                        <strong>{{ $expense['amount'] }}</strong>
                    </p>
                </div>
            </div>

            <div class="ui grid">
                <div class="eight wide column mobile">
                    <p>
                        {{ message('common', 'created_at') }}
                        <br />
                        <strong>{!! Carbon\Carbon::parse($expense['created_at'])->format(dateFormat()) !!}</strong>
                    </p>
                </div>
                <div class="eight wide column mobile">
                    <p>
                        {{ message('common', 'updated_at') }}
                        <br />
                        <strong>{!! Carbon\Carbon::parse($expense['updated_at'])->format(dateFormat()) !!}</strong>
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
