<form class="ui form" enctype="multipart/form-data" action="{{ route("attachment.insert") }}" method="post">

	<input type="hidden" name="attachment[id]" value="{{ $form['id']['value'] }}" />
	<input type="hidden" name="attachment[controller]" value="{{ $form['controller']['value'] }}" />
	<input type="hidden" name="attachment[controller_id]" value="{{ $form['controller_id']['value'] }}" />

	<div class="field">
		<div class="ui input wide">
			<textarea id="text" placeholder="{{ message("follow-up", "placeholder") }}" name="attachment[description]" rows="2" data-value value="{{ $form['description']['value'] }}" required></textarea>
		</div>
	</div>

	<div class="field">
		<div class="ui input wide icon">
			<i class="icon-doc icon"></i>
			<input type="file" name="attachment[files][]" multiple />
		</div>
	</div>

	<input type="hidden" name="_token" value="{{ csrf_token() }}">

	<input class="ui blue button" type="submit" name="attachment[submit]" value="{{ $form['submit']['value'] }}" />

</form>
