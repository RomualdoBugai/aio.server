@extends('template.default')
@section('title', 'Meu carrinho de compras')
@section('content')

    <script type="text/javascript" src="{{{ URL::asset('plugins/jquery-moip/moip-2.7.1.min.js') }}}"></script>
    <script type="text/javascript" src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>

    <script type="text/javascript">
    PagSeguroDirectPayment.setSessionId('{{ $session['id'] }}');
    </script>

    <div class="ui container">

        <h1>
            Confirmar Pagamento
        </h1>

        <table class="ui table">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Valor</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>

                @foreach($cart->item as $row)
                    <tr data-id="{{ $row->rowId }}">
                        <td>
                            <strong>{{ $row->name }}</strong>
                        </td>
                        <td><input type="text" updade-shopping-cart-quantity maxlength="2" value="{{ $row->qty }}" /></td>
                        <td>R$ {{ $row->price }}</td>
                        <td shopping-cart-item-total>R$ {{ $row->total }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2">&nbsp;</td>
                    <td>Subtotal</td>
                    <td>{{ $cart->subtotal }}</td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                    <td>Total</td>
                    <td shopping-cart-total>{{ $cart->total }}</td>
                </tr>
            </tbody>
        </table>

        <h1>
            Pagamento com cartão de crédito
        </h1>

        <form id="efetuar-pagamento" class="ui form" autocomplete="on" method="POST" action="{{ route("checkout") }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="checkOut[creditCardToken]" value="" />
            <input type="hidden" name="checkOut[creditCardBrand]" value="" />
            <input type="hidden" name="checkOut[senderHash]" value="" />


            <div class="field">
                <label>Número do cartão</label>
                <div class="ui input">
                    <input type="text" id="numero_cartao" name="checkOut[creditCardNumber]" autocomplete="cc-number" value="4532117128255891" required />
                    <p id="numero-cartao-errado"></p>
                    <img id="brand" src="" />
                </div>
            </div>

            <div class="field">
                <label>Nome do cartão</label>
                <div class="ui input">
                    <input type="text" id="nome_cartao" name="checkOut[creditCardName]" value="{{ Session::get('user.name') }}" required />

                </div>
            </div>

            <div class="field">
                <label>Verifica</label>
                <div class="ui input">
                    <input type="text" maxlength="4" id="cvc" name="checkOut[creditCardVerify]" autocomplete="off" required />
                    <p id="cvc-errado"></p>
                </div>
            </div>

            <div class="field">
                <label>Data de validade</label>
                <div class="ui input">
                    <input type="text" id="data_validade" name="checkOut[creditCardVality]" required />
                    <p id="data-validade-cartao-errado"></p>
                </div>
            </div>

            <div class="field">
                <label>Valor total</label>
                <div class="ui input">
                    <input type="text" name="checkOut[amount]" value="{{ $cart->subtotal }}" readonly />
                </div>
            </div>

            <div class="field">
                <label>Parcelar em</label>
                <div class="ui input">
                    <input type="text" name="checkOut[portionNumber]" value="1" />
                    <input type="text" name="checkOut[portionValue]" value="{{ $cart->subtotal }}" />
                </div>
            </div>

            <?php foreach($cart->item  as $key => $row) :?>
                <input type="text" name="checkOut[item][{{ $key }}][id]" value="{{ $row->id }}" />
                <input type="text" name="checkOut[item][{{ $key }}][quantity]" value="{{ $row->qty }}" />
                <input type="text" name="checkOut[item][{{ $key }}][description]" value="{{ $row->name }}" />
                <input type="text" name="checkOut[item][{{ $key }}][amount]" value="{{ $row->price }}" />
            <?php endforeach;?>


            @if($user->addresses->status)
                @foreach ($user->addresses->data as $address)
                    <div class="ui segment">
                        <p>
                            <input type="radio" name="checkOut[address]" value="{{ $address->id }}" />
                            {{ $address->street }} - {{ $address->number }}, {{ $address->district }}<br />
                            {{ $address->city }} - {{ $address->state }}
                        </p>
                    </div>
                @endforeach
            @else
                <p>
                    Nenhum endereço foi encontrado.
                </p>
            @endif

            <a id="validar" href="javascript:void(0);" class="ui button green">confirmar compra</a>
            <input type="submit" class="ui button green" name="confirmar compra" style="display: none" />



        </form>


    </div>

@stop

@section('script')

<script>
$(function(){
    'use strict';

    var $hash = $('input[name="checkOut[senderHash]"]');

    var $creditCardDate = $('input[name="checkOut[creditCardVality]"]');
    $creditCardDate.inputmask("99/99");
    $creditCardDate.on('blur', function(event){
        var num = $(this).val();
        num = num.split("/");
        var month = num[0];
        var year = num[1];
        var $message = $("#data-validade-cartao-errado").html("data de válidade inválida");
        $message.hide();
        if (num.length > 1)
        {
            if (Moip.Validator.isExpiryDateValid(month, year) == false)
            {
                $message.show();
                $(this).val('');
            }
        }
    });

    var $creditCardBrand = $('input[name="checkOut[creditCardBrand]"]');
    var $creditCardNumber = $('#numero_cartao');
    $creditCardNumber.inputmask('9999 9999 9999 9999');
    $creditCardNumber.on('blur', function(event){
        var num = $(this).val();
        num = num.replace(' ', '');
        if( Moip.Validator.isValid(num) )
        {
            var brand = Moip.Validator.cardType(num);
            $creditCardBrand.val(brand.brand.toLowerCase());
            $("#brand").attr("src", 'https://stc.pagseguro.uol.com.br/public/img/payment-methods-flags/42x20/' + brand.brand.toLowerCase() + '.png');
            PagSeguroDirectPayment.getBrand(
                {
                    cardBin: num,
                    success: function(response)
                    {
                        response.brand
                    },
                }
            );
            $("#numero-cartao-errado").hide();
        } else {
            $("#numero-cartao-errado").show().html("número do cartão inválido");
            $(this).val("");
        }
    });

    var $creditCardVerify = $("#cvc");
    $creditCardVerify.on('blur', function(event){
        var $message = $("#cvc-errado").html("código inválido");
        $message.hide();
        var creditCardNum = $creditCardNumber.val();
        creditCardNum = creditCardNum.replace(' ', '');
        var num  = $(this).val();
        if (num.length > 0)
        {
            if ( Moip.Validator.isSecurityCodeValid(creditCardNum, num) == false)
            {
                $message.show();
                $(this).val("");
            }
        }
    });

    var $cardToken = $('input[name="checkOut[creditCardToken]"]');

    var $form = $("#efetuar-pagamento");
    $("#validar").click(function(event){

        var creditCardVality = $creditCardDate.val();
        creditCardVality = creditCardVality.split("/");
        var month = creditCardVality[0];
        var year = "20" + creditCardVality[1];

        var param = {
            cardNumber: $creditCardNumber.val().replace(/[^\d\.]/g, ''),
            cvv: $creditCardVerify.val(),
            expirationMonth: month,
            expirationYear: year,
            brand: $creditCardBrand.val(),
            success: function(response)
            {
                $cardToken.val(response.card.token);
            },
            error: function(response)
            {
                console.log(response);
                $cardToken.val("");
            }
        };
        PagSeguroDirectPayment.createCardToken(param);
        $hash.val(PagSeguroDirectPayment.getSenderHash());
        var explode = function(){
            $form.submit();
        };
        setTimeout(explode, 5000);
    });

});
</script>

@stop
