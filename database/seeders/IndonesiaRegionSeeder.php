<?php

namespace Database\Seeders;

use App\Models\Province;
use App\Models\City;
use App\Models\District;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class IndonesiaRegionSeeder extends Seeder
{
    private $baseUrl = 'https://www.emsifa.com/api-wilayah-indonesia/api';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🇮🇩 Fetching Indonesia regions data from API...');
        
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Truncate tables
        District::truncate();
        City::truncate();
        Province::truncate();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Fetch and seed provinces
        $this->seedProvinces();
        
        $this->command->info('✅ Indonesia regions seeded successfully!');
    }

    private function seedProvinces(): void
    {
        $this->command->info('📍 Fetching provinces...');
        
        $response = Http::get("{$this->baseUrl}/provinces.json");
        
        if ($response->failed()) {
            $this->command->error('Failed to fetch provinces');
            return;
        }

        $provinces = $response->json();
        $bar = $this->command->getOutput()->createProgressBar(count($provinces));
        $bar->start();

        foreach ($provinces as $province) {
            Province::create([
                'id' => $province['id'],
                'name' => $province['name'],
            ]);

            // Seed cities for this province
            $this->seedCities($province['id']);
            
            $bar->advance();
        }

        $bar->finish();
        $this->command->newLine();
        $this->command->info("✓ Seeded " . count($provinces) . " provinces");
    }

    private function seedCities(string $provinceId): void
    {
        $response = Http::get("{$this->baseUrl}/regencies/{$provinceId}.json");
        
        if ($response->failed()) {
            return;
        }

        $cities = $response->json();

        foreach ($cities as $city) {
            City::create([
                'id' => $city['id'],
                'province_id' => $city['province_id'],
                'name' => $city['name'],
            ]);

            // Seed districts for this city
            $this->seedDistricts($city['id']);
        }
    }

    private function seedDistricts(string $cityId): void
    {
        $response = Http::get("{$this->baseUrl}/districts/{$cityId}.json");
        
        if ($response->failed()) {
            return;
        }

        $districts = $response->json();

        $districtData = [];
        foreach ($districts as $district) {
            $districtData[] = [
                'id' => $district['id'],
                'city_id' => $district['regency_id'],
                'name' => $district['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Batch insert for better performance
        if (!empty($districtData)) {
            District::insert($districtData);
        }
    }
}
