<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CiRegion;
use App\Models\CiDistrict;
use App\Models\CiCommune;
use App\Models\CiQuartier;
use Illuminate\Support\Str;

class CiLocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Régions de Côte d'Ivoire
        $regions = [
            ['name' => 'Abidjan', 'code' => 'ABJ'],
            ['name' => 'Nord', 'code' => 'NORD'],
            ['name' => 'Nord-Est', 'code' => 'NORD_EST'],
            ['name' => 'Est', 'code' => 'EST'],
            ['name' => 'Centre', 'code' => 'CENTRE'],
            ['name' => 'Centre-Nord', 'code' => 'CENTRE_NORD'],
            ['name' => 'Centre-Est', 'code' => 'CENTRE_EST'],
            ['name' => 'Sud', 'code' => 'SUD'],
            ['name' => 'Sud-Est', 'code' => 'SUD_EST'],
            ['name' => 'Sud-Ouest', 'code' => 'SUD_OUEST'],
            ['name' => 'Ouest', 'code' => 'OUEST'],
        ];

        foreach ($regions as $regionData) {
            $region = CiRegion::updateOrCreate(
                ['code' => $regionData['code']],
                ['name' => $regionData['name']]
            );

            // Ajouter les districts pour chaque région
            $this->addDistrictsForRegion($region);
        }
    }

    private function addDistrictsForRegion($region)
    {
        $districtData = [
            'ABJ' => [
                ['name' => 'Abidjan 1', 'communes' => ['Plateau', 'Cocody', 'Deux-Plateaux']],
                ['name' => 'Abidjan 2', 'communes' => ['Yopougon', 'Attécoubé', 'Koumassi']],
                ['name' => 'Abidjan 3', 'communes' => ['Treichville', 'Port-Bouët', 'Adjamé']],
                ['name' => 'Abidjan 4', 'communes' => ['Abobo', 'Alépé', 'Bingerville']],
            ],
            'NORD' => [
                ['name' => 'Korhogo', 'communes' => ['Korhogo', 'Sirasso', 'M\'bé']],
            ],
            'NORD_EST' => [
                ['name' => 'Bondoukou', 'communes' => ['Bondoukou', 'Soko', 'Assuéfry']],
            ],
            'EST' => [
                ['name' => 'Abengourou', 'communes' => ['Abengourou', 'Ndouci', 'Eloka']],
            ],
            'CENTRE' => [
                ['name' => 'Yamoussoukro', 'communes' => ['Yamoussoukro', 'Attiégouakro']],
            ],
            'CENTRE_NORD' => [
                ['name' => 'Bouaké', 'communes' => ['Bouaké', 'Sakassou', 'Godouré']],
            ],
            'CENTRE_EST' => [
                ['name' => 'Dimbokro', 'communes' => ['Dimbokro', 'Bongouanou']],
            ],
            'SUD' => [
                ['name' => 'Duekoué', 'communes' => ['Duekoué', 'Guégué']],
            ],
            'SUD_EST' => [
                ['name' => 'Aboisso', 'communes' => ['Aboisso', 'Bettié']],
            ],
            'SUD_OUEST' => [
                ['name' => 'Sassandra', 'communes' => ['Sassandra', 'Soubré', 'Gagnoa']],
            ],
            'OUEST' => [
                ['name' => 'Man', 'communes' => ['Man', 'Danané', 'Biankouma']],
            ],
        ];

        $regionCode = $region->code;
        $districts = isset($districtData[$regionCode]) ? $districtData[$regionCode] : [];

        foreach ($districts as $districtInfo) {
            $district = CiDistrict::updateOrCreate(
                ['region_id' => $region->id, 'code' => Str::slug($districtInfo['name'])],
                ['name' => $districtInfo['name']]
            );

            // Ajouter les communes
            foreach ($districtInfo['communes'] as $communeName) {
                $commune = CiCommune::updateOrCreate(
                    ['district_id' => $district->id, 'code' => Str::slug($communeName)],
                    ['name' => $communeName]
                );

                // Ajouter les quartiers pour les communes d'Abidjan
                if ($region->code === 'ABJ') {
                    $this->addQuartiersForCommune($commune, $communeName);
                }
            }
        }
    }

    private function addQuartiersForCommune($commune, $communeName)
    {
        $quartiersByCommune = [
            'Plateau' => ['Plateau', 'Réserve', 'Parc'],
            'Cocody' => ['Cocody', 'Bonoumin', 'Abourépay'],
            'Deux-Plateaux' => ['Deux-Plateaux', 'Les Almadies'],
            'Yopougon' => ['Yopougon', 'Anonkoua-Kouté', 'Azaguié'],
            'Attécoubé' => ['Attécoubé', 'Abobo-Doussin'],
            'Koumassi' => ['Koumassi', 'Gesco'],
            'Treichville' => ['Treichville', 'Akouedo'],
            'Port-Bouët' => ['Port-Bouët', 'Port'],
            'Adjamé' => ['Adjamé', 'PK 18'],
            'Abobo' => ['Abobo', 'Saint-Jean', 'Sagbé'],
            'Alépé' => ['Alépé', 'M\'pouto'],
            'Bingerville' => ['Bingerville', 'Bananier'],
        ];

        $quartiers = isset($quartiersByCommune[$communeName]) ? $quartiersByCommune[$communeName] : [$communeName];

        foreach ($quartiers as $quartierName) {
            CiQuartier::updateOrCreate(
                ['commune_id' => $commune->id, 'name' => $quartierName],
                ['name' => $quartierName]
            );
        }
    }
}
