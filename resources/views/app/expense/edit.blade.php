@extends('template.default')
@section('title', $title)

@section('breadcrumb')
    @include("template.breadcrumb", ['breadcrumbs' => $breadcrumbs])
@stop

@section('content')

    <div class="ui basic segment">
        <div id="expense-edit">
            {!! $form !!}
        </div>
    </div>
    
@stop

@section('script')
    <script type="text/javascript">
        $(function(){
            'use strict';
            $("#expense-edit > form").onSubmit({
                url         : '{{ route('expense.update') }}',
                method      : 'post',
            });
        });
    </script>
@stop
