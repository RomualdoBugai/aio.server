@if(isArray($data))
    @foreach($data as $k => $v)
        @include("app.schedule.widget.item", ["item" => $v])
    @endforeach
@endif
