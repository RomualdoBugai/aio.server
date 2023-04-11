@if($status == true)
	@php
		$total = count($pendingRequests) - 1;
	@endphp
	@foreach($pendingRequests as $e => $pendingRequest)
		<div class="ui grid">
			<div class="three wide column mobile">
				<span style="width: 52px; height: 52px; line-height: 52px; text-transform: uppercase; text-align: center; background: #222; font-size: 22px; color: #fff; display: inline-block; border-radius: 50% !important">
				    {{ getCapitalLetters($pendingRequest['name']) }}
				</span>
			</div>
			<div class="thirten wide column mobile">
				<h4 class="margin top bottom none">
					{{ ownName($pendingRequest['name']) }}
				</h4>
				<a style="margin-top: 5px;" class="ui orange label" href="mailto:{{ $pendingRequest['email'] }}">
					{{ $pendingRequest['email'] }}
				</a>
			</div>
		</div>
	@endforeach
@endif
