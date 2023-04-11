@if($status == true)

	<div class="ui feed fluid" data-app="scheduling">
	@foreach($schedulings as $scheduling)
		<div class="event">
			<div class="content" data-year="{!! Carbon\Carbon::parse($scheduling['scheduling']['created_at'])->format('Y') !!}">

				<div class="summary">

					@php
    					$author  = $scheduling['users'][0]['user'];
						$authors = count($scheduling['users']);
						$guests  = $scheduling['users'];
						array_shift($guests);
					@endphp

					<a href="{{ route('social', ['id' => $author['id']]) }}">
						{{ $author['name'] }}
					</a> {{ message("scheduling", "post") }}

					<span style="font-weight: normal">
						{!! Carbon\Carbon::parse($scheduling['scheduling']['created_at'])->format(dateFormat()) !!}
					</span>

					@if($authors > 1)
						{{ message('common', 'with') }}
						@foreach($guests as $count => $guest)
							<a href="{{ route('social', ['id' => $guest['user']['id']]) }}">
								{{ $guest['user']['name'] }}
							</a>
							@if( $count < ($authors - 1) )
								,
							@endif
						@endforeach
					@endif
				</div>

				<div class="extra text">
					<p>
						<strong>
							{!! $scheduling['scheduling']['title'] !!}
						</strong>
					</p>
					{!! $scheduling['scheduling']['description'] !!}

					@if($scheduling['scheduling']['coordinates'] != "0,0")
						@php

							list($lat, $long) = explode(",", $scheduling['scheduling']['coordinates']);

						@endphp
						<a data-location data-url="{{ route("googleMaps", ['lat' => $lat, 'long' => $long]) }}">
							<i class="icon-location icon"></i>
						</a>
					@endif


				</div>

			</div>
		</div>
	@endforeach
	</div>

	<div id="scheduling-location" class="ui modal">
		<i class="close icon"></i>
		<div class="header">

		</div>
		<iframe border="0" style="border: 0; width: 100%; height: 320px;" allowfullscreen></iframe>
	</div>

@endif


<script>

	$("[data-location]").click(function(event){
		var url = $(this).data('url');
		$.get(url, function(response){
			console.log(response);

			var $modal = $("#scheduling-location");
			$modal.find('.header').html(response.name);
			$modal.find('iframe').prop('src', response.url)
			$modal
			.modal('setting', 'closable', true)
			.modal('setting', 'duration', '250')
			.modal('setting', 'transition', 'slide down')
  			.modal('show')

		});
		event.preventDefault();
	});


</script>
