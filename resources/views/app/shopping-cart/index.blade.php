@extends('template.default')
@section('title', 'escolher um plano')
@section('content')

    <div data-app="shopping-cart">


        <a class="ui animated green button" href="" tabindex="0">
            <div class="visible content">Próximo</div>
            <div class="hidden content">
                <i class="icon-right icon"></i>
            </div>
        </a>

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

    </div>
@stop

@section('script')
    <script type="text/javascript">

        (function(){
            'use strict';
            var $app    = $('[data-app="shopping-cart"]');
            var $item   = $app.find("[updade-shopping-cart-quantity]");
            var $total  = $app.find("[shopping-cart-total]");

            $item.on('blur', function(event){

                var $this       = $(this);
                var id          = $this.closest("tr").data('id');
                var quantity    = $this.val();

                var form = {
                    "updateItem": {
                        "id"       : id,
                        "quantity" : quantity
                    }
                };

                $item.startEvent({
                    data    : form,
                    url     : "{{ route("updateItem") }}",
                    method  : "post",
                    success : function(response)
                    {
                        $this
                        .closest("tr")
                        .find("[shopping-cart-item-total]")
                        .html("R$ " + response.itemTotal);

                        $total.html("R$ " + response.cartTotal)
                    }
                });

            });

        })(jQuery);
    </script>

@stop
