<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trip Planner</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <!-- Select2 CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>
<body>

<div class="container mt-5">
  <form id="trip_form">
    <div class="form-group">
      <label for="from">From:</label>
      <input type="text" class="form-control" id="from" placeholder="Enter starting point">
    </div>
    <input type="hidden" class="form-control" id="from_lat" name="from_lat">
    <input type="hidden" class="form-control" id="from_lng" name="from_lng">
    
    <div class="form-group">
      <label for="to">To:</label>
      <select class="form-control select2" id="to" name="to">
          @foreach ($places as $place)
          <option value="{{$place->id}}">{{$place->name}}</option>
          @endforeach
      
        <!-- Add more options as needed -->
      </select>
    </div>
 
  </form>
  <button type="button" class="btn btn-primary" id="submit-btn" onclick="submit()">Submit</button>
</div>

<div class="container mt-5 p-3">
    <div id="map" style="width: 100%; height: 100vh;"></div>
</div>

<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Google Places API -->
<script src="https://maps.googleapis.com/maps/api/js?key={{config('services.google-map.api-key')}}&libraries=places"></script>
<!-- Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
  // Initialize Google Autocomplete
  function initializeAutocomplete() {
    var input = document.getElementById('from');
    var latInput = document.getElementById('from_lat');
    var lngInput = document.getElementById('from_lng');
    var defaultBounds = new google.maps.LatLngBounds(
      new google.maps.LatLng(8.5, 74.5), // Southwest corner of Kerala
      new google.maps.LatLng(12.75, 77.5) // Northeast corner of Kerala
    );
    var options = {
        types: ['(regions)'],
      strictBounds: true,
      bounds: defaultBounds
    };
    var autocomplete = new google.maps.places.Autocomplete(input,options);
    autocomplete.addListener('place_changed', function() {
      var place = autocomplete.getPlace();
      if (!place.geometry) {
        // Place details not found
        return;
      }

      // Update hidden inputs with latitude and longitude
      latInput.value = place.geometry.location.lat();
      lngInput.value = place.geometry.location.lng();
    });
  }
  google.maps.event.addDomListener(window, 'load', initializeAutocomplete);

  // Initialize Select2
  $(document).ready(function() {
    $('.select2').select2();
  });


  function submit(){
    $.ajax({
            url: "{{ route('home.search') }}",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: $('#trip_form').serialize(),
            beforeSend: function() {
                $('#submit-btn').prop('disabled', true);
                $('#submit-btn').html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...'
                    );
            },
            complete: function() {
                $('#submit-btn').prop('disabled', false);
                $('#submit-btn').html('Submit');
            },
            success: function(data) {
                if (data.success) {
                    showRoute(data);
                } else {
                
                }

            }

        });
  }

  function showRoute(data) {
    var origin = new google.maps.LatLng(data.data.origin.lat, data.data.origin.long);
    var destination = new google.maps.LatLng(data.data.destination.lat, data.data.destination.long);
    var waypoints = data.data.waypoints.map(function(waypoint) {
        return {
            location: new google.maps.LatLng(waypoint.latitude, waypoint.longitude),
            stopover: true
        };
    });

    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 10,
        center: origin
    });

    var directionsService = new google.maps.DirectionsService();
    var directionsRenderer = new google.maps.DirectionsRenderer({
        map: map
    });

    var request = {
        origin: origin,
        destination: destination,
        waypoints: waypoints,
        travelMode: google.maps.TravelMode.DRIVING
    };

    directionsService.route(request, function(result, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            directionsRenderer.setDirections(result);
        } else {
            console.error('Directions request failed due to ' + status);
        }
    });
}

</script>

</body>
</html>
