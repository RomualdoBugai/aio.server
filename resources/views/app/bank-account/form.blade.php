<br />

<form id="bank-account" class="ui form">

	<input type="hidden" name="bank_account[id]" value="{{ $form['id']['value'] }}" />

	<div class="ui grid">

		<div class="row">
			<div class="sixteen wide column">
				<div class="field">
					<label>{{ $form['name']['label'] }}</label>
					<div class="ui input icon">
						<input type="text" maxlength="56" name="bank_account[name]" value="{{ $form['name']['value'] }}" />
						<i class="icon-doc icon"></i>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="eight wide column">
				<div class="field">
					<label>{{ $form['bank_id']['label'] }}</label>
					<div class="ui input">
						{{ Form::select('bank_account[bank_id]', $options['bank'], $form['bank_id']['value']) }}
					</div>
				</div>
			</div>
			<div class="eight wide column">
				<div class="field">
					<label>{{ $form['opening_at']['label'] }}</label>
					<div class="ui input icon">
						<input type="text" datepicker name="bank_account[opening_at]" value="{{ $form['opening_at']['value'] }}" />
						<i class="icon-calendar icon"></i>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="twelve wide column">
				<div class="field">
					<label>{{ $form['agency_number']['label'] }}</label>
					<div class="ui input">
						<input type="text" maxlength="5" name="bank_account[agency_number]" value="{{ $form['agency_number']['value'] }}" />
					</div>
				</div>
			</div>
			<div class="four wide column">
				<div class="field">
					<label>{{ $form['agency_number_digit']['label'] }}</label>
					<div class="ui input">
						<input type="text" maxlength="2" name="bank_account[agency_number_digit]" value="{{ $form['agency_number_digit']['value'] }}" />
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="twelve wide column">
				<div class="field">
					<label>{{ $form['account_number']['label'] }}</label>
					<div class="ui input">
						<input type="text" maxlength="8" name="bank_account[account_number]" value="{{ $form['account_number']['value'] }}" />
					</div>
				</div>
			</div>
			<div class="four wide column">
				<div class="field">
					<label>{{ $form['account_number_digit']['label'] }}</label>
					<div class="ui input">
						<input type="text" maxlength="2" name="bank_account[account_number_digit]" value="{{ $form['account_number_digit']['value'] }}" />
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="sixteen wide column">
				<div class="field">
					<label>{{ $form['opening_balance']['label'] }}</label>
					<div class="ui input icon">
						<input type="text"  maxlength="8" name="bank_account[opening_balance]" value="{{ $form['opening_balance']['value'] }}" />
						<i class="icon-dollar icon"></i>
					</div>
				</div>
			</div>
		</div>

	</div>

	<div class="row">
		<br />
	</div>

	<input class="ui basic button blue" type="submit" name="bank_account[submit]" value="{{ $form['submit']['value'] }}" />

</form>
