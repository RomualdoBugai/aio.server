@extends('template.default')
@section('title', $title)

@section('breadcrumb')
    @include("template.breadcrumb", ['breadcrumbs' => $breadcrumbs])
@stop

@section('header')

    <div class="ui grid basic segment margin top bottom none">
        <div class="sixteen wide column computer sixteen wide column tablet sixteen wide mobile column">
            <h2 style="font-size: 20px; line-height: 22px;" class="margin top bottom none">
                {{ ownName($today['name']) }}
            </h2>
        </div>
    </div>

    <div class="ui secondary pointing menu blue border top dashed margin top bottom none">

        <div class="item active" data-tab="schedule-week">
            <i class="icon-clock icon"></i>
            {{ message('common', 'week') }}
        </div>

        <div class="item" data-tab="schedule-current">
            <i class="icon-calendar icon"></i>
            {{ message('common', 'current') }}
        </div>

    </div>

@stop

@section('content')

    <div class="ui tab" data-tab="schedule-current" data-app="calendar">
        <div class="ui basic segment">
            {!! $calendars['current'] !!}
        </div>
        <div data-app="user-schedule" class="ui vertical fluid menu inverted violet" data-container>
        </div>
    </div>

    <div class="ui tab active" data-tab="schedule-week" data-app="calendar">

        <div class="ui basic segment" style="margin-top: 10px;">
            <div class="ui seven columns grid">
                @foreach($calendars['week'] as $w => $week)
                    @php $active = null @endphp
                    @if($today['month'] == $week['month'] && $week['day'] == $today['day'])
                        @php $active = 'inverted blue' @endphp
                    @endif
                    <div class="column wide text center {{ $active }}"  data-date="{{ $week['year'] . '-' . $week['month'] . '-' . $week['day']  }}">
                        <span style="font-size: 9px;">
                            {{ strtoupper($week['week']) }}
                        </span>
                        <span class="margin top bottom none text center" style="font-size: 16px;">
                            <strong>
                                {{ $week['day'] }}
                            </strong>
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="ui basic segment">
            {{ message('common', 'scheduling') }}
        </div>

        <div class="ui vertical fluid menu inverted violet" data-container>
            {!! $view['schedule'] !!}
        </div>

        <!--
        <div class="ui basic segment">
            {{ message('expense', 'index') }}
        </div>

        <div class="ui feed">
            {!! $view['expense'] !!}
        </div>
        -->

    </div>

@stop

@section('script')
    @include('app.schedule.widget.script')
@stop
