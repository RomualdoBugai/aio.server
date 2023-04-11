@extends('template.default')
@section('title', 'escolher um plano')
@section('content')

    <div class="ui container">

        <h1>Escolher um plano</h1>

        <div class="ui grid">
            @foreach ($plans as $plan)
                @if ($plan['allow_choose'])

                    <div class="four wide column">
                        <form name="addPlan" method="post" action="{{ route('addPlan') }}">
                            <h1>{{ $plan['name'] }}</h1>
                            <p>{{ $plan['user_limit'] }}</p>
                                <p>{{ $plan['enterprise_limit'] }}</p>
                            <p>{{ $plan['upload_limit'] }}</p>
                            <p>{{ $plan['send_file_email'] }}</p>
                            <p>R$ {{ $plan['price'] }}</p>
                            <input type="hidden" name="addPlan[id]" value="{{ $plan['id'] }}" />
                            <input type="submit" name="addPlan[submit]" value="escolher" class="ui button" />
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </form>
                    </div>

                @else

                    <div class="four wide column">
                        <form name="onRequest" method="post" action="{{ route('onRequest') }}">
                            <h1>{{ $plan['name'] }}</h1>
                            <p>{{ $plan['user_limit'] }}</p>
                            <p>{{ $plan['enterprise_limit'] }}</p>
                            <p>{{ $plan['upload_limit'] }}</p>
                            <p>{{ $plan['send_file_email'] }}</p>
                            <p>R$ {{ $plan['price'] }}</p>
                            <input type="hidden" name="onRequest[id]" value="{{ $plan['id'] }}" />
                            <input type="submit" name="onRequest[submit]" value="escolher" class="ui button" />
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </form>
                    </div>

                @endif
            @endforeach
        </div>

    </div>

@stop
