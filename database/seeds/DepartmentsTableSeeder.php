<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Faker\Factory as Faker;
use Carbon\Carbon;
use App\Department;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departments')->delete();
        $faker = Faker::create('en_US');

        Department::create([
            'department_name' => 'システム開発部'
        ]);

        Department::create([
            'department_name' => '人事課'
        ]);

        Department::create([
            'department_name' => '総務部'
        ]);

    }
}
