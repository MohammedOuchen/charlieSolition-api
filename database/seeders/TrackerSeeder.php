<?php

namespace Database\Seeders;

use App\Models\MqttMessage;
use App\Models\Sensor;
use App\Models\Tracker;
use Illuminate\Database\Seeder;
use PhpParser\Node\Stmt\TryCatch;

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
                if (!is_null($data) && !array_key_exists('ble_payload', $data)) {
                    $tracker = new Tracker();
                    $tracker->tracker_id = array_key_exists('s', $data) ? $data['s'] : null;
                    $tracker->timestamp = array_key_exists('ts', $data) ? $data['ts'] : null;
                    $tracker->model = array_key_exists('m', $data) ? $data['m'] : null;
                    $tracker->latitude = array_key_exists('loc', $data) ? $data['loc'][0] : null;
                    $tracker->longitude = array_key_exists('loc', $data) ? $data['loc'][1] : null;
                    $tracker->frame_id = array_key_exists('v', $data) ? $this->extractFrameId($data['v']['ID_FRAME']['value']) : null;
                    $tracker->temperature = array_key_exists('v', $data) ? $data['v']['temperature']['value'] : null;
                    $tracker->battery = array_key_exists('v', $data) ? $data['v']['battery']['value'] : null;
                    $tracker->gps_valid_position = array_key_exists('v', $data) ? $data['v']['GPS']['validPosition'] : null;
                    $tracker->network_rsrp = array_key_exists('v', $data) ? $data['v']['network']['rsrp'] : null;
                    $tracker->save();
                } elseif (!is_null($data) && array_key_exists('ble_payload', $data)) {
                    $sensors = $data['ble_payload'];
                    $tracker_id = $this->extractFrameId($data["ID_FRAME"]);

                    $tracker = Tracker::where('frame_id', $tracker_id)->first();
                    if ($tracker) {
                        foreach ($sensors as $sensor) {
                            $capteurData = explode(';',$sensor);
                            $capteurModel = new Sensor();
                            $capteurModel->tracker_id = $tracker->id;
                            $capteurModel->type = $capteurData[0];
                            $capteurModel->id_frame = $capteurData[1];
                            $capteurModel->value = $capteurData[2];
                            $capteurModel->rssi = $capteurData[3];
                            $capteurModel->save();
                        }
                    }

                }

            }
        }
    }

    private function extractFrameId(string $frameId): ?string
    {
        return str_replace('id:', '', $frameId);
    }
}
