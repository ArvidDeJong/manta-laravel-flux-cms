<script>
    // Initialize and add the map
    var map;
    var markers = {}; // An object to store markers by their IDs

    async function initMap() {
        // The location of Uluru
        const position = {
            lat: {{ $DEFAULT_LATITUDE }},
            lng: {{ $DEFAULT_LONGITUDE }}
        };
        // Request needed libraries.
        //@ts-ignore
        const {
            Map
        } = await google.maps.importLibrary("maps");
        const {
            AdvancedMarkerElement
        } = await google.maps.importLibrary("marker");

        // The map, centered at Uluru
        map = new Map(document.getElementById("map"), {
            zoom: {{ $GOOGLE_MAPS_ZOOM ?? 14 }},
            center: position,
            mapTypeId: '{{ $mapTypeId ?? 'satellite' }}',
            mapId: "map_{{ $maps_id ?? 'maps' }}",
        });

        @if (isset($markers) && is_array($markers))
            @foreach ($markers as $marker)

                createMarker('marker{{ $marker['id'] }}', {{ $marker['latitude'] }}, {{ $marker['longitude'] }},
                    '{{ $marker['title'] }}', {{ $marker['draggable'] ? 'true' : 'false' }});
            @endforeach
        @endif

    }
    initMap();

    // Function to create a marker with a given ID
    function createMarker(id, latitude, longitude, title, draggable = true) {
        var position = {
            lat: latitude,
            lng: longitude
        };
        markers[id] = new google.maps.Marker({
            position: position,
            map: map,
            title: title || '',
            id: id, // Custom ID for the marker
            draggable: draggable,
        });
        // Add event listener for marker dragend event
        markers[id].addListener('dragend', function() {
            // Retrieve the updated marker position
            var position = markers[id].getPosition();
            // Get the latitude and longitude
            @this.set('latitude', position.lat());
            @this.set('longitude', position.lng());
            @if (isset($maps_set_center))

                @this.dispatch('updateMapCenter', {
                    latitude: position.lat(),
                    longitude: position.lng()
                }, {{ $this->GOOGLE_MAPS_ZOOM }});
            @endif
        });
    }

    // Function to relocate a marker by its ID
    function moveMarkerById(id, latitude, longitude) {
        if (markers[id]) {
            var newPosition = new google.maps.LatLng(latitude, longitude);
            markers[id].setPosition(newPosition);
            map.setCenter(newPosition);
        }
    }

    // Example of relocating a marker by its ID
    function relocateMarkerById() {
        var markerId = 'marker1'; // Specify the ID of the marker to relocate
        var newLatitude = 37.7765;
        var newLongitude = -122.4181;

        // Call the moveMarkerById function to relocate the marker
        moveMarkerById(markerId, newLatitude, newLongitude);
    }
    document.addEventListener('livewire:initialized', () => {
        @this.on('updateMapCenter', (value) => {
            if (value[0]) value = value[0];
            console.log(value);
            map.setCenter(new google.maps.LatLng(parseFloat(value.latitude), parseFloat(value
                .longitude)));
            map.setZoom(value.zoom);
        });

        @this.on('updateMapType', (value) => {
            if (value[0]) value = value[0];
            console.log(value.type);
            map.setMapTypeId(value.type);
        });
    });
</script>
