<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Sensor;
use App\Models\Tracker;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class TrackerController extends Controller
{

    public function dailySensorCounts () {
        $trackers = Tracker::all();

        $groupedTrackers = $trackers->groupBy(function ($tracker) {
            return Carbon::parse($tracker->timestamp)->toDateString();
        });

        $sensorCounts = $groupedTrackers->map(function ($group, $date) {
            return [
                'date' => $date,
                'sensor_count' => count($group)
            ];
        });

       return $sensorCounts;
    }

    function geoFencingTrackers(){
        // Coordinates of Charlie's construction site
        $siteLatitude = 50.6337848;
        $siteLongitude = 3.0217842;
        $maxDistance = 500; // Maximum geofencing distance in meters

        // Find trackers located within 500 meters of the construction site
        $trackers = Tracker::all()->filter(function ($tracker) use ($siteLatitude, $siteLongitude, $maxDistance) {
            $distance = $this->calculateDistance($tracker->latitude, $tracker->longitude, $siteLatitude, $siteLongitude);
            return $distance <= $maxDistance;
        });

        // Group trackers by day
        $trackersByDay = $trackers->groupBy(function ($tracker) {
            return Carbon::parse($tracker->timestamp)->toDateString();
        });

        // Collection to store the results
        $results = collect();

        // Iterate through each group of trackers by day
        $trackersByDay->each(function ($trackers, $day) use ($results, $maxDistance) {
            $dayResult = [
                'day' => $day,
                'sensors_in_zone' => collect(),
            ];

            // Retrieve sensors for each tracker and check distance
            $trackers->each(function ($tracker) use (&$dayResult, $maxDistance) {
                $sensors = Sensor::where('tracker_id', $tracker->id)->get();
                $sensorsInZone = $sensors->filter(function ($sensor) use ($tracker, $maxDistance) {
                    $distance = $this->calculateDistanceSensorTracker($sensor->rssi);
                    return $distance <= $maxDistance;
                });

                $dayResult['sensors_in_zone']->push([
                    'tracker_id' => $tracker->id,
                    'sensors' => $sensorsInZone,
                ]);
            });

            // Add the day's result to the global collection
            $results->push($dayResult);
        });

        return $results;
    }

    function calculateDistance($latitude1, $longitude1, $latitude2, $longitude2) {
        // Convert degrees to radians
        $lat1 = deg2rad($latitude1);
        $lon1 = deg2rad($longitude1);
        $lat2 = deg2rad($latitude2);
        $lon2 = deg2rad($longitude2);

        // Calculate latitude and longitude differences
        $deltaLat = $lat2 - $lat1;
        $deltaLon = $lon2 - $lon1;

        // Calculate distance using the spherical formula
        $a = sin($deltaLat / 2) * sin($deltaLat / 2) + cos($lat1) * cos($lat2) * sin($deltaLon / 2) * sin($deltaLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = 6371 * $c;

        return $distance;
    }

    function calculateDistanceSensorTracker($rssi) {
        // Distance calculation formula based on RSSI
        $distance = pow(10, (-40 - $rssi) / 20);
        return $distance;
    }


}
