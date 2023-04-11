<form id="user-settings" class="ui form">

    <div class="field">
        <label>{{ message("common", "date-format") }}</label>
        <div class="ui input">
            <div class="ui fluid search selection dropdown">
                <input type="hidden" name="userSettings[date_format]" value="{{ strtolower($userSettings['date_format']) }}" />
                <i class="dropdown icon"></i>
                <div class="default text">{{ message("common", "date-format") }}</div>
                <div class="menu">
                    @foreach($options['date_format'] as $value => $timestamp)
                        <div class="item" data-value="{{ $value }}">
                            {{ $timestamp[0] }}
                            <br />
                            <small>
                            {{ $timestamp[1] }}
                            </small>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="field">
        <label>{{ message("common", "input-date-format") }}</label>
        <div class="ui input">
            <div class="ui fluid search selection dropdown">
                <input type="hidden" name="userSettings[input_date_format]" value="{{ strtolower($userSettings['input_date_format']) }}" />
                <i class="dropdown icon"></i>
                <div class="default text">{{ message("common", "input-date-format") }}</div>
                <div class="menu">
                    @foreach($options['input_date_format'] as $value => $timestamp)
                        <div class="item" data-value="{{ $value }}">
                            {{ $timestamp[0] }}
                            <span class="description">
                                {{ $timestamp[1] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="field">
        <label>{{ message("common", "timezone") }}</label>
        <div class="ui input">
            <div class="ui fluid search selection dropdown">
                <input type="hidden" name="userSettings[timezone]" value="{{ strtolower($userSettings['timezone']) }}" />
                <i class="dropdown icon"></i>
                <div class="default text">{{ message("common", "timezone") }}</div>
                <div class="menu">
                    @foreach($options['timezones'] as $country => $timezones)
                        <div class="item header disabled">
                            {{ $country }}
                        </div>
                        @foreach($timezones as $timezone => $timestamp)
                            <div class="item" data-value="{{ strtolower($timezone) }}">
                                {{ $timezone }}
                                <span class="description">
                                    {{ $timestamp }}
                                </span>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    </div>

	<input class="ui basic button blue" type="submit" name="userSettings[submit]" value="{{ message('common', 'update') }}" />

</form>
