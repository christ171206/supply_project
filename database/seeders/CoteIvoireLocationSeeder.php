<?php

namespace Database\Seeders;

use App\Models\Commune;
use App\Models\District;
use App\Models\Quartier;
use App\Models\Region;
use Illuminate\Database\Seeder;

class CoteIvoireLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Abidjan
        $abidjan = Region::create(['name' => 'Abidjan', 'code' => 'ABJ']);

        // Districts d'Abidjan
        $cocody = District::create(['region_id' => $abidjan->id, 'name' => 'Cocody']);
        $plateau = District::create(['region_id' => $abidjan->id, 'name' => 'Plateau']);
        $attecoube = District::create(['region_id' => $abidjan->id, 'name' => 'Attécoubé']);
        $abobo = District::create(['region_id' => $abidjan->id, 'name' => 'Abobo']);
        $yopougon = District::create(['region_id' => $abidjan->id, 'name' => 'Yopougon']);
        $koumassi = District::create(['region_id' => $abidjan->id, 'name' => 'Koumassi']);
        $treichville = District::create(['region_id' => $abidjan->id, 'name' => 'Treichville']);
        $adjame = District::create(['region_id' => $abidjan->id, 'name' => 'Adjamé']);
        $marcory = District::create(['region_id' => $abidjan->id, 'name' => 'Marcory']);
        $songon = District::create(['region_id' => $abidjan->id, 'name' => 'Songon']);

        // Communes et Quartiers - Cocody
        $cocody_commune = Commune::create(['district_id' => $cocody->id, 'name' => 'Cocody']);
        Quartier::create(['commune_id' => $cocody_commune->id, 'name' => 'Bloc Administratif']);
        Quartier::create(['commune_id' => $cocody_commune->id, 'name' => 'Abatta']);
        Quartier::create(['commune_id' => $cocody_commune->id, 'name' => 'Berges du Lagon']);
        Quartier::create(['commune_id' => $cocody_commune->id, 'name' => '2 Plateaux']);
        Quartier::create(['commune_id' => $cocody_commune->id, 'name' => 'Danga']);
        Quartier::create(['commune_id' => $cocody_commune->id, 'name' => 'Vallon']);

        // Communes et Quartiers - Plateau
        $plateau_commune = Commune::create(['district_id' => $plateau->id, 'name' => 'Plateau']);
        Quartier::create(['commune_id' => $plateau_commune->id, 'name' => 'Centre Ville']);
        Quartier::create(['commune_id' => $plateau_commune->id, 'name' => 'Riviera']);
        Quartier::create(['commune_id' => $plateau_commune->id, 'name' => 'Cité Administrative']);
        Quartier::create(['commune_id' => $plateau_commune->id, 'name' => 'Port']);
        Quartier::create(['commune_id' => $plateau_commune->id, 'name' => 'Monument']);

        // Communes et Quartiers - Attécoubé
        $attecoube_commune = Commune::create(['district_id' => $attecoube->id, 'name' => 'Attécoubé']);
        Quartier::create(['commune_id' => $attecoube_commune->id, 'name' => 'Centre']);
        Quartier::create(['commune_id' => $attecoube_commune->id, 'name' => 'Abatta']);
        Quartier::create(['commune_id' => $attecoube_commune->id, 'name' => 'Remblai']);
        Quartier::create(['commune_id' => $attecoube_commune->id, 'name' => 'Vridi']);

        // Communes et Quartiers - Abobo
        $abobo_commune = Commune::create(['district_id' => $abobo->id, 'name' => 'Abobo']);
        Quartier::create(['commune_id' => $abobo_commune->id, 'name' => 'Centre']);
        Quartier::create(['commune_id' => $abobo_commune->id, 'name' => 'Gbessia']);
        Quartier::create(['commune_id' => $abobo_commune->id, 'name' => 'Sagbé']);
        Quartier::create(['commune_id' => $abobo_commune->id, 'name' => 'Abobo-Doumé']);
        Quartier::create(['commune_id' => $abobo_commune->id, 'name' => 'N\'Dakakro']);

        // Communes et Quartiers - Yopougon
        $yopougon_commune = Commune::create(['district_id' => $yopougon->id, 'name' => 'Yopougon']);
        Quartier::create(['commune_id' => $yopougon_commune->id, 'name' => 'Centre']);
        Quartier::create(['commune_id' => $yopougon_commune->id, 'name' => 'Andokoi']);
        Quartier::create(['commune_id' => $yopougon_commune->id, 'name' => 'Aéroport']);
        Quartier::create(['commune_id' => $yopougon_commune->id, 'name' => 'Sable Blanc']);
        Quartier::create(['commune_id' => $yopougon_commune->id, 'name' => 'Baysside']);

        // Communes et Quartiers - Koumassi
        $koumassi_commune = Commune::create(['district_id' => $koumassi->id, 'name' => 'Koumassi']);
        Quartier::create(['commune_id' => $koumassi_commune->id, 'name' => 'Centre']);
        Quartier::create(['commune_id' => $koumassi_commune->id, 'name' => 'Frontière']);

        // Communes et Quartiers - Treichville
        $treichville_commune = Commune::create(['district_id' => $treichville->id, 'name' => 'Treichville']);
        Quartier::create(['commune_id' => $treichville_commune->id, 'name' => 'Centre']);
        Quartier::create(['commune_id' => $treichville_commune->id, 'name' => 'Petit Bassam']);

        // Communes et Quartiers - Adjamé
        $adjame_commune = Commune::create(['district_id' => $adjame->id, 'name' => 'Adjamé']);
        Quartier::create(['commune_id' => $adjame_commune->id, 'name' => 'Centre']);
        Quartier::create(['commune_id' => $adjame_commune->id, 'name' => 'Achoualé']);

        // Communes et Quartiers - Marcory
        $marcory_commune = Commune::create(['district_id' => $marcory->id, 'name' => 'Marcory']);
        Quartier::create(['commune_id' => $marcory_commune->id, 'name' => 'Centre']);
        Quartier::create(['commune_id' => $marcory_commune->id, 'name' => 'Faya']);

        // Communes et Quartiers - Songon
        $songon_commune = Commune::create(['district_id' => $songon->id, 'name' => 'Songon']);
        Quartier::create(['commune_id' => $songon_commune->id, 'name' => 'Centre']);
        Quartier::create(['commune_id' => $songon_commune->id, 'name' => 'Saunier']);

        // Autres régions
        $regions = [
            ['name' => 'Cascades', 'code' => 'CAS', 'districts' => [
                'Banfora' => ['Banfora Centre', 'Banfora 2', 'Léraba'],
                'Mangodara' => ['Mangodara Centre', 'Orodara'],
            ]],
            ['name' => 'Vallée du Bandama', 'code' => 'VB', 'districts' => [
                'Yamoussoukro' => ['Centre', 'Belleville', 'Ahotondji'],
                'Kossou' => ['Kossou Centre'],
            ]],
            ['name' => 'Lacs', 'code' => 'LAC', 'districts' => [
                'Dimbokro' => ['Dimbokro Centre', 'Affem'],
                'Gbassoua' => ['Gbassoua Centre'],
            ]],
            ['name' => 'Gôh-Djiboua', 'code' => 'GD', 'districts' => [
                'Gagnoa' => ['Gagnoa Centre', 'Dakpadou'],
                'Oumé' => ['Oumé Centre'],
            ]],
            ['name' => 'Marahoué', 'code' => 'MAR', 'districts' => [
                'Bouaflé' => ['Bouaflé Centre'],
                'Yamoussoukro' => ['Tiébissou'],
            ]],
            ['name' => 'Wooded Savanna', 'code' => 'WS', 'districts' => [
                'Toumodi' => ['Toumodi Centre'],
                'Duchekro' => ['Duchekro Centre'],
            ]],
            ['name' => 'Poro', 'code' => 'POR', 'districts' => [
                'Korhogo' => ['Korhogo Centre', 'Dikodougou'],
                'Sinématiali' => ['Sinématiali Centre'],
            ]],
            ['name' => 'Savanes', 'code' => 'SAV', 'districts' => [
                'Nioro' => ['Nioro Centre', 'Boundiali'],
                'Odienné' => ['Odienné Centre'],
            ]],
            ['name' => 'Denguélé', 'code' => 'DEN', 'districts' => [
                'Man' => ['Man Centre', 'Biankouma'],
                'Danané' => ['Danané Centre'],
            ]],
            ['name' => 'Montagnes', 'code' => 'MON', 'districts' => [
                'Yamoussoukro' => ['Daloa'],
                'Zoukougbeu' => ['Zoukougbeu Centre'],
            ]],
            ['name' => 'Lagunes', 'code' => 'LAG', 'districts' => [
                'Grand-Lahou' => ['Grand-Lahou Centre'],
                'Tiassalé' => ['Tiassalé Centre'],
            ]],
            ['name' => 'Sud-Comoé', 'code' => 'SC', 'districts' => [
                'Aboisso' => ['Aboisso Centre'],
                'Noé' => ['Noé Centre'],
            ]],
            ['name' => 'Bas-Sassandra', 'code' => 'BS', 'districts' => [
                'San-Pédro' => ['San-Pédro Centre', 'Facobly'],
                'Sassandra' => ['Sassandra Centre'],
            ]],
        ];

        foreach ($regions as $regionData) {
            $region = Region::create([
                'name' => $regionData['name'],
                'code' => $regionData['code'],
            ]);

            foreach ($regionData['districts'] as $districtName => $quartiers) {
                $district = District::create([
                    'region_id' => $region->id,
                    'name' => $districtName,
                ]);

                $commune = Commune::create([
                    'district_id' => $district->id,
                    'name' => $districtName,
                ]);

                foreach ($quartiers as $quartierName) {
                    Quartier::create([
                        'commune_id' => $commune->id,
                        'name' => $quartierName,
                    ]);
                }
            }
        }
    }
}
