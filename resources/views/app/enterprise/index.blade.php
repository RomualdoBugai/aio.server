@extends('template.default')
@section('title', $title)

@section('breadcrumb')
    @include("template.breadcrumb", ['breadcrumbs' => $breadcrumbs])
@stop

@section('content')

    <div class="ui secondary pointing menu margin bottom none fluid blue">
        <div class="item active">
            <i class="icon-users icon"></i>&nbsp;
            {{ message('common', 'index') }}
        </div>
        <a class="item" href="{{ route("enterprise.new") }}">
            <i class="icon-doc-new icon"></i>&nbsp;
            {{ message('common', 'new') }}
        </a>
    </div>

    <div class="ui basic segment margin top bottom none">
        <div class="ui form">
            <div class="field">
                <div class="ui input icon">
                    <i class="icon-search-1 icon circular"></i>
                    <input type="search" placeholder="{{ message('common', 'search') }}" />
                </div>
            </div>
        </div>
    </div>

    <div class="ui basic segment margin top none">
        <div class="ui vertical menu fluid">
        @foreach($enabled as $enterprise)
            <a class="item" href="{{ route('enterprise.show', ['id' => $enterprise['id']]) }}" title="{{ message('enterprise', 'enterprise.show') }}">

                <div class="ui grid">

                    <div class="two wide column mobile">
                        <span style="width: 30px; height: 30px; line-height: 30px; text-align: center; background: #222; color: #fff; display: inline-block; border-radius: 50% !important">
                            {{ getCapitalLetters($enterprise['name']) }}
                        </span>
                    </div>

                    <div class="fourteen wide column mobile">

                        <div style="font-size: 18px; font-weight: bold; line-height: 18px; margin-bottom: 5px;">
                            {{ ownName($enterprise['name']) }}
                        </div>
                        <small>
                            <span class="flag-icon flag-icon-{{ $enterprise['code']}}"></span>
                        </small>
                        &nbsp;
                        <small>
                            {{ $enterprise['national_code'] }}
                        </small>
                    </div>

                </div>

            </a>

        @endforeach
        </div>
    </div>

@stop

@section('script')

@stop
