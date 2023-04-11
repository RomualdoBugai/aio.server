<div class="item" data-year="{!! Carbon\Carbon::parse($item['created_at'])->format('Y') !!}">
	
	<small>
		{!! Carbon\Carbon::parse($item['created_at'])->format(dateFormat()) !!}
	</small>
	
	<br />
	
	<strong>
		{!! $item['title'] !!}
	</strong>

	<p>
		{!! $item['description'] !!}
		@if($item['coordinates'] != "0,0")
			@php (list($lat, $long) = explode(",", $item['coordinates']))
			<a data-location data-url="{{ route("googleMaps", ['lat' => $lat, 'long' => $long]) }}">
				<i class="icon-location icon"></i>
			</a>
		@endif
	</p>
		
</div>
