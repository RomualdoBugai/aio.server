@extends('template.default')
@section('title', $title)

@section('breadcrumb')
    @include("template.breadcrumb", ['breadcrumbs' => $breadcrumbs])
@stop

@section('header')
    <div class="ui vertical fluid menu no-border margin top bottom none">

        <div class="item active" data-tab="index">
            <i class="icon-cog-1 icon"></i>
            {{ message('setup', 'index') }}
        </div>

        <div class="item" data-tab="inviteUser.new">
            <i class="icon-user-add icon"></i>
            {{ message('invite-user', 'new') }}
        </div>

        <div class="item" data-tab="inviteUser.open">
            <i class="icon-paper-plane-1 icon"></i>
            {{ message('invite-user', 'open') }}
        </div>

        <div class="item" data-tab="app">
            <i class="icon-database icon"></i>
            {{ message('app', 'edit') }}
        </div>

        <div class="item" data-tab="services">
            <i class="icon-tools icon"></i>
            {{ message('services', 'index') }}
        </div>

        <div class="item" data-tab="user">
            <i class="icon-users icon"></i>
            {{ message('user', 'index') }}
        </div>

    </div>
@stop

@section('content')

    <div class="ui tab" data-tab="user">
        <div class="ui basic segment">
            <div data-index="user" data-url="{{ route("user.index") }}">
            </div>
        </div>
    </div>

    <div class="ui tab" data-tab="services">
        <div class="ui vertical fluid menu no-border inverted red">
            <a class="item" data-service data-url="{{ route('service.cacheClear') }}">
                <i class="icon-trash-1 icon"></i>
                {{ message('setup', 'clear-cache') }}
            </a>
            <a class="item" data-service data-url="{{ route('service.schedule') }}">
                <i class="icon-mail-2 icon"></i>
                {{ message('services', 'schedule') }}
            </a>
        </div>
    </div>

    <div class="ui tab active" data-tab="index">
        <div class="ui basic segment">
            {{ message('setup', 'index') }}
        </div>
    </div>

    <div class="ui tab" data-tab="inviteUser.new">
        <div class="ui basic segment">
            <div data-form="inviteUser" data-url="{{ route("inviteUser.insert") }}">
                {!! $form['inviteUser'] !!}
            </div>
        </div>
    </div>

    <div class="ui tab" data-tab="inviteUser.open">
        <div class="ui basic segment inverted teal">
            <div data-index="inviteUser" data-url="{{ route("inviteUser.index") }}">
            </div>
        </div>
    </div>

    <div class="ui tab" data-tab="app">
        <div class="ui basic segment">

        </div>
    </div>

@stop

@section('script')


        <script type="text/javascript">

        $(function(){
            'use strict';

            $.fn.setupForm = function() {
                return this.each(function(){
                    var $this       = $(this);
                    var controller  = $this.attr('href');
                    $(this).find("form").onSubmit({
                        url         : $this.data('url'),
                        method      : 'post',
                        success: function() {
                            $this.find('[data-value]').val('');
                            $('[data-index="' + controller + '"]').setupIndex();
                        }
                    });
                });
            }

            $.fn.setupIndex = function() {
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

            $("[data-form]").setupForm();
            $("[data-index]").setupIndex();


            $.fn.executeService = function() {
                return this.each(function(){
                    var $this       = $(this);
                    var url = $this.data('url');


                    $this.on('click', function(event){
                        $.ajax({
                            dataType: 'json',
                            url     : url,
                            type    : 'GET',
                            headers : {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                            async   : true,
                            cache   : false,
                            beforeSend: function(){
                                $.loading();
                            },
                            success: function(response){
                                $.loading();
                                $.flash({
                                    class   : (response.status == true ? 'green' : 'red'),
                                    content : response.message
                                });
                            }
                        });
                        return false;
                    });
                });
            }

            $("[data-service]").executeService();

        });
    </script>

@stop
