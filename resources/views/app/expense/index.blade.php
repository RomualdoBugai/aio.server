@extends('template.default')
@section('title', $title)

@section('breadcrumb')
    @include("template.breadcrumb", ['breadcrumbs' => $breadcrumbs])
@stop

@section('content')

    <div class="ui secondary pointing menu margin bottom none fluid blue">
        <div class="item active">
            <i class="icon-dollar icon"></i>&nbsp;
            {{ message('common', 'index') }}
        </div>
        <a class="item" href="{{ route("expense.new") }}">
            <i class="icon-doc-new icon"></i>&nbsp;
            {{ message('expense', 'new') }}
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

    <div class="ui vertical menu fluid margin top bottom none no-border">
    @foreach($expenses as $expense)
        <a class="item" href="{{ route('expense.show', ['id' => $expense['id']]) }}" title="{{ message('expense', 'expense.show') }}">
            <strong>{{ $expense['name'] }}</strong><br />
            {{ $expense['bank_account']['name'] }}<br />
            {{ $expense['user']['name'] }}<br />
            {{ $expense['amount']}} {{ $expense['currency']['code'] }}
            <strong>{!! Carbon\Carbon::parse($expense['due_date_at'])->format(dateFormat()) !!}</strong>
        </a>
    @endforeach
    </div>
    

@stop

@section('script')

@stop

