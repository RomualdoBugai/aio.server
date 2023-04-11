@extends('template.default')
@section('title', $title)

@section('title')
    <h1>{{ $title }}</h1>
@stop

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
        <div class="item" data-tab="more-options" popup data-content="{{ message('common', 'more') }}&nbsp;{{ message('common', 'options') }}" >
            <i class="icon-cog-5  icon"></i>
        </div>
    </div>
@stop

@section('content')

    <div class="ui tab active" data-tab="data">
        <div class="ui basic segment">

            <p>
                {{ message("common", "server-side-datetime") }}
                <br />
                <strong>{{ $timestamp['server'] }}</strong>
            </p>

            <p>
                {{ message("common", "client-side-datetime") }}
                <br />
                <strong><span id="me-index-current-timestamp"></span></strong>
            </p>
         
        </div>
        
    </div>

    <div class="ui tab" data-tab="more-options">
        <div class="ui basic segment">
            {!! $form !!}
        </div>
    </div>

@stop

@section('script')
    <script type="text/javascript">
        var dt      = new Date();
        var time    = ( dt.getHours() < 10 ? '0' + dt.getHours() : dt.getHours() ) + ":" + ( dt.getMinutes() < 10 ? '0' + dt.getMinutes() : dt.getMinutes() ) + ":" + ( dt.getSeconds() < 10 ? '0' + dt.getSeconds() : dt.getSeconds() );
        $("#me-index-current-timestamp").html(time);

                $("form#user-settings").onSubmit({
                    url         : '{{ route("me.update") }}',
                    method      : 'post',
                });
    </script>


@stop
