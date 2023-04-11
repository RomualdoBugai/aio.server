@if($status == true)
	@php
		$total = count($emails) - 1;
	@endphp 
	@foreach($emails as $e => $email)
		<a href="mailto:{{ $email['email'] }}" style="padding: 2px;">
			{{ $email['email'] }}
		</a>
		@if($e < $total)
			,
		@endif
	@endforeach
@endif
