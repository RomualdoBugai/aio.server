@extends('template.default')
@section('title', $title)

@section('breadcrumb')
    @include("template.breadcrumb", ['breadcrumbs' => $breadcrumbs])
@stop

@section('header')

    <div class="ui grid basic segment margin top bottom none">
        <div class="thirteen wide column computer thirteen wide column tablet thirteen wide mobile column">
            <h2 style="font-size: 20px; line-height: 22px;" class="margin top bottom none">
                {{ ownName($user['name']) }}
            </h2>
            {{ $user['email'] }}
        </div>
    </div>

    <div class="ui menu secondary pointing fluid blue margin top bottom none" style="border-top: dashed 1px #eee">
        <div class="item active" data-tab="data" popup data-content="{{ message('common', 'index') }}" >
            <i class="icon-doc-text  icon"></i>
        </div>
        <div class="item" data-tab="scheduling" popup data-content="{{ message('common', 'scheduling') }}" >
            <i class="icon-calendar  icon"></i>
        </div>
        <div class="item" data-tab="more-options" popup data-content="{{ message('common', 'more') }}&nbsp;{{ message('common', 'options') }}" >
            <i class="icon-cog-5  icon"></i>
        </div>
    </div>

@stop

@section('content')

    <div class="ui tab" data-tab="more-options">

        <div class="ui vertical menu fluid no-border">

            <div class="item" data-tab="user.phone">
                <i class="icon-phone icon"></i>
                {{ message('phone', 'add') }}
            </div>

            <div class="item" data-tab="user.address">
                <i class="icon-location icon"></i>
                {{ message('address', 'add') }}
            </div>

        </div>

        <!-- user phone -->
        <div class="ui tab" data-tab="user.phone">
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

        <!-- user address -->
        <div class="ui tab" data-tab="user.address">
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
                <div data-index="address" data-url="{{ route("address.index", ['controller' => 'user', 'controller_id' => $user['id']]) }}">
                </div>
            </div>
        </div>

    </div>

    <div class="ui tab" data-tab="scheduling">

        <div data-form="scheduling" data-url="{{ route("scheduling.insert") }}">
            {!! $forms['scheduling'] !!}
        </div>

        <section class="ui basic segment">
            <div data-index="scheduling" data-url="{{ route("scheduling.index", ['controller' => 'user', 'controller_id' => $user['id']]) }}">
            </div>
        </section>

    </div>

    <div class="ui tab active" data-tab="data">


        <div class="ui grid basic segment margin padding bottom top none">
            <div class="one wide column computer one wide column tablet one wide column mobile">
                <i class="icon-phone icon"></i>
            </div>
            <div class="twelve wide column computer twelve wide column tablet twelve wide column mobile">
                <div data-index="phone" data-url="{{ route("phone.index", ['controller' => 'user', 'controller_id' => $user['id']]) }}">
                </div>
            </div>
        </div>

        <div class="ui grid basic segment margin padding bottom top none">
            <div class="one wide column computer one wide column tablet one wide column mobile">
                <br />
                <i class="icon-location icon"></i>
            </div>
            <div class="fourteen wide column computer fourteen wide column tablet fourteen wide column mobile">
                <div data-index="address" data-url="{{ route("address.index", ['controller' => 'user', 'controller_id' => $user['id']]) }}">
                </div>
            </div>
        </div>

        <div class="ui grid basic segment margin padding bottom top none">
            <div class="one wide column computer one wide column tablet one wide column mobile">
                <i class="icon-edit icon"></i>
            </div>
            <div class="fourteen wide column computer fourteen wide column tablet fourteen wide column mobile">
                {!! Carbon\Carbon::parse($user['created_at'])->format(dateFormat()) !!}
                <strong>
                    {{ message('common', 'updated_at') }}
                </strong>
                {!! Carbon\Carbon::parse($user['updated_at'])->format(dateFormat()) !!}
            </div>
        </div>

                


                    


    </div>

@stop

@section('script')

    <script type="text/javascript">

        $(function(){
            'use strict';

            $.fn.userForm = function() {
                return this.each(function(){
                    var $this       = $(this);
                    var controller  = $this.data('form');
                    $(this).find("form").onSubmit({
                        url         : $this.data('url'),
                        method      : 'post',
                        success: function() {
                            $this.find('[data-value]').val('');
                            $('[data-index="' + controller + '"]').userIndex();
                        }
                    });
                });
            }

            $.fn.userIndex = function() {
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

            $("[data-form]").userForm();
            $("[data-index]").userIndex();

            $("[data-folloing]").userIndex();


        });
    </script>


@stop
