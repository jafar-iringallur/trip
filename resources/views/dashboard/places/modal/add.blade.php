<style>
    .pac-container {
        z-index: 9999 !important;
    }
</style>
<div class="modal fade" id="add_place_modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-weight: 900" id="couponModalTitle">Add Place</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="map" style="height: 300px; width: 100%;"></div>
                <form class="row g-3" id="addUserForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="inputAddress">Name</label>
                            <input type="text" class="form-control" id="inputAddress" placeholder="search" name="inputAddress">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="inputName">Name</label>
                            <input type="text" class="form-control" id="inputName" placeholder="name" name="name">
                        </div>
                    </div>
                    <div class="form-row" style="display: none">
                        <div class="form-group">
                            <label for="inputLatitude">Latitude</label>
                            <input type="text" class="form-control" id="inputLatitude" placeholder="latitude" name="latitude">
                        </div>
                        <div class="form-group">
                            <label for="inputLongitude">Longitude</label>
                            <input type="text" class="form-control" id="inputLongitude" placeholder="longitude" name="longitude">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button onclick="add()" id="add-place-btn" class="btn btn-primary">Add</button>
            </div>
        </div>
    </div>
</div>

<script>
    let placePicker;

    function initMap() {
        map = new google.maps.Map(document.getElementById("map"), {
            center: { lat: -34.397, lng: 150.644 },
            zoom: 8,
        });

        const input = document.getElementById("inputAddress");
        const searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        map.addListener("bounds_changed", () => {
            searchBox.setBounds(map.getBounds());
        });

        searchBox.addListener("places_changed", () => {
            const places = searchBox.getPlaces();

            if (places.length === 0) {
                return;
            }

            const place = places[0];

            map.setCenter(place.geometry.location);
            map.setZoom(15);

            marker.setPosition(place.geometry.location);
            document.getElementById("inputLatitude").value = place.geometry.location.lat();
            document.getElementById("inputLongitude").value = place.geometry.location.lng();
        });

        marker = new google.maps.Marker({
            map,
            draggable: true,
        });

        marker.addListener("dragend", () => {
            document.getElementById("inputLatitude").value = marker.getPosition().lat();
            document.getElementById("inputLongitude").value = marker.getPosition().lng();
        });
    }

</script>

<!-- Include the Google Maps JavaScript API script -->
<script src="https://maps.googleapis.com/maps/api/js?key={{config('services.google-map.api-key')}}&libraries=places&callback=initMap" async defer></script>
