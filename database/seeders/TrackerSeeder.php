<?php

namespace Database\Seeders;

use App\Models\MqttMessage;
use App\Models\Tracker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TrackerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $mqttMessages = MqttMessage::all();

      foreach ($mqttMessages as $mqtt) {

        if (!is_null($mqtt->message)) {
            $data = json_decode($mqtt->message, true);
            if (!is_null($data)) {
                $tracker = new Tracker();
                $tracker->tracker_id = array_key_exists('s', $data) ? $data['s'] : null;
                $tracker->timestamp = array_key_exists('ts', $data) ? $data['ts'] : null;
                $tracker->model = array_key_exists('m', $data) ? $data['m'] : null;
                $tracker->latitude = array_key_exists('loc', $data) ? $data['loc'][0] : null;
                $tracker->longitude = array_key_exists('loc', $data) ? $data['loc'][1] : null;
                $tracker->temperature = array_key_exists('v', $data) ? $data['v']['temperature']['value'] : null;
                $tracker->battery = array_key_exists('v', $data) ? $data['v']['battery']['value'] : null;
                $tracker->gps_valid_position = array_key_exists('v', $data) ? $data['v']['GPS']['validPosition'] : null;
                $tracker->network_rsrp = array_key_exists('v', $data) ? $data['v']['network']['rsrp'] : null;
                $tracker->save();
            }
        }
    }
    }
}
