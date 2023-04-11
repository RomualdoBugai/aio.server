@extends('template.basic')
@section('title', $title)
@section('content')

    <div class="ui container">
        <div class="ui grid">
            <div class="two wide column"></div>
            <div class="twelve wide column">

                <br />

                <div style="width: 80px; height: 80px; background: #60a033; border-radius: 5px !important; color: #fff; text-align: center; font-size: 32px; line-height: 80px; margin: 0 auto;">
                    <i class="icon-users icon"></i>
                </div>

                <br />

                <p style="text-align: center">
    				<span style="font-size: 12px; line-height: 10px !important;">
                        Gerenciamento de Relacionamento com Cliente
    				</span><br />
    				<span style="font-size: 22px; line-height: 28px; margin: 20px 0 0 0; padding: 0; font-weight: normal; text-align: center;">
    					<strong>Lumbex</strong> Lumber Company

    				</span>
    			</p>

                <p>
                    Você precisa se autenticar para entrar no sistema, para isso utilize o formulário abaixo:
                </p>

                <form id="log-in" class="ui form" method="post" action="{{ route('logInSubmit') }}">

                    <div class="ui left icon wide fluid input">
		          		<input style="border-bottom: 0" type="email" name="logIn[email]" id="email" placeholder="e-mail" readonly onfocus="this.removeAttribute('readonly');" />
		          		<i class="icon-user-1 icon"></i>
		        	</div>
                    <div class="ui left icon wide fluid input">
                        <input style="border-top: 0;" type="password" name="logIn[password]" id="password" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;" readonly onfocus="this.removeAttribute('readonly');" />
		          		<i class="icon-lock icon"></i>
		        	</div>

                    <br />

                    <div style="text-align: center">
                        <input class="ui basic button primary" type="submit" name="logIn[submit]" value="{{ message('common', 'log-in-submit') }}" />
                    </div>

                    <input type="hidden" id="token" name="_token" value="{{ csrf_token() }}">

                </form>
            </div>
            <div class="two wide column"></div>
        </div>
    </div>

@stop

@section('script')
    <script type="text/javascript">
        $(function(){
            'use strict';
            $("#log-in").onSubmit({
                success: function(response)
                {
                    if (response.status == true)
                    {
                        window.location = '{{ route('welcome') }}';
                    }
                }
            })

        });
    </script>
@stop
