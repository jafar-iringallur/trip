<?php

namespace App\Http\Controllers;

use App\Library\Polyline;
use GuzzleHttp\Client;
use App\Models\Place;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $places = Place::all();
        return view('home',['places'=> $places]);
    }

    public function search(Request $request){
        $validator = \Validator::make($request->all(), [
            'to' => 'required',
            'from_lat' => 'required',
            'from_lng' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }
        $to = Place::find($request->to);
        if(!isset($to)){
            return response()->json([
                'success' => false,
                'message' => "not found",
            ]);
        }
        $apiEndpoint = 'https://maps.googleapis.com/maps/api/directions/json';
        $origin = [
            "lat" => $request->from_lat,
            "long" => $request->from_lng,
        ];
        $destination = [
            "lat" => $to->latitude,
            "long" => $to->longitude,
        ];
        $apiKey = config('services.google-map.api-key');

        // Create a Guzzle client
        $client = new Client();

        // Make the API request
        $response = $client->get($apiEndpoint, [
            'query' => [
                'origin' => $origin['lat'] ."," . $origin['long'],
                'destination' => $destination['lat'] ."," . $destination['long'],
                'key' => $apiKey,
                'mode' => "DRIVING"
            ],
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
        $data = json_decode($response->getBody(), true);
        // dd( $data);
        $overview_polyline = $data['routes'][0]['overview_polyline']['points'];
        // dd( $overview_polyline);
        $waypoints = Polyline::decode2($overview_polyline);

       
        $places = Place::where('id','!=',$request->to)
        ->select(['name','latitude','longitude'])
        // ->whereBetween('latitude', [$destination['lat'], $origin['lat']])
        // ->whereBetween('longitude', [$origin['long'], $destination['long']])
        ->get();
    

        $nearestCities = [];

        for ($j = 0; $j < count($waypoints); $j += 20) {
            // Loop through cities
            foreach ($places as $city) {
                $cityLocation = [
                    'lat' => $city['latitude'],
                    'lng' => $city['longitude'],
                ];

                $distance = $this->computeDistance(
                    ['lat' => $waypoints[$j][0], 'lng' => $waypoints[$j][1]],
                    $cityLocation
                );
                if ($distance < 20000) {
                    $cityIndex = array_search($city['name'], array_column($nearestCities, 'name'));
                    if ($cityIndex === false) {
                        $nearestCities[] = $city;
                    }
                }
            }
        }

        $data = [
            'origin' => $origin,
            'destination' =>  $destination,
            'waypoints' => $nearestCities
        ];
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    private function computeDistance($point1, $point2)
    {
        $lat1 = deg2rad($point1['lat']);
        $lon1 = deg2rad($point1['lng']);
        $lat2 = deg2rad($point2['lat']);
        $lon2 = deg2rad($point2['lng']);

        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;

        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $radius = 6371; // Earth radius in kilometers. You can change this value if needed.

        $distance = $radius * $c;

        return $distance * 1000; // Convert to meters
    }
}
