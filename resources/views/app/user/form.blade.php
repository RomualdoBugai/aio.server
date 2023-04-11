<form id="user" class="ui form">

	<input type="hidden" name="user[id]" value="{{ $form['id']['value'] }}" />
	<input type="hidden" name="user[user_id]" value="{{ $form['user_id']['value'] }}" />
	<input type="hidden" name="user[invite_token]" value="{{ $form['invite_token']['value'] }}" />

	<div class="field">
		<label>{{ $form['name']['label'] }}</label>
		<div class="ui input large">
			<input type="text" name="user[name]" value="{{ $form['name']['value'] }}" maxlength="96" required />
		</div>
	</div>

	<div class="field">
		<label>{{ $form['email']['label'] }}</label>
		<div class="ui input">
			<input type="email" name="user[email]" maxlength="96" value="{{ $form['email']['value'] }}" {{ $form['user_id']['value'] > 0 ? 'readonly' : null }} />
		</div>
	</div>

	<div class="field">
		<label>{{ $form['password']['label'] }}</label>
		<div class="ui input">
			<input type="password" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;" name="user[password]" maxlength="12" required />
		</div>
	</div>

	<div class="field">
		<label>{{ $form['confirm_password']['label'] }}</label>
		<div class="ui input">
			<input type="password" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;" name="user[confirm_password]" maxlength="12" required />
		</div>
	</div>

	<input class="ui basic button blue" type="submit" name="user[submit]" value="{{ $form['submit']['value'] }}" />

</form>
