@if($status == true)
	<div class="ui feed fluid" data-app="follow-up">
	@foreach($followUps as $followUp)
		<div class="event" data-user-id="{{ $followUp['user']['id'] }}">
			<div class="content" data-year="{!! Carbon\Carbon::parse($followUp['created_at'])->format('Y') !!}">
				<div class="summary">
					<a href="{{ route('social', ['id' => $followUp['user']['id']]) }}">
						{{ $followUp['user']['name'] }}
					</a> {{ message("follow-up", "post") }}
					<div class="date">
						{!! Carbon\Carbon::parse($followUp['created_at'])->format(dateFormat()) !!}
					</div>
				</div>
				<div class="extra text">
					{!! $followUp['description'] !!}
				</div>
			</div>
		</div>
	@endforeach
	</div>
@endif
