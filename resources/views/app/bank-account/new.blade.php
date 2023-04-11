@extends('template.default')
@section('title', $title)

@section('breadcrumb')
    @include("template.breadcrumb", ['breadcrumbs' => $breadcrumbs])
@stop

@section('content')

    <div class="ui secondary pointing menu margin bottom none fluid blue">
        <a class="item" href="{{ route("bankAccount.index") }}">
            <i class="icon-users icon"></i>&nbsp;
            {{ message('common', 'index') }}
        </a>
        <a class="item active">
            <i class="icon-doc-new icon"></i>&nbsp;
            {{ message('common', 'new') }}
        </a>
    </div>

    <div id="bank-account-new" class="ui basic segment margin top none">
        {!! $form !!}
    </div>

@stop

@section('script')
    <script type="text/javascript">
        $(function(){
            'use strict';
            $("#bank-account-new > form").onSubmit({
                url         : '{{ $url }}',
                method      : 'post',
                success: function(response)
                {
                    if (response.status == true)
                    {
                        window.location = response.url;
                    }
                }
            });
        });
    </script>
@stop
