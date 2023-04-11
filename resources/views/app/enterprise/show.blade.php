@extends('template.default')
@section('title', $title)

@section('breadcrumb')
    @include("template.breadcrumb", ['breadcrumbs' => $breadcrumbs])
@stop

@section('header')

    <div class="ui grid basic segment margin top bottom none">
        <div class="thirteen wide column computer thirteen wide column tablet thirteen wide mobile column">
            <h2 style="font-size: 20px; line-height: 22px;" class="margin top bottom none">
                {{ ownName($enterprise['name']) }}
            </h2>
            @if($enterprise['national_code'] != null)
                {{ $enterprise['national_code'] }}
            @endif
        </div>
        <div class="three wide column computer three wide column tablet three wide mobile column right aligned">
            <h2 style="font-size: 20px; line-height: 22px;" class="margin top bottom none">
                {!! $forms['follow'] !!}
            </h2>
        </div>
    </div>

    <div class="ui menu secondary pointing fluid margin top bottom none border top dashed">
        <div class="item active" data-tab="data" popup data-content="{{ message('common', 'index') }}" >
            <i class="icon-doc-text icon"></i>
        </div>
        <div class="item" data-tab="follow-up" popup data-content="{{ message('common', 'follow-up') }}" >
            <i class="icon-comment-alt icon"></i>
        </div>
        <div class="item" data-tab="scheduling" popup data-content="{{ message('common', 'scheduling') }}" >
            <i class="icon-calendar icon"></i>
        </div>
        <div class="item" data-tab="attachment" popup data-content="{{ message('common', 'attachment') }}" >
            <i class="icon-attach-2 icon"></i>
        </div>
        <div class="item" data-tab="more-options" popup data-content="{{ message('common', 'more') }}&nbsp;{{ message('common', 'options') }}" >
            <i class="icon-cog-5 icon"></i>
        </div>
    </div>

@stop

