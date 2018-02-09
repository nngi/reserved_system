<?php

use Illuminate\Database\Seeder;

use App\Agreement;

class AgreementsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('agreements')->delete();
        Agreement::create();
    }
}
