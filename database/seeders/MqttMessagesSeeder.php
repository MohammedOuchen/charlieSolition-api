<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MqttMessagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sqlFile = storage_path('app/charlie_mqtt_messages.sql');

        if (File::exists($sqlFile)) {
            $sql = File::get($sqlFile);

            DB::unprepared($sql);

            $this->command->info('Données importées avec succès.');
        } else {
            $this->command->error('Fichier SQL introuvable.');
        }
    }
}
