@extends('template.default')
@section('title', $title)

@section('title')
    {{ $title }}
@stop

@section('content')

    <div class="ui basic segment">

        <h2>
            {{ message('user', 'new-account') }}
            <br />
            <small>
                {{ message('user', 'fill-form')}}
            </small>
        </h2>

    	<div id="user-new">
        	{!! $form !!}
        </div>

    </div>

@stop

@section('script')
    <script type="text/javascript">
        $(function(){
            'use strict';
            $("#user-new > form").onSubmit({
                url         : '{{ route('user.insert') }}',
                method      : 'post',
                success: function(response)
                {
                    if (response.status == true)
                    {
                        window.location = response.url;
                    }
                }
            });
        });
    </script>
@stop
