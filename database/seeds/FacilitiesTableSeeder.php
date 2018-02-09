<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Faker\Factory as Faker;
use Carbon\Carbon;
use App\Facility;
use App\FacilityType;

class FacilitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('facilities')->delete();
        $faker = Faker::create('en_US');

        $type1 = FacilityType::where('facility_type_name', '会議室')->first();
        $type2 = FacilityType::where('facility_type_name', 'プロジェクター')->first();

        Facility::create([
            'facility_name' => '会議室A',
            'facility_type_id' => $type1->facility_type_id
        ]);

        Facility::create([
            'facility_name' => 'プロジェクターA',
            'facility_type_id' => $type2->facility_type_id
        ]);
    }
}
