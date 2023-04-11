@extends('template.default')
@section('title', $title)

@section('breadcrumb')
    @include("template.breadcrumb", ['breadcrumbs' => $breadcrumbs])
@stop

@section('content')
    <div id="enterprise-edit" class="ui container">
        {!! $form !!}
    </div>
@stop

@section('script')
    <script type="text/javascript">
        $(function(){
            'use strict';
            $("#enterprise-edit > form").onSubmit({
                url         : '{{ route("enterprise.update") }}',
                method      : 'post',
            });
        });
    </script>
@stop
