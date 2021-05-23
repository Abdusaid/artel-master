
<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use App\Position;
use App\RawParent;
use App\Raw;
use App\Firm;
use App\RawFirm;
use Illuminate\Support\Facades\DB;

class RawsSeeder extends Seeder
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
            'Водонагреватель',
            'Вытяжка',
            'Сушилка',
        ];
        foreach($firms as $firm){
            Firm::create([
                'name'=> $firm,
            ]);
        }

        $allRaws = [
            'ABS' => [
                '1' => [
                    'ABS 707' => ['ABS 50 ERON', 'ABS HI121 OB707'],
                    'ABS Natural 121' => ['ABS HI121 NATURAL'],
                    'ABS TX0510' => ['ABS TX0510 (701264)', 'ABS TX0510 (50287)', 'ABS-TX0510(701261)', 'ABS-TX0510(701263)'],
                    'ABS TR558A' => ['ABS-TR 558A(49185)-RED', 'ABS-TR 558A(8M684)-GRAY', 'ABS-TR 558A(58855)', 'ABS-TR 558A(4А082)', 'ABS TR 558 прозрачний', 'ABS-TR 558A(6B395)-BLUE'],
                    'ABS colored' => ['ABS HF 380 8P836-C (Metalic)', 'ABS HF 380 8P837-A (серый)', 'ABS HF380 58841-B (зелёный)', 'ABS HF380 49978-A (красный)'],
                    'ABS FB-100' => ['ABS FB-100'],
                    'АВS HG 0760 GP/W92151' => ['ABS 121H-X0822', 'АВS HG 0760 GP/W92151', 'АВS HG 0760 GP/G71936', 'ABS FR500']
                ],
                '2' => [
                    'ABS white' => ['ABS HI121 OB707 Окраш', 'ABS 50 C Eron'],
                    'ABS Natural' => ['ABS HI121H NATURAL', 'АВS-750 N SW'],
                    'ABS colored' => ['ABS HF 380 8P836-C (Metalic)', 'ABS HF 380 8P837-A (серый)', 'ABS HF380 58841-B (зелёный)', 'ABS HF380 49978-A (красный)'],
                    'ABS AP-WG-50' => ['ABS краситель (белый) AP-WG 50', 'Kраситель (белый) AP-WG 75'],
                    '' => ['ABS краситель (черный)', 'ABS краситель (белый)', 'ABS краситель (серый)']
                ],
                '3' => [
                    'ABS QU' => ['АВS (75)', 'ABS QU-0191/W91527 (SAMSUNG)'],
                    'ABS RS 670 WHITE' => ['ABS RS670 OB898', 'ABS RS 670 WHITE']
                ],
                '5' => [
                    '' => ['ABS Y1817C1 (бардовый)', 'ABS Z7705C1 (синий)', 'АВS HI121 B-03 BLACK', 'ABS HG0760 (K2637)'],
                ],
                '7'=> [
                    'ABS HF380' => ['LG ABS HF380 0B249', 'LG ABS HF380 0B304'],
                    'ABS TR558' => ['ABS-TR 558A(49185)-RED', 'ABS-TR 558A(Green)']
                ],
                '8' => [
                    'ABS XR40' => ['ABS (Жаропрочный) (белый) XR 404', 'ABS (Жаропрочный) (черный)']
                ]
            ],
            'HIPS' => [
                '1' => [
                    '' => ['HIPS VE 1877 (100F)']
                ],
                '2' => [
                    '' => ['HIPS HI450NPG  natural', 'HIPS-HI450CPG White'],
                    'HIPS краситель' => ['HIPS краситель (зелёный)', 'HIPS краситель (синий)', 'HIPS краситель (красный)']
                ],
                '3' => [
                    '' => ['HIPS 450', 'HIPS  ВМ'],
                    'HIPS 470' => ['HIPS-470', 'HIPS-HI450PG']
                ]
            ],
            'GPPS' => [
                '2' => [
                    '' => ['GPPS GP 150 N K', 'GPPS GP 150 N I', 'GPPS G-116'],
                    'GPPS краситель' => ['GPPS (синий)-B-40820'],
                    'GPPS Samsung' => ['GPPS P G-383M ( SAMSUNG)']
                ],
                '3' => [
                    'GPPS 125' => ['GPPS-GP 125 N']
                ]
            ],
            'POM' => [
                '1' => [
                    'POM СТМ' => ['POM DPO-02W']
                ],
                '2' => [
                    'POM X' => ['POM F20-03 NATURAL', 'POM DPO-02W']
                ]
            ],
            'PP' => [
                '1' => [
                    '' => ['PP J 740', 'PP J350', 'PP-J 360 Nukus', 'PP-548 Eron'],
                    'PP автомат' => ['PP PC2305 NATS', 'PP PC2305 W229', 'PP MT43 HBL', 'PP-K8003 (Автомат)'],
                ],
                '2' => [
                    'PP X' => ['PP-J 360 Nukus', 'PPTV30 (белый)'],
                    'PP Samsung' => ['PP BJ 750 SAMSUNG', 'PP-3204 China (SAMSUNG)']
                ],
                '3' => [
                    '' => ['PP J340'],
                    'PP Аксессуар' => ['PP - Юмшоқ', 'PP - Қаттиқ']
                ]
            ],
            'Краситель' => [
                '' => [
                    'Пигмент W900' => ['Краситель MB KR W9000', 'Краситель  MWPG122']
                ],
                '1' => [
                    '' => ['Краситель (серый)', 'Краситель (серый)-STEDY', 'Краситель (светло-серый)', 'Краситель (зелёный)-STEDY',
                            'Краситель (синий)-STEDY', 'PE 8587/01 PAX-красный-STEDY', 'Краситель (белый) -70 %', 'Краситель (белый)']
                ],
                '2' => [
                    'Пигмент X' => ['Краситель (синий)', 'Краситель (зелёный)', 'Краситель (светло-серый) STEDY', 'Краситель (красный)']
                ],
                '3' => [
                    'HP-WG 50' => ['Краситель HP-WG-50']
                ],
                '5' => [
                    '' => ['Красител (бордовый) (3004)', 'Красител (синий) (5005)', 'Красител (темно серый) (7012)']
                ],
                '6' => [
                    '' => ['PC Краситель (синий)', 'PS краситель (матовый)', 'Краситель (коричневый)']
                ]
            ],
            'PBT' => [
                '4' => [
                    '' => ['Tissan PBT (белый)', 'Tissan PBT (черный)']
                ],
                '6' => [
                    'PBT china' => [' PBT (белый) China', 'PBT WT9207M']
                ]
            ],
            'PA' => [
                '4' => [
                    'PA-66' => ['Tissan PA-66 (белый)', 'Tissan PA-66 (черный)', 'Tissan PA-66  GFR (черный)'],
                    'PA-6' => [' PA-6(черный)', ' PA-6(белый)']
                ]
            ],
            'PS' => [
                '6' => [
                    '' => ['PS lampa']
                ]
            ],
            'EVA' => [
                '3' => [
                    '' => ['EVA'],
                    'EVA master batch' => ['EVA COLOR MASTER BATCH']
                ]
            ],
            'Полиетилен' => [
                '1' => [
                    'Полиэтилен СТМ' => ['Полэтилен -местный']
                ],
                '3' => [
                    'Полиетилен F0320' => ['Полэтилен Cleaning compound']
                ],
                '9' => [
                    'Полиэтилен 1561' => ['Полиэтилен 1561']
                ]
            ],
            'PVC' => [
                '3' => [
                    '' => ['Пластикат FT-20 (белый)', 'Пластикат FT-20 (серый)', 'Пластикат FT-25 (серый)', 'Пластикат FT-26 (серый)'],
                    'PVC DM' => ['Пластикат FT-60 (серый)', 'Пластикат FT-65 (серый)', 'PVX (серый)']
                ]
            ],
            'Термоэластамер' => [
                '1' => [
                    '' => ['Термоэластамер (AP8175W-AB) China']
                ]
            ],
            'Cleaning compound' => [
                '' => [
                    '' => ['AsaClean T7711']
                ]
            ],
            'Master batch' => [
                '1' => [
                    '' => ['MASTER BATCH FOR ABS Plastic (0592 WHITE)'],
                    'TR-100 S3GT-G001' => ['TR-100 S3GT-G001', 'CMB-IF 9A280']
                ]
            ],
            'AsaClean T7711' => [
                '2' => [
                    '' => ['AsaClean T7711']
                ]
            ],
            'Наполнитель' => [
                '1' => [
                    '' => ['Наполнитель']
                ]
            ]
        ];
        RawParent::truncate();
        Raw::truncate();
        DB::table('firm_raw_parent')->truncate();
        RawFirm::truncate();

        //looping the root parent
        foreach($allRaws as $key=>$parents){
            $faker = Factory::create();
            $raw_parent = RawParent::create([
                'name' => $key,
            ]);
            $raw_parent->update([
                'ancestors'=>$raw_parent->id.",",
            ]);

            //looping the firm ids
            foreach($parents as $k=>$firms){
                $firm_id = $k;

                //looping the middle parents
                foreach($firms as $ky => $children){
                    if($ky != ''){
                        try{
                            $raw_children = RawParent::create([
                                'name' => $ky,
                                'parent_id'=>$raw_parent->id,
                            ]);
                            $raw_children->update([
                                'ancestors'=>$raw_parent->ancestors.$raw_children->id.",",
                            ]);
                        }
                        catch(Illuminate\Database\QueryException $e){
                            $error_code = $e->errorInfo[1];
                            if($error_code == 1062){
                                $raw_children = RawParent::where('name', $ky)->first();
                            }
                        }
                    }
                    else
                        $raw_children = $raw_parent;

                    //looping the leaf raws
                    foreach($children as $leaf){
                        try{
                            $raw = $raw_children->raws()->create([
                                'name'=>$leaf,
                                'quantity'=>0
                             ]);
                            // if(!$raw){
                            //     throw new \Exception;
                            // }
                        }
                        catch(Illuminate\Database\QueryException $e){
                            $error_code = $e->errorInfo[1];
                            if($error_code == 1062){
                                $raw = Raw::where('name', $leaf)->first();
                            }
                        }

                        $position_id = $faker->numberBetween(1, 432);
                        //creating rawFirm for three types
                        if($firm_id != ''){
                            foreach(range(0, 2) as $type){
                                $raw->rawFirms()
                                ->create([
                                    'firm_id'=>$firm_id,
                                    'position_id'=>$position_id,
                                    'quantity'=>0,
                                    'type'=>$type,
                                ]);

                            }
                            //if($type==2){dd($raw->rawFirms);}
                        }
                        else{
                            foreach(range(1, 9) as $i){
                                foreach(range(0, 2) as $type){
                                    $raw->rawFirms()
                                    ->create([
                                        'firm_id'=>$i,
                                        'position_id'=>$position_id,
                                        'quantity'=>0,
                                        'type'=>$type,
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
