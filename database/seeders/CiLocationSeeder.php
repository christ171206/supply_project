<?php

namespace Database\Seeders;

use App\Models\CiRegion;
use App\Models\CiDistrict;
use App\Models\CiCommune;
use App\Models\CiQuartier;
use Illuminate\Database\Seeder;

class CiLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Régions
        $regions = [
            ['name' => 'Cascades', 'code' => 'CAS'],
            ['name' => 'Savanes', 'code' => 'SAV'],
            ['name' => 'Vallée du Bandama', 'code' => 'VDB'],
            ['name' => 'Worodougou', 'code' => 'WOR'],
            ['name' => 'Zanzan', 'code' => 'ZAN'],
            ['name' => 'Lacs', 'code' => 'LAC'],
            ['name' => 'Marahoué', 'code' => 'MAR'],
            ['name' => 'Moyen-Sassandra', 'code' => 'MOS'],
            ['name' => 'Bas-Sassandra', 'code' => 'BAS'],
            ['name' => 'Gôh-Djiboua', 'code' => 'GDJ'],
            ['name' => 'Littoral', 'code' => 'LIT'],
            ['name' => 'Lagunes', 'code' => 'LAG'],
        ];

        foreach ($regions as $region) {
            CiRegion::create($region);
        }

        // Districts d'Abidjan (Région Lagunes)
        $abidjanRegion = CiRegion::where('code', 'LAG')->first();
        $abidjanDistricts = [
            ['region_id' => $abidjanRegion->id, 'name' => 'Abidjan', 'code' => 'ABJ'],
        ];

        foreach ($abidjanDistricts as $district) {
            $d = CiDistrict::create($district);

            // Communes d'Abidjan
            $communes = [
                ['district_id' => $d->id, 'name' => 'Abobo', 'code' => 'ABO'],
                ['district_id' => $d->id, 'name' => 'Adjamé', 'code' => 'ADJ'],
                ['district_id' => $d->id, 'name' => 'Anyama', 'code' => 'ANY'],
                ['district_id' => $d->id, 'name' => 'Attécoubé', 'code' => 'ATT'],
                ['district_id' => $d->id, 'name' => 'Cocody', 'code' => 'COC'],
                ['district_id' => $d->id, 'name' => 'Koumassi', 'code' => 'KOU'],
                ['district_id' => $d->id, 'name' => 'Marcory', 'code' => 'MAR'],
                ['district_id' => $d->id, 'name' => 'Plateau', 'code' => 'PLA'],
                ['district_id' => $d->id, 'name' => 'Port-Bouët', 'code' => 'PBT'],
                ['district_id' => $d->id, 'name' => 'Treichville', 'code' => 'TRE'],
                ['district_id' => $d->id, 'name' => 'Yopougon', 'code' => 'YOP'],
            ];

            foreach ($communes as $commune) {
                $c = CiCommune::create($commune);

                // Quartiers pour chaque commune
                $quartiersByCommune = [
                    'ABO' => ['Abobo-Gare', 'Abobo-Doumé', 'Abobo-DFC', 'Abobo-Village'],
                    'ADJ' => ['Adjamé Centre', 'Adjamé Nord', 'Adjouffou', 'Akouédo'],
                    'ANY' => ['Anyama Centre', 'Anyama Résidentiel', 'Anyama Industrial'],
                    'ATT' => ['Attécoubé Centre', 'Attécoubé Commercial', 'Attécoubé Est'],
                    'COC' => ['Cocody Centre', 'Cocody-Golf', 'Riviera', '2-Plateaux'],
                    'KOU' => ['Koumassi Centre', 'Koumassi Est', 'Koumassi Commercial'],
                    'MAR' => ['Marcory Centre', 'Marcory Est', 'Marcory Résidentiel'],
                    'PLA' => ['Plateau Centre', 'Plateau Commercial', 'Plateau Administratif'],
                    'PBT' => ['Port-Bouët Centre', 'Port-Bouët Aéroport', 'Port-Bouët Port'],
                    'TRE' => ['Treichville Centre', 'Treichville Est', 'Treichville Commercial'],
                    'YOP' => ['Yopougon Centre', 'Yopougon Est', 'Yopougon Industrial', 'Yopougon Nord'],
                ];

                if (isset($quartiersByCommune[$commune['code']])) {
                    foreach ($quartiersByCommune[$commune['code']] as $quartier) {
                        CiQuartier::create([
                            'commune_id' => $c->id,
                            'name' => $quartier,
                        ]);
                    }
                }
            }
        }

        // Autres districts (exemple: Yamoussoukro)
        $yamRegion = CiRegion::where('code', 'VDB')->first();
        if ($yamRegion) {
            $yamDistrict = CiDistrict::create([
                'region_id' => $yamRegion->id,
                'name' => 'Yamoussoukro',
                'code' => 'YAM',
            ]);

            $yamCommune = CiCommune::create([
                'district_id' => $yamDistrict->id,
                'name' => 'Yamoussoukro Centre',
                'code' => 'YAC',
            ]);

            $yamQuartiers = ['Centre-Ville', 'Résidentiel', 'Commercial', 'Administratif'];
            foreach ($yamQuartiers as $quartier) {
                CiQuartier::create([
                    'commune_id' => $yamCommune->id,
                    'name' => $quartier,
                ]);
            }
        }
    }
}
