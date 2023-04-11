@if($status == true)
	@foreach($addresses as $address)
        <div class="ui grid margin top bottom none">
            <div class="eleven wide column computer eleven wide column tablet eleven wide column mobile">
                <p style="line-height: 16px">
                    {{ ownName($address['street']) }}, {{ $address['number'] }}<br />
                    {{ ownName($address['district']) }} - {{ $address['complement'] }}<br />
                    <strong>
                        {{ ownName($address['city']) }} - {!! strtoupper($address['state']) !!}
                    </strong>
                </p>
            </div>
            <div class="five wide column computer five wide column tablet five wide column mobile right aligned">
                <a href="https://www.google.com.br/maps/?q={{ $address['street'] }}+,{{ $address['number'] }}+,{{ $address['district'] }}+,{{ $address['city'] }}+,{{ $address['state'] }}" style="width: 60px; height: 60px; border-radius: 50% !important; overflow: hidden; display: block;">
                    <img src="https://maps.googleapis.com/maps/api/staticmap?center={{ $address['street'] }}+,{{ $address['number'] }}+,{{ $address['district'] }}+,{{ $address['city'] }}+,{{ $address['state'] }}&zoom=14&size=60x60&maptype=roadmap&markers=color:red%7%7C{{ $address['street'] }}+,{{ $address['number'] }}+,{{ $address['district'] }}+,{{ $address['city'] }}+,{{ $address['state'] }}&key=AIzaSyBoRqJe2ioT4TC1cJxrgbFLOzjNqRekTV0" />
                </a>
            </div>
        </div>
	@endforeach
@endif
