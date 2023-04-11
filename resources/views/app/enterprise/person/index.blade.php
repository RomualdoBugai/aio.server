@if($status == true)
	@php
		$total = count($persons) - 1;
	@endphp
	@foreach($persons as $e => $person)
		<a style="padding: 2px;">
			{{ $person['name'] }}
		</a>
		@if($e < $total)
			,
		@endif
	@endforeach
@endif
