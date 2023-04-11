@if($status == true)
	@php
		$total = count($phones) - 1;
	@endphp 
	@foreach($phones as $e => $phone)
		<a href="tel:{{ $phone['default'] }}" style="padding: 2px;">
			{{ $phone['default'] }}
		</a>
		@if($e < $total)
			,
		@endif
	@endforeach
@endif
