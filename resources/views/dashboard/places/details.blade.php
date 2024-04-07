@extends('dashboard.layouts.index')

@section('title')
    Place Details
@endsection
@section('header')
@include('dashboard.layouts.header')
@endsection
@section('sidebar')
@include('dashboard.layouts.sidebar')
@endsection
@section('page-content')
<meta name="csrf-token" content="{{ csrf_token() }}" />

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

     <div class="pagetitle">
       <h1>Place Details</h1>
       <nav>
         <ol class="breadcrumb">
           <li class="breadcrumb-item"><a href="/admin/home">Home</a></li>
           <li class="breadcrumb-item"><a href="{{route('places.index')}}">Places</a></li>
           <li class="breadcrumb-item active">{{$place['name']}}</li>
         </ol>
       </nav>
     </div><!-- End Page Title -->
 
     <section class="section dashboard">
    

      <div class="card">
         <div class="card-body">
           <h5 class="card-title">Place Details</h5>
                  <form class="row g-3" method="Post" action="{{route('places.update.details')}}">
                    @csrf
                    <input type="hidden" name="place_id" value="{{$place['id']}}">
                    @if($errors->any())
                    <div class="alert alert-danger" style="font-size: 14px">
                      {!! implode('', $errors->all('<div>:message</div>')) !!}
                    </div>
                    @endif
                     <div class="col-12">
                       <label for="inputNanme4" class="form-label">Name</label>
                       <input type="text" class="form-control" id="name" value="{{$place['name']}}" name="name" required>
                     </div>
                     <div class="form-row" style="display: none">
                        <div class="form-group">
                            <label for="inputLatitude">Latitude</label>
                            <input type="text" class="form-control" id="latitude" placeholder="latitude" name="latitude" value="{{$place['latitude']}}">
                        </div>
                        <div class="form-group">
                            <label for="inputLongitude">Longitude</label>
                            <input type="text" class="form-control" id="longitude" placeholder="longitude" name="longitude" value="{{$place['longitude']}}">
                        </div>
                    </div>
                    <div id="map" style="height: 400px; width: 100%;"></div>
                    <div class="col-12">
                        <label for="inputNanme4" class="form-label">Description</label>
                        <textarea class="form-control" name="meta[description]" required>{{$data['description'] ?? ""}}</textarea>
                      </div>
                      <div class="col-12">
                        <label for="inputNanme4" class="form-label">Opening Hours</label>
                        <input type="text" class="form-control"name="meta[opening_hour]" value="{{$data['opening_hour'] ?? ""}}" required>
                      </div>
                      <div class="col-12">
                        <label for="inputNanme4" class="form-label">Entry Fees</label>
                        <input type="text" class="form-control"name="meta[entry_fee]" value="{{$data['entry_fee'] ?? ""}}" required>
                      </div>
                     <div class="text-center">
                       <button type="submit" class="btn btn-primary" style="width: 90%">Update Details</button>
                      
                     </div>
                   </form>
               </div>
 
          </div>
           
       
     </section>
     <script src="https://maps.googleapis.com/maps/api/js?key={{config('services.google-map.api-key')}}&libraries=places&callback=initMap" async defer></script>

 <script>
function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: {{ $place['latitude'] }}, lng: {{ $place['longitude'] }} },
            zoom: 15
        });

        // Add marker to the map
        var marker = new google.maps.Marker({
            position: { lat: {{ $place['latitude'] }}, lng: {{ $place['longitude'] }} },
            map: map,
            draggable: true // Allow marker to be dragged
        });

        // Update latitude and longitude fields when marker is dragged
        google.maps.event.addListener(marker, 'dragend', function(event) {
            document.getElementById('latitude').value = event.latLng.lat();
            document.getElementById('longitude').value = event.latLng.lng();
        });
    }
    google.maps.event.addDomListener(window, 'load', initMap);

</script>
@endsection
