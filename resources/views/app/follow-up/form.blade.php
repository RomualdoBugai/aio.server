<form class="ui form">

	<input type="hidden" name="follow_up[id]" value="{{ $form['id']['value'] }}" />
	<input type="hidden" name="follow_up[controller]" value="{{ $form['controller']['value'] }}" />
	<input type="hidden" name="follow_up[controller_id]" value="{{ $form['controller_id']['value'] }}" />

	<div class="field">
		<div class="ui input wide icon">
			<textarea id="text" placeholder="{{ message("follow-up", "placeholder") }}" name="follow_up[description]" rows="3" data-value value="{{ $form['description']['value'] }}" required></textarea>
		</div>
	</div>

	<input class="ui blue button" type="submit" name="follow_up[submit]" value="{{ $form['submit']['value'] }}" />

</form>
