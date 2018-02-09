<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Faker\Factory as Faker;
use Carbon\Carbon;
use App\FacilityType;

class FacilityTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('facility_types')->delete();
        $faker = Faker::create('en_US');

        FacilityType::create([
            'facility_type_name' => '会議室'
        ]);

        FacilityType::create([
            'facility_type_name' => 'プロジェクター'
        ]);
    }
}
