@extends('template.default')
@section('title', $title)

@section('breadcrumb')
    @include("template.breadcrumb", ['breadcrumbs' => $breadcrumbs])
@stop

@section('content')

    <div class="ui basic segment">
        <div id="bank-account-edit">
            {!! $form !!}
        </div>
    </div>
    
@stop

@section('script')
    <script type="text/javascript">
        $(function(){
            'use strict';
            $("#bank-account-edit > form").onSubmit({
                url         : '{{ $url }}',
                method      : 'post',
            });
        });
    </script>
@stop
