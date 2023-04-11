<form class="ui form">

	<input type="hidden" name="address[id]" value="{{ $form['id']['value'] }}" />
	<input type="hidden" name="address[is_active]" value="{{ $form['is_active']['value'] }}"/>
	<input type="hidden" name="address[default]" value="{{ $form['default']['value'] }}" />
	<input type="hidden" name="address[controller]" value="{{ $form['controller']['value'] }}" />
	<input type="hidden" name="address[controller_id]" value="{{ $form['controller_id']['value'] }}" />

	<div class="ui grid">
		<div class="row">
			<div class="eight wide column computer eight wide column tablet eight wide column mobile" style="padding-right: 0">
				<div class="field">
					<label>{{ $form['country_id']['label'] }}</label>
					<div class="ui input icon">
						<i class="icon-globe icon"></i>
						{{ Form::select('address[country_id]', $options['country'], $form['country_id']['value']) }}
					</div>
				</div>
			</div>
			<div class="eight wide column computer eight wide column tablet eight wide column mobile">
				<div class="field">
					<label>{{ $form['postal_code']['label'] }}</label>
					<div class="ui input">
						<input data-value type="text" name="address[postal_code]" value="{{ $form['postal_code']['value'] }}" required />
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="ui grid">
		<div class="row">
			<div class="twelve wide column computer twelve wide column tablet twelve wide column mobile" style="padding-right: 0">
				<div class="field">
					<label>{{ $form['street']['label'] }}</label>
					<div class="ui input">
						<input data-value type="text" name="address[street]" value="{{ $form['street']['value'] }}" required />
					</div>
				</div>
			</div>
			<div class="four wide column computer four wide column tablet four wide column mobile">
				<div class="field">
					<label>{{ $form['number']['label'] }}</label>
					<div class="ui input">
						<input data-value type="text" name="address[number]" value="{{ $form['number']['value'] }}" required />
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="ui grid">
		<div class="row">
			<div class="eight wide column computer eight wide column tablet eight wide column mobile" style="padding-right: 0">
				<div class="field">
					<label>{{ $form['district']['label'] }}</label>
					<div class="ui input">
						<input data-value type="text" name="address[district]" value="{{ $form['district']['value'] }}" required />
					</div>
				</div>
			</div>
			<div class="eight wide column computer eight wide column tablet eight wide column mobile">
				<div class="field">
					<label>{{ $form['complement']['label'] }}</label>
					<div class="ui input">
						<input data-value type="text" name="address[complement]" value="{{ $form['complement']['value'] }}" />
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="ui grid">
		<div class="row">
			<div class="twelve wide column computer twelve wide column tablet twelve wide column mobile" style="padding-right: 0">
				<div class="field">
					<label>{{ $form['city']['label'] }}</label>
					<div class="ui input">
						<input data-value type="text" name="address[city]" value="{{ $form['city']['value'] }}" required />
					</div>
				</div>
			</div>
			<div class="four wide column computer four wide column tablet four wide column mobile">
				<div class="field">
					<label>{{ $form['state']['label'] }}</label>
					<div class="ui input">
						<input data-value type="text" name="address[state]" value="{{ $form['state']['value'] }}" required />
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<br />
	</div>

	<input class="ui basic button green" type="submit" name="address[submit]" value="{{ $form['submit']['value'] }}" />

</form>
