
<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use App\Position;
use App\RawParent;
use App\Raw;
use App\Firm;
use App\RawFirm;
use Illuminate\Support\Facades\DB;

class RawsSeeder2 extends Seeder
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
        $company_names = [
            'Allegro',
            'Magic Plastics',
            'Techno Continental',
            'Magic Plastics',
            'Allegro',
            'Magic Plastics',
            'Techno Continental',
            'Techno Continental',
            'Allegro',
        ];
        foreach($firms as $index => $firm){
            Firm::create([
                'name'=> $firm,
                'company_name' => $company_names[$index],
            ]);
        }
        $colors = [
            '',
            'натурал',
            'прозрачный',
            'коричневый',
            'белый',
            'серый',
            'красный',
            'зеленый',
            'синый',
            'черный',
            'светлосерый',
            'темносиный',
            'темносерый',
            'матовый',
            'металик',
            'бордовый',
        ];
        DB::table('colors')->truncate();
        foreach($colors as $color){
            DB::table('colors')->insert([
                'name' => $color,
            ]);
        }

        $allRaws = [
            '1' => [
                'ABS' => [
                    '1' => [
                        'ABS 707' => ['ABS 50 ERON', 'ABS HI121 OB707'],
                        'ABS TX0510' => ['ABS TX0510 (701264)', 'ABS TX0510 (50287)', 'ABS-TX0510(701261)', 'ABS-TX0510(701263)'],
                        'ABS TR558A' => ['ABS-TR 558A(49185)', 'ABS-TR 558A(8M684)', 'ABS-TR 558A(58855)', 'ABS-TR 558A(4А082)', 'ABS TR 558', 'ABS-TR 558A(6B395)'],
                        'ABS colored' => ['ABS HF 380 8P836-C', 'ABS HF 380 8P837-A', 'ABS HF380 58841-B', 'ABS HF380 49978-A'],
                        'ABS FB-100' => ['ABS FB-100'],
                        'АВS HG 0760 GP/W92151' => ['ABS 121H-X0822', 'АВS HG 0760 GP/W92151', 'АВS HG 0760 GP/G71936', 'ABS FR500'],
                        'colorful' => ['ABS TX0510', 'ABS TR558A', 'ABS HI121']
                    ],
                    '2' => [
                        'ABS white' => ['ABS HI121 OB707 Окраш', 'ABS 50 C Eron', 'ABS white'],
                        'ABS 121' => ['ABS HI121H', 'АВS-750 N SW'],
                        'ABS colored' => ['ABS HF 380 8P836-C', 'ABS HF 380 8P837-A', 'ABS HF380 58841-B', 'ABS HF380 49978-A'],
                        'ABS AP-WG-50' => ['ABS краситель AP-WG 50','Kраситель AP-WG 75'],
                    ],
                    '3' => [
                        'ABS QU' => ['АВS (75)', 'ABS QU-0191/W91527 (SAMSUNG)', 'ABS QU-0191/W91528 (SAMSUNG)'],
                        'ABS RS 670 WHITE' => ['ABS RS670 OB898', 'ABS RS 670']
                    ],
                    '5' => [
                        '' => ['ABS Y1817C1', 'ABS Z7705C1', 'АВS HI121 B-03', 'ABS HG0760 (K2637)'],
                    ],
                    '7'=> [
                        'ABS HF380' => ['LG ABS HF380 0B249', 'LG ABS HF380 0B304'],
                        'ABS TR558' => ['ABS-TR 558A(49185)', 'ABS-TR 558A']
                    ],
                    '8' => [
                        'ABS XR40' => ['ABS (Жаропрочный) XR 404', 'ABS (Жаропрочный)']
                    ]
                ],
                'HIPS' => [
                    '1' => [
                        '' => ['HIPS VE 1877 (100F)']
                    ],
                    '2' => [
                        '' => ['HIPS HI450NPG', 'HIPS-HI450CPG'],
                    ],
                    '3' => [
                        '' => ['HIPS 450', 'HIPS  ВМ'],
                        'HIPS 470' => ['HIPS-470', 'HIPS-HI450PG']
                    ]
                ],
                'GPPS' => [
                    '2' => [
                        '' => ['GPPS GP 150 N K', 'GPPS GP 150 N I', 'GPPS G-116'],
                        'GPPS краситель' => ['GPPS B-40820'],
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
                        'POM X' => ['POM F20-03', 'POM DPO-02W']
                    ]
                ],
                'PP' => [
                    '1' => [
                        '' => ['PP J 740', 'PP J350', 'PP-J 360 Nukus', 'PP-548 Eron'],
                        'PP автомат' => ['PP PC2305 NATS', 'PP PC2305 W229', 'PP MT43 HBL', 'PP-K8003 (Автомат)'],
                        'colorful' => ['PP']
                    ],
                    '2' => [
                        'PP X' => ['PP-J 360 Nukus', 'PPTV30'],
                        'PP Samsung' => ['PP BJ 750 SAMSUNG', 'PP-3204 China (SAMSUNG)']
                    ],
                    '3' => [
                        '' => ['PP J340'],
                        'PP Аксессуар' => ['PP - Юмшоқ', 'PP - Қаттиқ']
                    ]
                ],
                'Краситель' => [
                    '' => [
                        'Пигмент W900' => ['Краситель MB KR W9000', 'Краситель  MWPG122'],
                        'Краситель' => ['Краситель', 'Краситель-STEDY', 'ABS краситель', 'HIPS краситель']
                    ],
                    '1' => [
                        '' => ['PE 8587/01 PAX-STEDY', 'Краситель -70 %']
                    ],
                    '3' => [
                        'HP-WG 50' => ['Краситель HP-WG-50']
                    ],
                    '5' => [
                        '' => ['Красител (3004)', 'Красител (5005)', 'Красител (7012)']
                    ],
                    '6' => [
                        '' => ['PC Краситель', 'PS краситель']
                    ]
                ],
                'PBT' => [
                    '4' => [
                        '' => ['Tissan PBT', 'Tisan PBT']
                    ],
                    '6' => [
                        'PBT china' => ['PBT China', 'PBT WT9207M']
                    ]
                ],
                'PA' => [
                    '4' => [
                        'PA-66' => ['Tissan PA-66', 'Tissan PA-66 GFR'],
                        'PA-6' => [' PA-6', 'PA-6']
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
                        '' => ['Пластикат FT-20', 'пластикат FT-20', 'Пластикат FT-25', 'Пластикат FT-26'],
                        'PVC DM' => ['Пластикат FT-60', 'Пластикат FT-65', 'PVX']
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
                        '' => ['MASTER BATCH FOR ABS Plastic (0592)'],
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
            ],
            '2' => [
                'Краситель' => [
                    '' => [
                        'Краситель' => ['Краситель', 'Краситель-STEDY', 'ABS краситель', 'HIPS краситель']
                    ],
                ],
                '1' => [
                    '1' => [
                        'АВS HG 0760 GP/W92151' => ['АВS HG 0760 GP/W92151', 'ABS FR500', 'ABS 121H-X0822', 'PP PC2305 NATS'],
                        'colorful' => ['ABS HI121', 'ABS TX0510', 'ABS TR558A', 'PP']
                    ]
                ],
                '2' => [
                    '2' => [
                        'colorful' => ['PP'],
                        'POM' => ['POM DPO-02W', 'LG ABS HF380 0B249', 'Термоэластамер (AP8175W-AB) China', 'Полэтилен -местный']
                    ]
                ],
                '3' => [
                    '3' => [
                        'POM' => ['HIPS-470', 'PP J340', 'Пластикат FT-20', 'пластикат FT-20', 'EVA', 'Полэтилен -местный', 'ABS QU-0191/W91527 (SAMSUNG)', 'ABS RS 670', 'АВS (75)']
                    ]
                ],
                '4' => [
                    '4' => [
                        'POM' => ['Tissan PA-66', 'Tissan PA-66 GFR', 'Tissan PBT', 'Tisan PBT']
                    ]
                ],
                '5' => [
                    '5' => [
                        'POM' => ['ABS HG0760 (K2637)']
                    ]
                ],
                '6' => [
                    '6' => [
                        'POM' => ['PBT China', 'PBT WT9207M', 'PS lampa']
                    ]
                ]
            ],
            '0' => [
                'Краситель' => [
                    '' => [
                        'Краситель' => ['Краситель', 'Краситель-STEDY', 'ABS краситель', 'HIPS краситель']
                    ],
                ],
                '7' => [
                    '1' => [
                        'colorful' => ['ABS TR558A', 'PP'],
                        'ABS TX0510' => ['ABS-TX0510(701261)', 'ABS-TX0510(701263)']
                    ]
                ],
                '8' => [
                    '2' => [
                        'POM' => ['LG ABS HF380 0B249'],
                        'colorful' => ['PP']
                    ]
                ],
                '9' => [
                    '3' => [
                        'POM' => ['АВS (75)', 'ABS QU-0191/W91527 (SAMSUNG)', 'PP J340', 'HIPS-470']
                    ]
                ],
                '10' => [
                    '4' => [
                        'POM' => ['Tissan PA-66', 'Tissan PA-66 GFR', 'Tissan PBT', 'Tisan PBT']
                    ]
                ],
                '11' => [
                    '5' => [
                        'POM' => ['ABS Y1817C1', 'ABS Z7705C1']
                    ]
                ],
            ]
        ];

        $raw_colors = [
            '2' => ['ABS HI121', 'АВS-750 N SW', 'HIPS HI450NPG', 'POM F20-03'],
            '3' => ['ABS TR 558'],
            '5' => ['ABS white', 'ABS краситель AP-WG 50', 'Kраситель AP-WG 75', 'ABS RS 670', 'ABS RS670 OB898', 'ABS (Жаропрочный) XR 404', 'HIPS-HI450CPG', 'PPTV30', 'Краситель -70 %', 'Tisan PBT', 'PBT China', 'Tissan PA-66', 'PA-6', 'пластикат FT-20', 'MASTER BATCH FOR ABS Plastic (0592)'],
            '6' => ['ABS-TR 558A(8M684)', 'ABS HF 380 8P837-A', 'ABS HF 380 8P837-A', 'Пластикат FT-20', 'Пластикат FT-25', 'Пластикат FT-26', 'Пластикат FT-60', 'Пластикат FT-65', 'PVX', 'ABS QU-0191/W91528 (SAMSUNG)'],
            '7' => ['ABS-TR 558A(49185)', 'ABS HF380 49978-A', 'ABS HF380 49978-A', 'PE 8587/01 PAX-STEDY', 'ABS-TR 558A(49185)'],
            '8' => ['ABS HF380 58841-B', 'ABS HF380 58841-B', 'ABS-TR 558A'],
            '9' => ['ABS-TR 558A(6B395)', 'ABS Z7705C1', 'GPPS B-40820', 'Красител (5005)', 'PC Краситель'],
            '10' => ['АВS HI121 B-03', 'ABS (Жаропрочный)', 'Tissan PBT', 'Tissan PA-66', 'Tissan PA-66 GFR', ' PA-6'],
            '11' => [],
            '12' => [],
            '13' => ['Красител (7012)'],
            '14' => ['PS краситель'],
            '15' => ['ABS HF 380 8P836-C', 'ABS HF 380 8P836-C'],
            '16' => ['ABS Y1817C1', 'Красител (3004)']
        ];

        RawParent::truncate();
        Raw::truncate();
        DB::table('firm_raw_parent')->truncate();
        RawFirm::truncate();

        //looping the root parent
        foreach($allRaws as $t=>$type){
            foreach($type as $key=>$parents){
                $faker = Factory::create();
                if($t=='1'){
                    $raw_parent = RawParent::create([
                        'name' => $key,
                    ]);
                    $raw_parent->update([
                        'ancestors'=>$raw_parent->id.",",
                    ]);
                }

                //looping the firm ids
                foreach($parents as $k=>$firms){
                    $firm_id = $k;

                    //looping the middle parents
                    foreach($firms as $ky => $children){
                        if($t=='1'){
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
                        }  
                        //looping the leaf raws
                        foreach($children as $leaf){
                            try{
                                if($t=='1'){
                                    
                                    if($ky=='Краситель'||$ky=='colorful'){
                                        foreach($colors as $c=>$color){
                                            $raw = $raw_children->raws()->create([
                                                'name'=>$leaf,
                                                'quantity'=>0,
                                                'color_id'=>$c+1 
                                                ]);
                                        }
                                    }
                                    else{
                                        $raw = $raw_children->raws()->create([
                                            'name'=>$leaf,
                                            'quantity'=>0,
                                            'color_id'=>1 
                                            ]);
                                    }
                                }
                                else{
                                    echo $leaf;
                                    $raw = Raw::where('name', $leaf)->first();
                                }
                            }
                            catch(Illuminate\Database\QueryException $e){
                                $error_code = $e->errorInfo[1];
                                if($error_code == 1062){
                                    $raw = Raw::where('name', $leaf)->first();
                                }
                            }

                            $position_id = $faker->numberBetween(1, 432);
                            //creating rawFirm for three types
                            if($ky=='Краситель'||$ky=='colorful'){
                                $raw = Raw::where('name', $leaf)->get();
                            }

                            if($firm_id != ''){
                                if($ky=='Краситель'||$ky=='colorful'){
                                    foreach($raw as $ra){
                                        $ra->rawFirms()
                                        ->create([
                                            'firm_id'=>$firm_id,
                                            'position_id'=>$position_id,
                                            'quantity'=>0,
                                            'type'=>$t,
                                        ]);
                                    }
                                }
                                else{
                                    $raw->rawFirms()
                                        ->create([
                                            'firm_id'=>$firm_id,
                                            'position_id'=>$position_id,
                                            'quantity'=>0,
                                            'type'=>$t,
                                        ]);
                                }
                                //if($type==2){dd($raw->rawFirms);}
                            }
                            else{
                                foreach(range(1, 9) as $i){
                                    if($ky=='Краситель'||$ky=='colorful'){
                                        foreach($raw as $ra){
                                            $ra->rawFirms()
                                            ->create([
                                                'firm_id'=>$i,
                                                'position_id'=>$position_id,
                                                'quantity'=>0,
                                                'type'=>$t,
                                            ]);
                                        }
                                    }
                                    else{
                                        $raw->rawFirms()
                                            ->create([
                                                'firm_id'=>$i,
                                                'position_id'=>$position_id,
                                                'quantity'=>0,
                                                'type'=>$t,
                                            ]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        foreach($raw_colors as $key=>$color){
            foreach($color as $raw){
                $raw = Raw::where('name', $raw)->first();
                $raw->update([
                    'color_id' => $key
                ]);
            }
        }
    }
}
