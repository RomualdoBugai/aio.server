<br />
<form id="bank-account" class="ui form">

	<input type="hidden" name="expense[id]" value="{{ $form['id']['value'] }}" />
	<input type="hidden" name="expense[id]" value="{{ $form['id']['value'] }}" />

	<div class="ui grid">

		<div class="row">
			<div class="sixteen wide column">
				<div class="field">
					<label>{{ $form['name']['label'] }}</label>
					<div class="ui input icon">
						<input type="text" maxlength="56" name="expense[name]" value="{{ $form['name']['value'] }}" />
						<i class="icon-doc icon"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="sixteen wide column">
				<div class="field">
					<label>{{ $form['description']['label'] }}</label>
					<div class="ui input icon">
						<textarea rows="3" name="expense[description]" required>{{ $form['description']['value'] }}</textarea>
						<i class="icon-doc icon"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="eight wide column">
				<div class="field">
					<label>{{ $form['bank_account_id']['label'] }}</label>
					<div class="ui input">
						{{ Form::select('expense[bank_account_id]', $options['bankAccount'], $form['bank_account_id']['value']) }}
					</div>
				</div>
			</div>
			<div class="eight wide column">
				<div class="field">
					<label>{{ $form['due_date_at']['label'] }}</label>
					<div class="ui input icon">
						<input type="text" datepicker name="expense[due_date_at]" value="{{ $form['due_date_at']['value'] }}" />
						<i class="icon-calendar icon"></i>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="ten wide column">
				<div class="field">
					<label>{{ $form['amount']['label'] }}</label>
					<div class="ui input">
						<input price-format="value" type="text" maxlength="15" name="expense[amount]" value="{{ $form['amount']['value'] }}" />
					</div>
				</div>
			</div>
			<div class="six wide column">
				<div class="field">
					<label>{{ $form['currency_id']['label'] }}</label>
					<div class="ui input" price-format="selector">
						{{ Form::select('expense[currency_id]', $options['currency'], $form['currency_id']['value']) }}
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<br />
	</div>

	<input class="ui basic button blue" type="submit" name="expense[submit]" value="{{ $form['submit']['value'] }}" />

</form>

<script type="text/javascript">
	



</script>