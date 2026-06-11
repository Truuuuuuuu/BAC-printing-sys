<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CityMunicipality;
use App\Models\Barangay;

class SorsogonAddressSeeder extends Seeder
{
    public function run(): void
    {
        $csvPath = database_path('seeders/data/sorsogon_psgc.csv');

        if (!file_exists($csvPath)) {
            $this->command->error('CSV file not found at: ' . $csvPath);
            return;
        }

        $file      = fopen($csvPath, 'r');
        $cities    = [];
        $barangays = [];

        while (($row = fgetcsv($file)) !== false) {
            [$psgcCode, $name, $geoLevel] = $row;

            $psgcCode = trim($psgcCode);
            $name     = trim($name);
            $geoLevel = strtolower(trim($geoLevel));

            if (in_array($geoLevel, ['city', 'mun'])) {
                $city              = CityMunicipality::firstOrCreate(['name' => $name]);
                $cities[$psgcCode] = $city;
            }

            if ($geoLevel === 'bgy') {
                $barangays[] = [
                    'psgc_code'      => $psgcCode,
                    'name'           => $name,
                    'city_psgc_code' => substr($psgcCode, 0, 7) . '000',
                ];
            }
        }

        fclose($file);

        foreach ($barangays as $bgy) {
            $city = $cities[$bgy['city_psgc_code']] ?? null;

            if (!$city) continue;

            Barangay::firstOrCreate([
                'city_id' => $city->id,
                'name'    => $bgy['name'],
            ]);
        }

        $this->command->info('Sorsogon cities and barangays seeded successfully.');
    }
}