@extends('template.default')
@section('title', $title)

@section('header')

    <div class="ui grid basic segment margin top bottom none">
        <div class="sixteen wide column computer thirteen wide column tablet thirteen wide mobile column">
            <h2 style="font-size: 20px; line-height: 22px;" class="margin top bottom none">
                {{ message('support', 'welcome') }}
            </h2>

        </div>
    </div>

    <div class="ui menu secondary pointing fluid margin top bottom none border top dashed">
        <div class="item active" data-tab="index" popup data-content="{{ message('common', 'index') }}" >
            <i class="icon-home-1 icon"></i>
        </div>
        <div class="item" data-tab="pending" popup data-content="{{ message('support', 'pending') }}" >
            <i class="icon-traffic-cone icon"></i>
        </div>
        <div class="item" data-tab="in-progress" popup data-content="{{ message('support', 'in-progress') }}" >
            <i class="icon-flag icon"></i>
        </div>
        <div class="item" data-tab="closed" popup data-content="{{ message('support', 'closed') }}" >
            <i class="icon-cancel-circled icon"></i>
        </div>
        <div class="item" data-tab="more-options" popup data-content="{{ message('common', 'more') }}&nbsp;{{ message('common', 'options') }}" >
            <i class="icon-cog-5 icon"></i>
        </div>
    </div>

@stop

@section('content')

    <div class="ui tab active" data-tab="index">

    </div>

    <div class="ui tab" data-tab="pending">

    </div>

    <div class="ui tab" data-tab="in-progress">

    </div>

    <div class="ui tab" data-tab="closed">

    </div>

    <div class="ui tab" data-tab="more-options">

    </div>

@stop

@section('script')

@stop
