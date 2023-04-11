<form class="ui form">

	<input type="hidden" name="invite_user[id]" />

	<div class="field">
		<label for="form-controller-email">{{ $form['email']['label'] }}</label>
		<div class="ui input icon wide">
			<i class="icon-mail-2 icon"></i>
			<input data-value type="email" id="form-controller-email" name="invite_user[email]" maxlength="112" required />
		</div>
	</div>

	<div class="field">
		<label for="form-controller-name">{{ $form['name']['label'] }}</label>
		<div class="ui input icon wide">
			<i class="icon-mail-2 icon"></i>
			<input data-value type="text" id="form-controller-name" name="invite_user[name]" maxlength="96" required />
		</div>
	</div>

	<input class="ui basic green button" type="submit" name="invite_user[submit]" value="{{ $form['submit']['value'] }}" />

</form>