@section('content')

    <div class="ui tab" data-tab="more-options">

        <div class="ui vertical menu fluid no-border">

            <div class="item" data-tab="enterprise.phone">
                <i class="icon-phone icon"></i>
                {{ message('phone', 'add') }}
            </div>

            <div class="item" data-tab="enterprise.email">
                <i class="icon-mail-2 icon"></i>
                {{ message('email', 'add') }}
            </div>

            <div class="item" data-tab="enterprise.address">
                <i class="icon-location icon"></i>
                {{ message('address', 'add') }}
            </div>

            <div class="item" data-tab="enterprise.person">
                <i class="icon-user icon"></i>
                {{ message('person', 'add') }}
            </div>

            <a class="item" href="{{ $actions['edit']['url'] }}">
                <i class="icon-edit icon"></i>
                {{ $actions['edit']['label'] }}
            </a>

            <a class="item" href="{{ $actions['status']['url'] }}">
                <i class="icon-flash icon"></i>
                {{ $actions['status']['label'] }}
            </a>

            <div class="item" data-tab="enterprise.log">
                <i class="icon-flash icon"></i>
                {{ message('common', 'log') }}
            </div>

        </div>

        <!-- enterprise phone -->
        <div class="ui tab" data-tab="enterprise.phone">
            <div class="ui basic segment">
                <h2 class="ui header">
                    <i class="icon-phone icon"></i>
                    <div class="content">
                        {{ message("phone", "title") }}
                    </div>
                </h2>
                <br />
                <div data-form="phone" data-url="{{ route("phone.insert") }}">
                    {!! $forms['phone'] !!}
                </div>
            </div>
        </div>

        <!-- enterprise email -->
        <div class="ui tab" data-tab="enterprise.email">
            <div class="ui basic segment">
                <h2 class="ui header">
                    <i class="icon-mail-2 icon"></i>
                    <div class="content">
                        {{ message("email", "title") }}
                    </div>
                </h2>
                <br />
                <div data-form="email" data-url="{{ route("email.insert") }}">
                    {!! $forms['email'] !!}
                </div>
            </div>
        </div>

        <!-- enterprise address -->
        <div class="ui tab" data-tab="enterprise.address">
            <div class="ui basic segment">
                <h2 class="ui header">
                    <i class="icon-home-1 icon"></i>
                    <div class="content">
                        {{ message("address", "title") }}
                    </div>
                </h2>
                <br />
                <div data-form="address" data-url="{{ route("address.insert") }}">
                    {!! $forms['address'] !!}
                </div>
                <div data-index="address" data-url="{{ route("address.index", ['controller' => 'enterprise', 'controller_id' => $enterprise['id']]) }}">
                </div>
            </div>
        </div>

        <!-- enterprise person -->
        <div class="ui tab" data-tab="enterprise.person">
            <div class="ui basic segment">
                <h2 class="ui header">
                    <i class="icon-home-1 icon"></i>
                    <div class="content">
                        {{ message("enterprise-person", "title") }}
                    </div>
                </h2>
                <br />
                <div data-form="person" data-url="{{ route("enterprise.person.insert") }}">
                    {!! $forms['person'] !!}
                </div>
                <div data-index="person" data-url="{{ route("enterprise.person.index", ['enterprise_id' => $enterprise['id']]) }}">
                </div>
            </div>
        </div>

    </div>

    <div class="ui tab" data-tab="scheduling">

        <div data-form="scheduling" data-url="{{ route("scheduling.insert") }}">
            {!! $forms['scheduling'] !!}
        </div>

        <section class="ui basic segment">
            <div data-index="scheduling" data-url="{{ route("scheduling.index", ['controller' => 'enterprise', 'controller_id' => $enterprise['id']]) }}">
            </div>
        </section>

    </div>

    <div class="ui tab" data-tab="attachment">

        <div class="ui basic segment" style="background: #eee">
            <div data-form="attachment" data-url="{{ route("attachment.insert") }}">
                {!! $forms['attachment'] !!}
            </div>
        </div>

        <section class="ui basic segment">
            <div data-index="attachment" data-url="{{ route("attachment.index", ['controller' => 'enterprise', 'controller_id' => $enterprise['id']]) }}">
            </div>
        </section>

    </div>

    <div class="ui tab active" data-tab="data">


        <div class="ui grid basic segment margin padding bottom top none">
            <div class="one wide column computer one wide column tablet one wide column mobile">
                <i class="icon-phone icon"></i>
            </div>
            <div class="twelve wide column computer twelve wide column tablet twelve wide column mobile">
                <div data-index="phone" data-url="{{ route("phone.index", ['controller' => 'enterprise', 'controller_id' => $enterprise['id']]) }}">
                </div>
            </div>
        </div>

        <div class="ui grid basic segment margin padding bottom top none">
            <div class="one wide column computer one wide column tablet one wide column mobile">
                <i class="icon-mail-2 icon"></i>
            </div>
            <div class="twelve wide column computer twelve wide column tablet twelve wide column mobile">
                <div data-index="email" data-url="{{ route("email.index", ['controller' => 'enterprise', 'controller_id' => $enterprise['id']]) }}">
                </div>
            </div>
        </div>

        <div class="ui grid basic segment margin padding bottom top none">
            <div class="one wide column computer one wide column tablet one wide column mobile">
                <i class="icon-users icon"></i>
            </div>
            <div class="twelve wide column computer twelve wide column tablet twelve wide column mobile">
                <div data-index="person" data-url="{{ route("enterprise.person.index", ['controller' => 'enterprise', 'controller_id' => $enterprise['id']]) }}">
                </div>
            </div>
        </div>

        <div class="ui grid basic segment margin padding bottom top none">
            <div class="one wide column computer one wide column tablet one wide column mobile">
                <br />
                <i class="icon-location icon"></i>
            </div>
            <div class="fourteen wide column computer fourteen wide column tablet fourteen wide column mobile">
                <div data-index="address" data-url="{{ route("address.index", ['controller' => 'enterprise', 'controller_id' => $enterprise['id']]) }}">
                </div>
            </div>
        </div>

        <div class="ui grid basic segment margin padding bottom top none">
            <div class="one wide column computer one wide column tablet one wide column mobile">
                <i class="icon-edit icon"></i>
            </div>
            <div class="fourteen wide column computer fourteen wide column tablet fourteen wide column mobile">
                {!! Carbon\Carbon::parse($enterprise['created_at'])->format(dateFormat()) !!}
                <strong>
                    {{ message('common', 'updated_at') }}
                </strong>
                {!! Carbon\Carbon::parse($enterprise['updated_at'])->format(dateFormat()) !!}
            </div>
        </div>

    </div>

    <div class="ui tab" data-tab="follow-up">

        <div class="ui basic segment" style="background: #eee">
            <div data-form="follow-up" data-url="{{ route("followUp.insert") }}">
                {!! $forms['followUp'] !!}
            </div>
        </div>

        <div class="ui basic segment">
            <div data-index="follow-up" data-url="{{ route("followUp.index", ['controller' => 'enterprise', 'controller_id' => $enterprise['id']]) }}">
            </div>
        </div>

    </div>

@stop

@section('script')

    <script type="text/javascript">

        $(function(){
            'use strict';

            $(".ui.accordion").accordion();
            $(".ui.dropdown").dropdown();

            $.fn.enterpriseForm = function() {
                return this.each(function(){
                    var $this       = $(this);
                    var controller  = $this.data('form');
                    $(this).find("form").onSubmit({
                        url         : $this.data('url'),
                        method      : 'post',
                        success: function() {
                            $this.find('[data-value]').val('');
                            $('[data-index="' + controller + '"]').enterpriseIndex();
                        }
                    });
                });
            }

            $.fn.enterpriseIndex = function() {
                return this.each(function(){
                    var $this = $(this);
                    var controller = $this.data('index');
                    $.ajax({
                        url     : $this.data('url'),
                        type    : 'get',
                        async   : true,
        				cache   : false,
                        success: function(response){
                            $('[data-index="' + controller + '"]').html(response);
                        }
                    });
                });
            };

            $("[data-form]").enterpriseForm();
            $("[data-index]").enterpriseIndex();
            $("[data-folloing]").enterpriseIndex();

        });
    </script>

@stop
