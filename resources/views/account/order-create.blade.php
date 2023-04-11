@extends('template.mail', $template)

@section('content')

    <strong>
        {{ ownName(message('common', 'hello')) }} {!! ownName($user->name) !!}!
    </strong>
    <br>

    {!! message('common', 'mail.order-created.text-one', ['app' => $app]) !!}

    <br>
    <br>

    {!! message('common', 'mail.order-created.text-two', ['created_at' => Carbon\Carbon::parse(($order->created_at))->format(nationalDate())]) !!}

    <br>
    <br>

    <strong>
        {!! message('common', 'mail.order-created.title-payment', ['name'  => $paymentMethod->name]); !!}
    </strong>

    <br>
    <br>
    
    <strong>
        {!! message('common', 'mail.order-created.title-order', ['id'  => $order->id]); !!}
    </strong>

    <br>

    @foreach($orderItem as $item)

    	{!! message('common', 'mail.order-created.text-item', ['description' => ($item->description), 'amount' => ($item->amount), 'quantity' => ($item->quantity)]) !!}

    	<br>

    @endforeach

    {!! message('common', 'mail.order-created.text-total', ['amount_total' => ($order->amount_total)]) !!}

    <br>
    <br>

    {!! message('common', 'mail.order-created.text-tre', ['app' => $app]) !!}


@stop
	