<!DOCTYPE HTML>
<html lang="en-US">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />

        <title>LUMBEX @yield('title')</title>

        <link href="{{{ URL::asset('semantic-ui/semantic.css') }}}?<?php echo date('Ymdhis'); ?>" rel="stylesheet" type="text/css">
        <link href="{{{ URL::asset('fontello/css/fontello.css') }}}" rel="stylesheet" type="text/css">
        <link href="{{{ URL::asset('plugins/app/loading/css/loading.css') }}}" rel="stylesheet" type="text/css">
        <link href="{{{ URL::asset('plugins/app/flash/css/flash.css') }}}" rel="stylesheet" type="text/css">
        <link href="{{{ URL::asset('plugins/flag-icon-css-master/css/flag-icon.min.css') }}}" rel="stylesheet" type="text/css">
        <link href="{{{ URL::asset('plugins/clockpicker/clockpicker.css') }}}" rel="stylesheet" type="text/css">
        <link href="{{{ URL::asset('plugins/clockpicker/standalone.css') }}}" rel="stylesheet" type="text/css">
        <link href="{{{ URL::asset('plugins/datetimepicker/jquery.datetimepicker.css') }}}" rel="stylesheet" type="text/css">

        <style type="text/css">


            html {
                background: #fff
            }


            * {
                border-radius: 0px !important;
                box-shadow: none !important;
            }

        	.ui.form .grid .row {
        		padding: 0px !important;
        		margin-bottom: 5px !important;
        	}

            .ui.breadcrumb a.section {
                color: #fff;
            }

            .margin.bottom.none {
                margin-bottom: 0;
            }

            .margin.top.none {
                margin-top: 0;
            }

            .no-border {
                border: none !important
            }

            .padding.bottom.none {
                padding-bottom: 0 !important;
            }

            .padding.top.none {
                padding-top: 0 !important;
            }

            .text.center {
                text-align: center;
            }

            .border.top.dashed {
                border-top: dashed 1px #ddd
            }

        </style>

    </head>
    <body>

        <div class="ui left vertical sidebar push menu no-border">

            <div class="header item">
                LUMBEX<br />
                <small style="font-weight: normal">
                    Ver. 1.1.0 beta
                </small>
            </div>

            <div class="item active" data-tab="main">
                <i class="icon-location icon"></i>
                {{ message('common', 'main') }}
            </div>

            <div class="item" data-tab="language">
                <i class="icon-globe icon"></i>
                {{ message('common', 'language') }}
            </div>

        </div>

        <div class="ui pusher">

            <div class="ui grid basic segment" style="margin-top: 0; margin-bottom: 0;">
                <div class="one wide column computer two wide column tablet two wide column mobile">
                    <div id="sidebar" class="ui toggle">
                        <i class="icon-menu-1"></i>
                    </div>
                </div>
                <div class="eight wide computer fourteen wide tablet fourteen wide mobile column">
                    @yield('page.title')
                    @yield('breadcrumb')
                </div>
            </div>

            <div class="ui tab active" data-tab="main">

                @yield('header')

                <div class="four wide computer sixteen wide tablet sixteen wide mobile column">
                    @yield('lateral')
                </div>
                <div class="twelve wide computer sixteen wide tablet sixteen wide mobile column">
                    @yield('content')
                </div>

            </div>

            <div class="ui tab" data-tab="language">

                <div class="ui basic segment">

                    <div class="ui header">
                        {{ message("common", "change-language") }}
                    </div>

                    @php ($routePrefixes = explode("/", \Request::route()->getPrefix()))
                    @php ($language      = $routePrefixes[0])

                    <div class="ui menu vertical fluid">
                        <a class="item {{ ($language == "en" ? "disabled" : null ) }}" href="{{ route('changeLanguage', ['language' => 'en', 'url' => str_replace("/", "+", str_replace(url('/') . "/", "", Request::url()))  ]) }}">
                            <span class="flag-icon flag-icon-us"></span>
                            &nbsp;
                            {{ message("common", "english") }}
                        </a>
                        <a class="item {{ ($language == "pt" ? "disabled" : null ) }}" href="{{ route('changeLanguage', ['language' => 'pt', 'url' => str_replace("/", "+", str_replace(url('/') . "/", "", Request::url()))  ]) }}">
                            <span class="flag-icon flag-icon-br"></span>
                            &nbsp;
                            {{ message("common", "portuguese") }}
                        </a>
                        <a class="item disabled {{ ($language == "es" ? "disabled" : null ) }}" href="{{ route('changeLanguage', ['language' => 'es', 'url' => str_replace("/", "+", str_replace(url('/') . "/", "", Request::url()))  ]) }}">
                            <span class="flag-icon flag-icon-es"></span>
                            &nbsp;
                            {{ message("common", "spanish") }}
                        </a>
                    </div>

                </div>

            </div>


        </div>

        @include('template.script')
        @include('template.form')

        @yield('script')

        @include('template.loading')
        @include('template.flash')

    </body>

    <script stype="text/javascript">

        $('.left.sidebar').first()
            .sidebar('setting', {
                dimPage             : false,
                transition          : 'push',
                mobileTransition    : 'push'})
            .sidebar('attach events', '#sidebar')
        ;

    </script>

</html>
