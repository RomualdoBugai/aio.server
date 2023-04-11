@extends('template.default')
@section('title', 'minhas empresas')
@section('content')
<div class="ui container">
    @foreach($enterprises as $enterprise):
        <div class="ui segment">
            {{ $enterprise->id }}<br />
            {{ $enterprise->name }}<br />
            {{ $enterprise->national_code }}<br />
            {{ $enterprise->is_active }}<br />
        </div>
    @endforeach
</div>
@stop
