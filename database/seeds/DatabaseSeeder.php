<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call('FacilityTypesTableSeeder');
        $this->call('FacilitiesTableSeeder');
        $this->call('DepartmentsTableSeeder');
        $this->call('AgreementsTableSeeder');
        $this->call('CompaniesTableSeeder');
        Model::reguard();
    }
}
