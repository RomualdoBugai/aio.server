@extends('template.default')

@section('title', $title)

@section('breadcrumb')
    @include("template.breadcrumb", ['breadcrumbs' => $breadcrumbs])
@stop

@section('header')

    <div class="ui grid basic segment">

        <div class="four wide column computer eight wide column tablet sixteen wide column mobile">
            <div class="ui segment inverted green text center">
                <h1 class="margin top bottom none">{{ $indicators->enterprise }}</h1>
                <span>{{ message('enterprise', 'indicator') }}</span>
            </div>
        </div>

    </div>

@stop

@section('content')

@stop
