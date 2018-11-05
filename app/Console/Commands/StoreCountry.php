<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StoreCountry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'store:country';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /*$countries = \DB::connection('all_countires')->select("select * from countries");


        foreach ($countries as $country){
            $my_country = new \App\Models\Country();
            $my_country->name =$country->name;
            $my_country->code =$country->sortname;
            $my_country->save();
        }*/


        /*$states = \DB::connection('all_countires')->select("select * from states");


        foreach ($states as $state){
            $my_state = new \App\Models\State();
            $my_state->state_name =$state->name;
            $my_state->state_code =$state->name;
            $my_state->country_id =$state->country_id;
            $my_state->save();
        }*/
        $states = \DB::connection('all_countires')->select("select * from states");
        //dd($states[1]->name);

        $cities = \DB::connection('all_countires')->select("select * from cities");


        foreach ($cities as $city){
            $my_city = new \App\Models\City();
            $my_city->city_name =$city->name;
            $my_city->state_id =$city->state_id;
            $my_city->state_name =isset($states[$city->state_id])?$states[$city->state_id]->name:"";
            $my_city->save();
        }
    }
}
