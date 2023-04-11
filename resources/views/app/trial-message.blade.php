 @if(Session::has('app.trialPeriod'))
    @if(Session::get('app.trialPeriod') == 1)
        <div class="ui message icon red">
            <i class="icon-attention-alt icon"></i>
            <div class="conten">
                <div class="header">
                    @if(Session::get('app.trialPeriodDaysLeft') == 1)
                        Amanha seu periodo de testes irá encerrar.
                    @else
                        Seu periodo de testes acaba em {{ Session::get('app.trialPeriodDaysLeft') }} dias.
                    @endif
                </div>
                <p>
                    Clique <a href="/vault/plan/choose">aqui</a> para escolher seu plano.
                </p>
            </div>
        </div>
    @endif
@endif