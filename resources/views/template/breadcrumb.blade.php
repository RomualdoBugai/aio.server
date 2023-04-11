@if(isset($breadcrumbs) AND isArray($breadcrumbs) == true)
	<div class="ui breadcrumb">
	@php ($last = count($breadcrumbs))
	@foreach($breadcrumbs as $number => $breadcrumb)
		
		@if($breadcrumb['link'])
			<a class="section" href="{{ $breadcrumb['url'] }}">
				{{ $breadcrumb['label'] }}	
			</a>
		@else
			<div class="section">
				{{ $breadcrumb['label'] }}	
			</div>
		@endif

		@if( ($number+1) < $last)
			<i class="right angle icon divider"></i>
		@endif

	@endforeach
	</div>
@endif