<form class="ui form">

	<input type="hidden" name="email[id]" value="{{ $form['id']['value'] }}" />
	<input type="hidden" name="email[controller]" value="{{ $form['controller']['value'] }}" />
	<input type="hidden" name="email[controller_id]" value="{{ $form['controller_id']['value'] }}" />

	<div class="field">
		<label for="form-controller-email">{{ $form['email']['label'] }}</label>
		<div class="ui input icon wide">
			<i class="icon-mail-2 icon"></i>
			<input data-value type="email" id="form-controller-email" name="email[email]" maxlength="112" value="{{ $form['email']['value'] }}" required />
		</div>
	</div>

	<input class="ui basic green button" type="submit" name="email[submit]" value="{{ $form['submit']['value'] }}" />

</form>
