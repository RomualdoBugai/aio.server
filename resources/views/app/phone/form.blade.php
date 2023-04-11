<form class="ui form">

	<input type="hidden" name="phone[id]" value="{{ $form['id']['value'] }}" />
	<input type="hidden" name="phone[controller]" value="{{ $form['controller']['value'] }}" />
	<input type="hidden" name="phone[controller_id]" value="{{ $form['controller_id']['value'] }}" />

	<div class="ui grid">

		<div class="row">
			<div class="sixteen wide column">
				<div class="field">
					<label>{{ $form['international_code']['label'] }}</label>
					<div class="ui input icon">
						<i class="icon-globe icon"></i>
						{{ Form::select('phone[international_code]', $options['international_code'], null) }}
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="four wide column computer four wide column tablet four wide column mobile" style="padding-right: 0">
				<div class="field">
					<label>{{ $form['long_distance']['label'] }}</label>
					<div class="ui input">
						<input data-value type="text" name="phone[long_distance]" maxlength="4" value="{{ $form['long_distance']['value'] }}" required />
					</div>
				</div>
			</div>
			<div class="twelve wide column computer twelve wide column tablet twelve wide column mobile">
				<div class="field">
					<label>{{ $form['number']['label'] }}</label>
					<div class="ui input icon">
						<i class="icon-phone icon"></i>
						<input data-value type="text" name="phone[number]" maxlength="9" value="{{ $form['number']['value'] }}" required />
					</div>
				</div>
			</div>
		</div>
		
	</div>

	<div class="row">
		<br />
	</div>

	<input class="ui basic green button" type="submit" name="phone[submit]" value="{{ $form['submit']['value'] }}" />

</form>
