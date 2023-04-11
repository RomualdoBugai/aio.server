<form id="enterprise" class="ui form">

	<input type="hidden" name="enterprise[id]" value="{{ $form['id']['value'] }}" />
	<input type="hidden" name="enterprise[is_matrix]" value="{{ $form['is_matrix']['value'] }}" />


	<div class="two fields">
   		<div class="four wide column field">
			<label>{{ $form['country_id']['label'] }}</label>
			<div class="ui input">
				{{ Form::select('enterprise[country_id]', $options['country'], $form['country_id']['value']) }}
			</div>
		</div>
		<div class="twelve wide column field">
			<label>{{ $form['national_code']['label'] }}</label>
			<div class="ui input">
				<input type="text" name="enterprise[national_code]" value="{{ $form['national_code']['value'] }}" />
			</div>
		</div>
	</div>

	<div class="two fields">
   		<div class="eight wide column field">
			<label>{{ $form['name']['label'] }}</label>
			<div class="ui input">
				<input type="text" name="enterprise[name]" value="{{ $form['name']['value'] }}" maxlength="96" required />
			</div>
		</div>
		<div class="eight wide column field">
			<label>{{ $form['fantasy_name']['label'] }}</label>
			<div class="ui input">
				<input type="text" name="enterprise[fantasy_name]" maxlength="96" value="{{ $form['fantasy_name']['value'] }}" />
			</div>
		</div>
	</div>

	<input class="ui basic button blue" type="submit" name="enterprise[submit]" value="{{ $form['submit']['value'] }}" />

</form>
