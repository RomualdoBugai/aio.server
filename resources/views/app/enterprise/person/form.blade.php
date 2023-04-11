<form class="ui form">

	<input type="hidden" name="enterprise_person[id]" value="{{ $form['id']['value'] }}" />
	<input type="hidden" name="enterprise_person[enterprise_id]" value="{{ $form['enterprise_id']['value'] }}" />

	<div class="ui grid">
		<div class="row">
			<div class="sixteen wide column">
				<div class="field">
					<label for="enterprise-person-name">{{ $form['name']['label'] }}</label>
					<div class="ui input icon wide">
						<i class="icon-user icon"></i>
						<input type="text" id="enterprise-person-name" name="enterprise_person[name]" maxlength="112" value="{{ $form['name']['value'] }}" required />
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="sixteen wide column">
				<div class="field">
					<label for="enterprise-person-description">{{ $form['description']['label'] }}</label>
					<div class="ui input icon wide">
						<i class="icon-tags icon"></i>
						<input type="text" id="enterprise-person-description" name="enterprise_person[description]" maxlength="224" value="{{ $form['description']['value'] }}" />
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<br />
	</div>

	<input class="ui basic green button" type="submit" name="enterprise_person[submit]" value="{{ $form['submit']['value'] }}" />

</form>
