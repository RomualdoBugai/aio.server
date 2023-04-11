@if($status == true)
	<div class="ui feed fluid" data-app="attachment">
	@foreach($attachments as $attachment)
		<div class="event" data-user-id="{{ $attachment['user']['id'] }}">
			<div class="content" data-year="{!! Carbon\Carbon::parse($attachment['follow_up']['created_at'])->format('Y') !!}">
				<div class="summary">
					<a href="{{ route('social', ['id' => $attachment['user']['id']]) }}">
						{{ $attachment['user']['name'] }}
					</a> {{ message("attachment", "post") }}
					<div class="date">
						{!! Carbon\Carbon::parse($attachment['follow_up']['created_at'])->format(dateFormat()) !!}
					</div>
				</div>
				<div class="extra text">
					<p>
						{!! $attachment['follow_up']['description'] !!}
					</p>
					<div class="ui menu vertical fluid">
						@foreach($attachment['attachment'] as $file)
						<a class="item" href="{{ $file['path'] . "/" . $file['filename'] }}">
							<i class="icon-doc left icon"></i> {{ $file['name'] }}
							<span class="ui label">
								{!! formatBytes($file['size'], 1) !!}
							</span>
						</a>
					@endforeach
					</div>
				</div>
			</div>
		</div>
	@endforeach
	</div>
@endif
