<div class="ui grid basic segment margin top bottom none">
    <div class="thirteen wide column computer thirteen wide column tablet thirteen wide mobile column">
        <h2 style="font-size: 20px; line-height: 22px;" class="margin top bottom none">
            {{ message('common', 'quotation') }}
        </h2>
    </div>
</div>

<div class="ui menu secondary pointing fluid blue margin top bottom none border top dashed">
    <div class="item active" data-tab="quotation.overview">
        <i class="icon-doc-text  icon"></i>
    </div>
    <div class="item" data-tab="quotation.history">
        <i class="icon-cog-5  icon"></i>
    </div>
</div>

<div class="ui tab active" data-tab="quotation.overview">
	@php ($lastUpdate = null)
	<div class="ui vertical menu fluid no-border">
		@foreach($currencyQuotes as $currencyCode => $currencyQuote)
			<div class="item">
				<strong>{{ $currencyCode }}</strong>
				<div class="ui label green">
					{{ number_format( $currencyQuote['rate'], 4, '.', ' ') }}
				</div>
			</div>
			@php ($lastUpdate = substr($currencyQuote['updated_at'], 11, 5))
		@endforeach
		<div class="item">
			{{ message('common', 'updated_at') }}
			<i class="icon-clock icon"></i><strong>{{ $lastUpdate }}</strong>
		</div>
	</div>
</div>

<div class="ui tab" data-tab="quotation.history">
	@if(isArray($lastUpdates))
		<div class="ui vertical menu fluid no-border">
		@foreach($lastUpdates as $lastUpdate)
			<div class="item">
				<strong>{{ $lastUpdate['currency']['name'] }}</strong>
				 / <small>
					{!! Carbon\Carbon::parse($lastUpdate['updated_at'])->format(dateFormat()) !!}
				</small>
				<span class="ui label orange">
					{{ number_format( $lastUpdate['rate'], 4, '.', ' ') }}
				</span>
			</div>
		@endforeach
		</div>
	@endif
</div>