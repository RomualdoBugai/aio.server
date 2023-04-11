<div class="ui vertical menu fluid margin top none inverted orange">
@foreach($expenses as $expense)
    <a class="item" href="{{ route('expense.show', ['id' => $expense['id']]) }}" title="{{ message('expense', 'expense.show') }}">
        <strong>{{ $expense['name'] }}</strong><br />
        {{ $expense['bank_account']['name'] }}<br />
        {{ $expense['user']['name'] }}
        <br />
        <strong>{!! Carbon\Carbon::parse($expense['due_date_at'])->format(dateFormat()) !!}</strong>
    </a>
@endforeach
</div>
