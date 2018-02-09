<?php

use Illuminate\Database\Seeder;

use App\Company;
use App\Agreement;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->delete();

        $agrmnt = Agreement::all()->first();

        Company::create([
            'agreement_id' => $agrmnt->agreement_id,
            'company_name' => '企業1',
            'zip_code' => 'xxx-xxxx',
            'prefecture_code' => '東京都',
            'city' => 'XX区',
            'street_address' => 'XXXX',
            'tel' => 'xxx-xxxx-xxxx'
        ]);
    }
}
