<style type="text/css">

    #map {
        width: 100%;
        height: 400px;
    }

    .controls {
        margin-top: 10px !important;
        border: 1px solid transparent;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        height: 32px;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    }

    #pac-input {
        background-color: #fff;
        font-size: 15px;
        font-weight: 300;
        margin-left: 12px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 200px;
    }

    #pac-input:focus {
        border-color: #4d90fe;
    }

    #type-selector {
        color: #fff;
        background-color: #4d90fe;
        padding: 5px 11px 0px 11px;
    }

    #type-selector label {
        font-size: 12px;
        font-weight: 300;
    }

    #target {
        width: 345px;
    }

</style>


    <form class="ui form">

        <div class="ui menu secondary pointing fluid blue margin bottom top none">
            <div class="item active" data-tab="scheduling.index">
                <i class="icon-doc-new icon"></i>
                {{ message('common', 'data') }}
            </div>
            <div class="item" data-tab="scheduling.location">
                <i class="icon-location icon"></i>
                {{ message('common', 'add') }}
                {{ message('common', 'location') }}
            </div>
        </div>

        <div class="ui tab" data-tab="scheduling.location">
            <input id="pac-input" class="controls" type="text" placeholder="{{ message('common', 'search') }}">
            <div id="map"></div>
        </div>

    	<input type="hidden" name="scheduling[id]" value="{{ $form['id']['value'] }}" />
    	<input type="hidden" name="scheduling[controller]" value="{{ $form['controller']['value'] }}" />
    	<input type="hidden" name="scheduling[controller_id]" value="{{ $form['controller_id']['value'] }}" />

        <div class="ui tab active" data-tab="scheduling.index">

            <div class="ui basic segment" style="background-color: #eee">

                <div class="field">
            		<div class="ui input wide icon">
            			<i class="icon-doc-new icon"></i>
            			<input placeholder="{{ message("common", "subject") }}" type="text" maxlentgh="96" name="scheduling[title]" data-value value="{{ $form['title']['value'] }}" required />
            		</div>
            	</div>

            	<div class="field">
            		<div class="ui input wide icon">
            			<i class="icon-comment-alt icon"></i>
            			<textarea placeholder="{{ message("common", "description") }}" name="scheduling[description]" rows="2" data-value value="{{ $form['description']['value'] }}" required></textarea>
            		</div>
            	</div>

            	<div class="ui grid">

            		<div class="four wide column computer four wide column tablet four wide column mobile" style="padding-right: 0">
            			<div class="field">
            				<div class="ui input wide">
            					<input type="text" class="clockpicker" name="scheduling[start_hour_at]" data-value value="{{ $form['start_hour_at']['value'] }}" required />
            				</div>
            			</div>
            		</div>
            		<div class="twelve wide column computer twelve wide column tablet twelve wide column mobile">
            			<div class="field">
            				<div class="ui input wide">
            					<input type="text" datepicker name="scheduling[start_at]" data-value value="{{ $form['start_at']['value'] }}" required />
            				</div>
            			</div>
            		</div>

            	</div>

                <input type="hidden" id="latitude"  name="scheduling[latitude]" />
                <input type="hidden" id="longitude" name="scheduling[longitude]" />

                <div class="row">
                    <br />
                </div>

                <input class="ui basic green button" type="submit" name="scheduling[submit]" value="{{ $form['submit']['value'] }}" />

            </div>

        </div>

    </form>



    <script type="text/javascript">

    initLocation();

    var myLat = 0;
    var myLng = 0;

    function initLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        }
    }

    function showPosition(position) {
        myLat = position.coords.latitude;
        if (myLat == null) {
            myLat = 0;
        }
        document.getElementById('latitude').value = myLat;
        myLng = position.coords.longitude;
        if (myLng == null) {
            myLng = 0;
        }
        document.getElementById('longitude').value = myLng;
    }


    function initAutocomplete() {
      var map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: myLat, lng: myLng},
        zoom: 15,
        

        disableDefaultUI: false,
        mapTypeControl: false,
        scaleControl: true,
        zoomControl: false,
        zoomControlOptions: {
            style: google.maps.ZoomControlStyle.LARGE 
        },
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        fullScreenControl: true,
        enableCloseButton: true,
        addressControlOptions: {
             position: google.maps.ControlPosition.BOTTOM_CENTER
        }

      });

      // Create the search box and link it to the UI element.
      var input = document.getElementById('pac-input');
      var searchBox = new google.maps.places.SearchBox(input);
      map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

      // Bias the SearchBox results towards current map's viewport.
      map.addListener('bounds_changed', function() {
        searchBox.setBounds(map.getBounds());
      });

      var markers = [];



      var marker = new google.maps.Marker({
          position: {lat: myLat, lng: myLng},
          map: map,
          draggable: true
      });

      google.maps.event.addListener(marker, 'dragend', function(evt){
          document.getElementById('latitude').value = evt.latLng.lat();
          document.getElementById('longitude').value = evt.latLng.lng();
      });

      searchBox.addListener('places_changed', function() {
        var places = searchBox.getPlaces();

        if (places.length == 0) {
          return;
        }

        // Clear out the old markers.
        markers.forEach(function(marker) {
          marker.setMap(null);
        });
        markers = [];

        // For each place, get the icon, name and location.
        var bounds = new google.maps.LatLngBounds();
        places.forEach(function(place) {
          var icon = {
            url: place.icon,
            size: new google.maps.Size(71, 71),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(17, 34),
            scaledSize: new google.maps.Size(25, 25)
          };

          // Create a marker for each place.
          markers.push(new google.maps.Marker({
            map: map,
            icon: icon,
            title: place.name,
            position: place.geometry.location
          }));

          if (place.geometry.viewport) {
            // Only geocodes have viewport.
            bounds.union(place.geometry.viewport);
          } else {
            bounds.extend(place.geometry.location);
          }
        });
        map.fitBounds(bounds);
      });
      // [END region_getplaces]
    }

    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBoRqJe2ioT4TC1cJxrgbFLOzjNqRekTV0&libraries=places&callback=initAutocomplete"
     async defer></script>
