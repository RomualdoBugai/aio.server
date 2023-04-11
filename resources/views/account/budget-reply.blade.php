@extends('template.mail', $template)

@section('content')

    <strong>
        {{ ownName(message('common', 'hello')) }} {!! ownName($user) !!}!
    </strong>
    <br>

    {!! message('common', 'mail.budget-reply.text-one', ['name' => ownName($name) , 'id' => $id, 'created_at' => Carbon\Carbon::parse(($created_at))->format(nationalDate())]) !!}

    <br>
    <br>

    <strong>
        {!! message('common', 'mail.budget-reply.text-two', ['records' => $records]) !!}
    </strong>
@stop
	