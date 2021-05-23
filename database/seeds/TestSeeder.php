<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use App\Position;
use App\RawParent;
use App\Raw;
use App\Firm;
use App\Import;
use App\RawFirm;
use App\Fifo;
use Illuminate\Support\Facades\DB;


class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        Position::truncate();
        $sections = ['A', 'B', 'C', 'D'];
        foreach($sections as $section){
            foreach(range(1,4) as $raw){
                foreach(range(1,27) as $column){
                    Position::create([
                        'section'=>$section,
                        'raw'=>$raw,
                        'column'=>$column,
                    ]);
                }
            }
        }

        Firm::truncate();
        $firms = [
            'Стиралка',
            'Холодильник',
            'Экструдер',
            'Газ плита',
            'Пылeсос',
            'Лампа',
            'Шлак',
        ];
        foreach($firms as $firm){
            Firm::create([
                'name'=> $firm,
            ]);
        }

        RawParent::truncate();
        Raw::truncate();
        Import::truncate();

        $parents = [
            'PP'=>[
                'PP J' =>['PP J 360', 'PP J 740', 'PP J 350', 'PP J 248'],
                'PP 548'=>['PP 548 Eron'],
            ],
            'ABS'=>[
                'ABS краситель'=>['ABS краситель (черный)','ABS краситель (белый)'],
                'ABS H1919' =>['ABS H1919 red','ABS H1919 white'],
            ],
            'HIPS'=>[
                'HIPS 122 черны'=> ['HIPS 122 белый','HIPS 122 черный'],
            ],
            'Краситель'=>[
                'Краситель HP' => ['Краситель HP  новый']
            ],
        ];
        RawFirm::truncate();
        Fifo::truncate();
        DB::table('firm_raw_parent')->truncate();
        foreach($parents as $key=>$parent){
            $faker = Factory::create();
            $raw_parent = RawParent::create([
                'name' => $key,
            ]);
            $raw_parent->update([
                'ancestors'=>$raw_parent->id,
            ]);
            foreach($parent as $ky=> $value){
                $raw_children = RawParent::create([
                    'name' => $ky,
                    'parent_id'=>$raw_parent->id,

                ]);
                $raw_children->update([
                    'ancestors'=>$raw_parent->ancestors.", ".$raw_children->id,
                ]);

                foreach($value as $val){
                    $raw = $raw_children->raws()->create([
                        'name'=>$val,
                        'quantity'=>0
                     ]);

                    $firm_id = $faker->numberBetween(1,7);
                    $position_id = $faker->numberBetween(1, 432);

                    $rawFirm0 = $raw->rawFirms()
                    ->create([
                            'firm_id'=>$firm_id,
                            'position_id'=>$position_id,
                            'quantity'=>0,
                            'type'=>0,

                    ]);
                    $rawFirm1 = $raw->rawFirms()
                        ->create([
                            'firm_id'=>$firm_id,
                            'position_id'=>$position_id,
                            'quantity'=>0,
                            'type'=>1,

                        ]);
                    $rawFirm2 = $raw->rawFirms()
                        ->create([
                            'firm_id'=>$firm_id,
                            'position_id'=>$position_id,
                            'quantity'=>0,
                            'type'=>2,
                        ]);


                    foreach(range(1, 5) as $value){

                        $import= $raw->imports()->create([
                           'raw_firm_id'=>$rawFirm0->id,
                           'quantity'=>$faker->numberBetween(0,10000),
                           'new'=>$faker->numberBetween(0,1),
                           'serial_number' => str_random(1).'-'.$faker->unique()->numberBetween(1000, 9999),
                           'supplier'=>$faker->name,
                           'status'=>$faker->numberBetween(0,3),
                        ]);
                        $rawFirm0->update(['quantity'=>$rawFirm0->quantity + $import->quantity]);
                        if(!($import->status!=1 && $rawFirm0->type == 1))
                            $rawFirm0->update(['valid_quantity'=>$rawFirm0->valid_quantity+$import->quantity]);
                        $fifo = $import->fifo()->updateOrCreate([
                            'quantity'=>$import->quantity
                        ]);

                        $import= $raw->imports()->create([
                            'raw_firm_id'=>$rawFirm1->id,
                            'quantity'=>$faker->numberBetween(0,10000),
                            'new'=>$faker->numberBetween(0,1),
                            'serial_number' => str_random(1).'-'.$faker->unique()->numberBetween(1000, 9999),
                            'supplier'=>$faker->name,
                            'status'=>$faker->numberBetween(0,3),
                        ]);
                        $rawFirm1->update(['quantity'=>$rawFirm1->quantity + $import->quantity]);
                        if(!($import->status!=1 && $rawFirm1->type == 1))
                            $rawFirm1->update(['valid_quantity'=>$rawFirm1->valid_quantity+$import->quantity]);
                        $fifo = $import->fifo()->updateOrCreate([
                            'quantity'=>$import->quantity
                        ]);


                        $import= $raw->imports()->create([
                            'raw_firm_id'=>$rawFirm2->id,
                            'quantity'=>$faker->numberBetween(0,10000),
                            'new'=>$faker->numberBetween(0,1),
                            'serial_number' => str_random(1).'-'.$faker->unique()->numberBetween(1000, 9999),
                            'supplier'=>$faker->name,
                            'status'=>$faker->numberBetween(0,3),
                        ]);
                        $rawFirm2->update(['quantity'=>$rawFirm2->quantity + $import->quantity]);
                        if(!($import->status!=1 && $rawFirm2->type == 1))
                            $rawFirm2->update(['valid_quantity'=>$rawFirm2->valid_quantity+$import->quantity]);
                        $fifo = $import->fifo()->updateOrCreate([
                            'quantity'=>$import->quantity
                        ]);


                        // $export = $raw->exports()->create([
                        //     'raw_firm_id'=>$rawFirm->id,
                        //     'quantity'=>$faker->numberBetween(0,$import->quantity),
                        //     'type'=>$faker->numberBetween(0,2),
                        //     'to'=>$faker->numberBetween(0,3),
                        //     'status'=>$faker->numberBetween(0,1),
                        // ]);
                        // $rawFirm->update(['quantity'=>$export->quantity]);

                    }
                }
            }
            foreach(range(1,4) as $value){
                $raw_parent->firms()->attach($faker->unique()->numberBetween(1,7));
            }
        }
    }

}
