<?php

namespace App\Http\Controllers;

use App\Export;
use App\Import;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Excel;

class ExcelController extends Controller
{
    public function imports(Request $request)
    {
        $today = new Carbon($request->date);
        $today = $today->format('d.m.y');
        $imports = Import::with('rawFirm')->whereDate('created_at', new Carbon($request->date))->whereHas('rawFirm', function($query){
            $query->with('firm')->whereHas('firm', function($query){
                $query->where('company_name', 'Allegro');
            });
        })->select('*', DB::raw('sum(quantity) as quantity'))->groupBy('raw_firm_id')->get();
        Excel::load(public_path('storage/allegro_imports_template.xlsx'), function ($excel) use ($today, $imports) {
            $excel->sheet(0, function ($sheet) use ($today, $imports) {
                $sheet->cell('C9', function ($cell) use ($today) {
                    $cell->setValue('Дата         ' . $today . ' г.');
                });
                $sheet->cell('H9', function ($cell) use ($today) {
                    $cell->setValue('Дата         ' . $today . ' г.');
                });
                $counter = 0;
                $sum = 0;
                foreach ($imports as $import) {
                    $sheet->appendRow(14 + $counter, array($counter + 1, $import->raw->name, 'кг.', $import->quantity, '', $counter + 1, $import->raw->name, 'кг.', $import->quantity));
                    $counter++;
                    $sum += $import->quantity;
                }

                $sheet->appendRow(14 + $counter, array('ИТОГО', '', '', $sum, '', 'ИТОГО', '', '', $sum));
                $sheet->appendRow(15 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(16 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(17 + $counter, array('', 'Отправитель Нач. Участка :         Хамидов А.', '          _______________________', '', '', '', 'Отправитель Нач. Участка :         Хамидов А.', '          _______________________', ''));
                $sheet->appendRow(18 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(19 + $counter, array('', 'Получатель Зав. Склад :               Шарипов Т.', '          _______________________', '', '', '', 'Получатель Зав. Склад :                Шарипов Т.', '          _______________________', ''));
                $sheet->appendRow(20 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(21 + $counter, array('', 'Проверил Кладовщик:                 Файзиев Ш.', '          _______________________', '', '', '', 'Проверил Кладовщик:                  Файзиев Ш.', '          _______________________', ''));
                $sheet->appendRow(22 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(23 + $counter, array('', 'Материальный Бух:', '          _______________________', '', '', '', 'Материальный Бух:', '          _______________________', ''));

                $sheet->mergeCells('A' . (14 + $counter) . ':C' . (14 + $counter));
                $sheet->mergeCells('F' . (14 + $counter) . ':H' . (14 + $counter));

                for ($i = 17 + $counter; $i <= 23 + $counter; $i = $i + 2) {
                    $sheet->mergeCells('C' . ($i) . ':D' . ($i));
                    $sheet->mergeCells('H' . ($i) . ':I' . ($i));
                }
                $sheet->cells('A14:I' . (14 + $counter), function ($cells) {
                    $cells->setFontSize(14);
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                });
                $sheet->cells('A' . (14 + $counter) . ':I' . (14 + $counter), function ($cells) {
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('A' . (17 + $counter) . ':I' . (23 + $counter), function ($cells) {
                    $cells->setFontSize(16);
                    $cells->setValignment('center');
                });
                $sheet->setBorder('A14:D' . (14 + $counter), 'thin');
                $sheet->setBorder('F14:I' . (14 + $counter), 'thin');
                $sheet->setWidth('A', 5);
                $height = array();
                for ($i = 14; $i < 23 + $counter; $i++) {
                    if ($i <= $i + $counter)
                        $height[$i] = 20;
                    else
                        $height[$i] = 16;

                }
                $sheet->setHeight($height);
            });

        })->save('xlsx', storage_path('excel/exports'));
        $imports = Import::with('rawFirm')->whereDate('created_at', new Carbon($request->date))->whereHas('rawFirm', function($query){
            $query->with('firm')->whereHas('firm', function($query){
                $query->where('company_name', 'Techno Continental');
            });
        })->select('*', DB::raw('sum(quantity) as quantity'))->groupBy('raw_firm_id')->get();
        Excel::load(public_path('storage/techno_imports_template.xlsx'), function ($excel) use ($today, $imports) {
            $excel->sheet(0, function ($sheet) use ($today, $imports) {
                $sheet->cell('C9', function ($cell) use ($today) {
                    $cell->setValue('Дата         ' . $today . ' г.');
                });
                $sheet->cell('H9', function ($cell) use ($today) {
                    $cell->setValue('Дата         ' . $today . ' г.');
                });
                $counter = 0;
                $sum = 0;
                foreach ($imports as $import) {
                    $sheet->appendRow(14 + $counter, array($counter + 1, $import->raw->name, 'кг.', $import->quantity, '', $counter + 1, $import->raw->name, 'кг.', $import->quantity));
                    $counter++;
                    $sum += $import->quantity;
                }

                $sheet->appendRow(14 + $counter, array('ИТОГО', '', '', $sum, '', 'ИТОГО', '', '', $sum));
                $sheet->appendRow(15 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(16 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(17 + $counter, array('', 'Отправитель Нач. Участка :         Хамидов А.', '          _______________________', '', '', '', 'Отправитель Нач. Участка :         Хамидов А.', '          _______________________', ''));
                $sheet->appendRow(18 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(19 + $counter, array('', 'Получатель Зав. Склад :               Шарипов Т.', '          _______________________', '', '', '', 'Получатель Зав. Склад :                Шарипов Т.', '          _______________________', ''));
                $sheet->appendRow(20 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(21 + $counter, array('', 'Проверил Кладовщик:                 Файзиев Ш.', '          _______________________', '', '', '', 'Проверил Кладовщик:                  Файзиев Ш.', '          _______________________', ''));
                $sheet->appendRow(22 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(23 + $counter, array('', 'Материальный Бух:', '          _______________________', '', '', '', 'Материальный Бух:', '          _______________________', ''));

                $sheet->mergeCells('A' . (14 + $counter) . ':C' . (14 + $counter));
                $sheet->mergeCells('F' . (14 + $counter) . ':H' . (14 + $counter));

                for ($i = 17 + $counter; $i <= 23 + $counter; $i = $i + 2) {
                    $sheet->mergeCells('C' . ($i) . ':D' . ($i));
                    $sheet->mergeCells('H' . ($i) . ':I' . ($i));
                }
                $sheet->cells('A14:I' . (14 + $counter), function ($cells) {
                    $cells->setFontSize(14);
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                });
                $sheet->cells('A' . (14 + $counter) . ':I' . (14 + $counter), function ($cells) {
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('A' . (17 + $counter) . ':I' . (23 + $counter), function ($cells) {
                    $cells->setFontSize(16);
                    $cells->setValignment('center');
                });
                $sheet->setBorder('A14:D' . (14 + $counter), 'thin');
                $sheet->setBorder('F14:I' . (14 + $counter), 'thin');
                $sheet->setWidth('A', 5);
                $height = array();
                for ($i = 14; $i < 23 + $counter; $i++) {
                    if ($i <= $i + $counter)
                        $height[$i] = 20;
                    else
                        $height[$i] = 16;

                }
                $sheet->setHeight($height);
            });

        })->save('xlsx', storage_path('excel/exports'));
        $imports = Import::with('rawFirm')->whereDate('created_at', new Carbon($request->date))->whereHas('rawFirm', function($query){
            $query->with('firm')->whereHas('firm', function($query){
                $query->where('company_name', 'Magic Plastics');
            });
        })->select('*', DB::raw('sum(quantity) as quantity'))->groupBy('raw_firm_id')->get();
        Excel::load(public_path('storage/magic_imports_template.xlsx'), function ($excel) use ($today, $imports) {
            $excel->sheet(0, function ($sheet) use ($today, $imports) {
                $sheet->cell('C9', function ($cell) use ($today) {
                    $cell->setValue('Дата         ' . $today . ' г.');
                });
                $sheet->cell('H9', function ($cell) use ($today) {
                    $cell->setValue('Дата         ' . $today . ' г.');
                });
                $counter = 0;
                $sum = 0;
                foreach ($imports as $import) {
                    $sheet->appendRow(14 + $counter, array($counter + 1, $import->raw->name, 'кг.', $import->quantity, '', $counter + 1, $import->raw->name, 'кг.', $import->quantity));
                    $counter++;
                    $sum += $import->quantity;
                }

                $sheet->appendRow(14 + $counter, array('ИТОГО', '', '', $sum, '', 'ИТОГО', '', '', $sum));
                $sheet->appendRow(15 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(16 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(17 + $counter, array('', 'Отправитель Нач. Участка :         Хамидов А.', '          _______________________', '', '', '', 'Отправитель Нач. Участка :         Хамидов А.', '          _______________________', ''));
                $sheet->appendRow(18 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(19 + $counter, array('', 'Получатель Зав. Склад :               Шарипов Т.', '          _______________________', '', '', '', 'Получатель Зав. Склад :                Шарипов Т.', '          _______________________', ''));
                $sheet->appendRow(20 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(21 + $counter, array('', 'Проверил Кладовщик:                 Файзиев Ш.', '          _______________________', '', '', '', 'Проверил Кладовщик:                  Файзиев Ш.', '          _______________________', ''));
                $sheet->appendRow(22 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(23 + $counter, array('', 'Материальный Бух:', '          _______________________', '', '', '', 'Материальный Бух:', '          _______________________', ''));

                $sheet->mergeCells('A' . (14 + $counter) . ':C' . (14 + $counter));
                $sheet->mergeCells('F' . (14 + $counter) . ':H' . (14 + $counter));

                for ($i = 17 + $counter; $i <= 23 + $counter; $i = $i + 2) {
                    $sheet->mergeCells('C' . ($i) . ':D' . ($i));
                    $sheet->mergeCells('H' . ($i) . ':I' . ($i));
                }
                $sheet->cells('A14:I' . (14 + $counter), function ($cells) {
                    $cells->setFontSize(14);
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                });
                $sheet->cells('A' . (14 + $counter) . ':I' . (14 + $counter), function ($cells) {
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('A' . (17 + $counter) . ':I' . (23 + $counter), function ($cells) {
                    $cells->setFontSize(16);
                    $cells->setValignment('center');
                });
                $sheet->setBorder('A14:D' . (14 + $counter), 'thin');
                $sheet->setBorder('F14:I' . (14 + $counter), 'thin');
                $sheet->setWidth('A', 5);
                $height = array();
                for ($i = 14; $i < 23 + $counter; $i++) {
                    if ($i <= $i + $counter)
                        $height[$i] = 20;
                    else
                        $height[$i] = 16;

                }
                $sheet->setHeight($height);
            });

        })->save('xlsx', storage_path('excel/exports'));

        // Load the workbooks to merge in a collection.
        // This example is assuming they're stored in the Laravel storage folder.
        $workbooks = collect([
            'excel/exports/allegro_imports_template.xlsx',
            'excel/exports/techno_imports_template.xlsx',
            'excel/exports/magic_imports_template.xlsx',
        ])->map(function ($filename) {
            return Excel::load(storage_path($filename));
        });

        // Create merged workbook
        Excel::create('imports', function ($excel) use ($workbooks) {
            // For each workbook to be merged
            $workbooks->each(function ($workbook) use ($excel) {
                // Get all the sheets
                collect($workbook->getAllSheets())->each(function ($sheet) use ($excel) {
                    // And add them to the merged workbook
                    $excel->addExternalSheet($sheet);
                });
            });
        })->save('xlsx', storage_path('excel/exports')); // save merged workbook to storage/exports/workbookC.xlsx

        return response()->download(storage_path('excel/exports/imports.xlsx'))->deleteFileAfterSend(true);
    }

    public function exports(Request $request)
    {
        $today = new Carbon($request->date);
        $today = $today->format('d.m.y');
        $exports = Export::with('rawFirm')->whereDate('created_at', new Carbon($request->date))->whereHas('rawFirm', function($query){
            $query->with('firm')->whereHas('firm', function($query){
                $query->where('company_name', 'Allegro');
            });
        })->select('*', DB::raw('sum(quantity) as quantity'))->groupBy('raw_firm_id')->get();
        Excel::load(public_path('storage/allegro_exports_template.xlsx'), function ($excel) use ($today, $exports) {
            $excel->sheet(0, function ($sheet) use ($today, $exports) {
                $sheet->cell('C9', function ($cell) use ($today) {
                    $cell->setValue('Дата         ' . $today . ' г.');
                });
                $sheet->cell('H9', function ($cell) use ($today) {
                    $cell->setValue('Дата         ' . $today . ' г.');
                });
                $counter = 0;
                $sum = 0;
                foreach ($exports as $export) {
                    $sheet->appendRow(14 + $counter, array($counter + 1, $export->raw->name, 'кг.', $export->quantity, '', $counter + 1, $export->raw->name, 'кг.', $export->quantity));
                    $counter++;
                    $sum += $export->quantity;
                }

                $sheet->appendRow(14 + $counter, array('ИТОГО', '', '', $sum, '', 'ИТОГО', '', '', $sum));
                $sheet->appendRow(15 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(16 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(17 + $counter, array('', 'Отправитель Нач. Участка :         Хамидов А.', '          _______________________', '', '', '', 'Отправитель Нач. Участка :         Хамидов А.', '          _______________________', ''));
                $sheet->appendRow(18 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(19 + $counter, array('', 'Получатель Зав. Склад :               Шарипов Т.', '          _______________________', '', '', '', 'Получатель Зав. Склад :                Шарипов Т.', '          _______________________', ''));
                $sheet->appendRow(20 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(21 + $counter, array('', 'Проверил Кладовщик:                 Файзиев Ш.', '          _______________________', '', '', '', 'Проверил Кладовщик:                  Файзиев Ш.', '          _______________________', ''));
                $sheet->appendRow(22 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(23 + $counter, array('', 'Материальный Бух:', '          _______________________', '', '', '', 'Материальный Бух:', '          _______________________', ''));

                $sheet->mergeCells('A' . (14 + $counter) . ':C' . (14 + $counter));
                $sheet->mergeCells('F' . (14 + $counter) . ':H' . (14 + $counter));

                for ($i = 17 + $counter; $i <= 23 + $counter; $i = $i + 2) {
                    $sheet->mergeCells('C' . ($i) . ':D' . ($i));
                    $sheet->mergeCells('H' . ($i) . ':I' . ($i));
                }
                $sheet->cells('A14:I' . (14 + $counter), function ($cells) {
                    $cells->setFontSize(14);
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                });
                $sheet->cells('A' . (14 + $counter) . ':I' . (14 + $counter), function ($cells) {
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('A' . (17 + $counter) . ':I' . (23 + $counter), function ($cells) {
                    $cells->setFontSize(16);
                    $cells->setValignment('center');
                });
                $sheet->setBorder('A14:D' . (14 + $counter), 'thin');
                $sheet->setBorder('F14:I' . (14 + $counter), 'thin');
                $sheet->setWidth('A', 5);
                $height = array();
                for ($i = 14; $i < 23 + $counter; $i++) {
                    if ($i <= $i + $counter)
                        $height[$i] = 20;
                    else
                        $height[$i] = 16;

                }
                $sheet->setHeight($height);
            });

        })->save('xlsx', storage_path('excel/exports'));
        $exports = Export::with('rawFirm')->whereDate('created_at', new Carbon($request->date))->whereHas('rawFirm', function($query){
            $query->with('firm')->whereHas('firm', function($query){
                $query->where('company_name', 'Techno Continental');
            });
        })->select('*', DB::raw('sum(quantity) as quantity'))->groupBy('raw_firm_id')->get();
        Excel::load(public_path('storage/techno_exports_template.xlsx'), function ($excel) use ($today, $exports) {
            $excel->sheet(0, function ($sheet) use ($today, $exports) {
                $sheet->cell('C9', function ($cell) use ($today) {
                    $cell->setValue('Дата         ' . $today . ' г.');
                });
                $sheet->cell('H9', function ($cell) use ($today) {
                    $cell->setValue('Дата         ' . $today . ' г.');
                });
                $counter = 0;
                $sum = 0;
                foreach ($exports as $export) {
                    $sheet->appendRow(14 + $counter, array($counter + 1, $export->raw->name, 'кг.', $export->quantity, '', $counter + 1, $export->raw->name, 'кг.', $export->quantity));
                    $counter++;
                    $sum += $export->quantity;
                }

                $sheet->appendRow(14 + $counter, array('ИТОГО', '', '', $sum, '', 'ИТОГО', '', '', $sum));
                $sheet->appendRow(15 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(16 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(17 + $counter, array('', 'Отправитель Нач. Участка :         Хамидов А.', '          _______________________', '', '', '', 'Отправитель Нач. Участка :         Хамидов А.', '          _______________________', ''));
                $sheet->appendRow(18 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(19 + $counter, array('', 'Получатель Зав. Склад :               Шарипов Т.', '          _______________________', '', '', '', 'Получатель Зав. Склад :                Шарипов Т.', '          _______________________', ''));
                $sheet->appendRow(20 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(21 + $counter, array('', 'Проверил Кладовщик:                 Файзиев Ш.', '          _______________________', '', '', '', 'Проверил Кладовщик:                  Файзиев Ш.', '          _______________________', ''));
                $sheet->appendRow(22 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(23 + $counter, array('', 'Материальный Бух:', '          _______________________', '', '', '', 'Материальный Бух:', '          _______________________', ''));

                $sheet->mergeCells('A' . (14 + $counter) . ':C' . (14 + $counter));
                $sheet->mergeCells('F' . (14 + $counter) . ':H' . (14 + $counter));

                for ($i = 17 + $counter; $i <= 23 + $counter; $i = $i + 2) {
                    $sheet->mergeCells('C' . ($i) . ':D' . ($i));
                    $sheet->mergeCells('H' . ($i) . ':I' . ($i));
                }
                $sheet->cells('A14:I' . (14 + $counter), function ($cells) {
                    $cells->setFontSize(14);
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                });
                $sheet->cells('A' . (14 + $counter) . ':I' . (14 + $counter), function ($cells) {
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('A' . (17 + $counter) . ':I' . (23 + $counter), function ($cells) {
                    $cells->setFontSize(16);
                    $cells->setValignment('center');
                });
                $sheet->setBorder('A14:D' . (14 + $counter), 'thin');
                $sheet->setBorder('F14:I' . (14 + $counter), 'thin');
                $sheet->setWidth('A', 5);
                $height = array();
                for ($i = 14; $i < 23 + $counter; $i++) {
                    if ($i <= $i + $counter)
                        $height[$i] = 20;
                    else
                        $height[$i] = 16;

                }
                $sheet->setHeight($height);
            });

        })->save('xlsx', storage_path('excel/exports'));
        $exports = Export::with('rawFirm')->whereDate('created_at', new Carbon($request->date))->whereHas('rawFirm', function($query){
            $query->with('firm')->whereHas('firm', function($query){
                $query->where('company_name', 'Magic Plastics');
            });
        })->select('*', DB::raw('sum(quantity) as quantity'))->groupBy('raw_firm_id')->get();
        Excel::load(public_path('storage/magic_exports_template.xlsx'), function ($excel) use ($today, $exports) {
            $excel->sheet(0, function ($sheet) use ($today, $exports) {
                $sheet->cell('C9', function ($cell) use ($today) {
                    $cell->setValue('Дата         ' . $today . ' г.');
                });
                $sheet->cell('H9', function ($cell) use ($today) {
                    $cell->setValue('Дата         ' . $today . ' г.');
                });
                $counter = 0;
                $sum = 0;
                foreach ($exports as $export) {
                    $sheet->appendRow(14 + $counter, array($counter + 1, $export->raw->name, 'кг.', $export->quantity, '', $counter + 1, $export->raw->name, 'кг.', $export->quantity));
                    $counter++;
                    $sum += $export->quantity;
                }

                $sheet->appendRow(14 + $counter, array('ИТОГО', '', '', $sum, '', 'ИТОГО', '', '', $sum));
                $sheet->appendRow(15 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(16 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(17 + $counter, array('', 'Отправитель Нач. Участка :         Хамидов А.', '          _______________________', '', '', '', 'Отправитель Нач. Участка :         Хамидов А.', '          _______________________', ''));
                $sheet->appendRow(18 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(19 + $counter, array('', 'Получатель Зав. Склад :               Шарипов Т.', '          _______________________', '', '', '', 'Получатель Зав. Склад :                Шарипов Т.', '          _______________________', ''));
                $sheet->appendRow(20 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(21 + $counter, array('', 'Проверил Кладовщик:                 Файзиев Ш.', '          _______________________', '', '', '', 'Проверил Кладовщик:                  Файзиев Ш.', '          _______________________', ''));
                $sheet->appendRow(22 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(23 + $counter, array('', 'Материальный Бух:', '          _______________________', '', '', '', 'Материальный Бух:', '          _______________________', ''));

                $sheet->mergeCells('A' . (14 + $counter) . ':C' . (14 + $counter));
                $sheet->mergeCells('F' . (14 + $counter) . ':H' . (14 + $counter));

                for ($i = 17 + $counter; $i <= 23 + $counter; $i = $i + 2) {
                    $sheet->mergeCells('C' . ($i) . ':D' . ($i));
                    $sheet->mergeCells('H' . ($i) . ':I' . ($i));
                }
                $sheet->cells('A14:I' . (14 + $counter), function ($cells) {
                    $cells->setFontSize(14);
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                });
                $sheet->cells('A' . (14 + $counter) . ':I' . (14 + $counter), function ($cells) {
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('A' . (17 + $counter) . ':I' . (23 + $counter), function ($cells) {
                    $cells->setFontSize(16);
                    $cells->setValignment('center');
                });
                $sheet->setBorder('A14:D' . (14 + $counter), 'thin');
                $sheet->setBorder('F14:I' . (14 + $counter), 'thin');
                $sheet->setWidth('A', 5);
                $height = array();
                for ($i = 14; $i < 23 + $counter; $i++) {
                    if ($i <= $i + $counter)
                        $height[$i] = 20;
                    else
                        $height[$i] = 16;

                }
                $sheet->setHeight($height);
            });

        })->save('xlsx', storage_path('excel/exports'));
        // Load the workbooks to merge in a collection.
        // This example is assuming they're stored in the Laravel storage folder.
        $workbooks = collect([
            'excel/exports/allegro_exports_template.xlsx',
            'excel/exports/techno_exports_template.xlsx',
            'excel/exports/magic_exports_template.xlsx',
        ])->map(function ($filename) {
            return Excel::load(storage_path($filename));
        });

        // Create merged workbook
        Excel::create('exports', function ($excel) use ($workbooks) {
            // For each workbook to be merged
            $workbooks->each(function ($workbook) use ($excel) {
                // Get all the sheets
                collect($workbook->getAllSheets())->each(function ($sheet) use ($excel) {
                    // And add them to the merged workbook
                    $excel->addExternalSheet($sheet);
                });
            });
        })->save('xlsx', storage_path('excel/exports')); // save merged workbook to storage/exports/workbookC.xlsx

        return response()->download(storage_path('excel/exports/exports.xlsx'))->deleteFileAfterSend(true);
    }

    public function requests(Request $request)
    {
        $today = new Carbon($request->date);
        $today = $today->format('d.m.y');
        $exports = Export::with('rawFirm')->whereDate('created_at', new Carbon($request->date))->whereHas('rawFirm', function($query){
            $query->with('firm')->whereHas('firm', function($query){
                $query->where('company_name', 'Allegro');
            });
        })->select('*', DB::raw('sum(quantity) as quantity'))->groupBy('raw_firm_id')->get();
        Excel::load(public_path('storage/allegro_requests_template.xlsx'), function ($excel) use ($today, $exports) {
            $excel->sheet(0, function ($sheet) use ($today, $exports) {
                $sheet->cell('C9', function ($cell) use ($today) {
                    $cell->setValue('Дата         ' . $today . ' г.');
                });
                $sheet->cell('H9', function ($cell) use ($today) {
                    $cell->setValue('Дата         ' . $today . ' г.');
                });
                $counter = 0;
                $sum = 0;
                foreach ($exports as $export) {
                    $sheet->appendRow(14 + $counter, array($counter + 1, $export->raw->name, 'кг.', $export->quantity, '', $counter + 1, $export->raw->name, 'кг.', $export->quantity));
                    $counter++;
                    $sum += $export->quantity;
                }

                $sheet->appendRow(14 + $counter, array('ИТОГО', '', '', $sum, '', 'ИТОГО', '', '', $sum));
                $sheet->appendRow(15 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(16 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(17 + $counter, array('', 'Отправитель Нач. Участка :         Хамидов А.', '          _______________________', '', '', '', 'Отправитель Нач. Участка :         Хамидов А.', '          _______________________', ''));
                $sheet->appendRow(18 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(19 + $counter, array('', 'Получатель Зав. Склад :               Шарипов Т.', '          _______________________', '', '', '', 'Получатель Зав. Склад :                Шарипов Т.', '          _______________________', ''));
                $sheet->appendRow(20 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(21 + $counter, array('', 'Проверил Кладовщик:                 Файзиев Ш.', '          _______________________', '', '', '', 'Проверил Кладовщик:                  Файзиев Ш.', '          _______________________', ''));
                $sheet->appendRow(22 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(23 + $counter, array('', 'Материальный Бух:', '          _______________________', '', '', '', 'Материальный Бух:', '          _______________________', ''));

                $sheet->mergeCells('A' . (14 + $counter) . ':C' . (14 + $counter));
                $sheet->mergeCells('F' . (14 + $counter) . ':H' . (14 + $counter));

                for ($i = 17 + $counter; $i <= 23 + $counter; $i = $i + 2) {
                    $sheet->mergeCells('C' . ($i) . ':D' . ($i));
                    $sheet->mergeCells('H' . ($i) . ':I' . ($i));
                }
                $sheet->cells('A14:I' . (14 + $counter), function ($cells) {
                    $cells->setFontSize(14);
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                });
                $sheet->cells('A' . (14 + $counter) . ':I' . (14 + $counter), function ($cells) {
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('A' . (17 + $counter) . ':I' . (23 + $counter), function ($cells) {
                    $cells->setFontSize(16);
                    $cells->setValignment('center');
                });
                $sheet->setBorder('A14:D' . (14 + $counter), 'thin');
                $sheet->setBorder('F14:I' . (14 + $counter), 'thin');
                $sheet->setWidth('A', 5);
                $height = array();
                for ($i = 14; $i < 23 + $counter; $i++) {
                    if ($i <= $i + $counter)
                        $height[$i] = 20;
                    else
                        $height[$i] = 16;

                }
                $sheet->setHeight($height);
            });

        })->save('xlsx', storage_path('excel/exports'));
        $exports = Export::with('rawFirm')->whereDate('created_at', new Carbon($request->date))->whereHas('rawFirm', function($query){
            $query->with('firm')->whereHas('firm', function($query){
                $query->where('company_name', 'Techno Continental');
            });
        })->select('*', DB::raw('sum(quantity) as quantity'))->groupBy('raw_firm_id')->get();
        Excel::load(public_path('storage/techno_requests_template.xlsx'), function ($excel) use ($today, $exports) {
            $excel->sheet(0, function ($sheet) use ($today, $exports) {
                $sheet->cell('C9', function ($cell) use ($today) {
                    $cell->setValue('Дата         ' . $today . ' г.');
                });
                $sheet->cell('H9', function ($cell) use ($today) {
                    $cell->setValue('Дата         ' . $today . ' г.');
                });
                $counter = 0;
                $sum = 0;
                foreach ($exports as $export) {
                    $sheet->appendRow(14 + $counter, array($counter + 1, $export->raw->name, 'кг.', $export->quantity, '', $counter + 1, $export->raw->name, 'кг.', $export->quantity));
                    $counter++;
                    $sum += $export->quantity;
                }

                $sheet->appendRow(14 + $counter, array('ИТОГО', '', '', $sum, '', 'ИТОГО', '', '', $sum));
                $sheet->appendRow(15 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(16 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(17 + $counter, array('', 'Отправитель Нач. Участка :         Хамидов А.', '          _______________________', '', '', '', 'Отправитель Нач. Участка :         Хамидов А.', '          _______________________', ''));
                $sheet->appendRow(18 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(19 + $counter, array('', 'Получатель Зав. Склад :               Шарипов Т.', '          _______________________', '', '', '', 'Получатель Зав. Склад :                Шарипов Т.', '          _______________________', ''));
                $sheet->appendRow(20 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(21 + $counter, array('', 'Проверил Кладовщик:                 Файзиев Ш.', '          _______________________', '', '', '', 'Проверил Кладовщик:                  Файзиев Ш.', '          _______________________', ''));
                $sheet->appendRow(22 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(23 + $counter, array('', 'Материальный Бух:', '          _______________________', '', '', '', 'Материальный Бух:', '          _______________________', ''));

                $sheet->mergeCells('A' . (14 + $counter) . ':C' . (14 + $counter));
                $sheet->mergeCells('F' . (14 + $counter) . ':H' . (14 + $counter));

                for ($i = 17 + $counter; $i <= 23 + $counter; $i = $i + 2) {
                    $sheet->mergeCells('C' . ($i) . ':D' . ($i));
                    $sheet->mergeCells('H' . ($i) . ':I' . ($i));
                }
                $sheet->cells('A14:I' . (14 + $counter), function ($cells) {
                    $cells->setFontSize(14);
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                });
                $sheet->cells('A' . (14 + $counter) . ':I' . (14 + $counter), function ($cells) {
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('A' . (17 + $counter) . ':I' . (23 + $counter), function ($cells) {
                    $cells->setFontSize(16);
                    $cells->setValignment('center');
                });
                $sheet->setBorder('A14:D' . (14 + $counter), 'thin');
                $sheet->setBorder('F14:I' . (14 + $counter), 'thin');
                $sheet->setWidth('A', 5);
                $height = array();
                for ($i = 14; $i < 23 + $counter; $i++) {
                    if ($i <= $i + $counter)
                        $height[$i] = 20;
                    else
                        $height[$i] = 16;

                }
                $sheet->setHeight($height);
            });

        })->save('xlsx', storage_path('excel/exports'));
        $exports = Export::with('rawFirm')->whereDate('created_at', new Carbon($request->date))->whereHas('rawFirm', function($query){
            $query->with('firm')->whereHas('firm', function($query){
                $query->where('company_name', 'Magic Plastics');
            });
        })->select('*', DB::raw('sum(quantity) as quantity'))->groupBy('raw_firm_id')->get();
        Excel::load(public_path('storage/magic_requests_template.xlsx'), function ($excel) use ($today, $exports) {
            $excel->sheet(0, function ($sheet) use ($today, $exports) {
                $sheet->cell('C9', function ($cell) use ($today) {
                    $cell->setValue('Дата         ' . $today . ' г.');
                });
                $sheet->cell('H9', function ($cell) use ($today) {
                    $cell->setValue('Дата         ' . $today . ' г.');
                });
                $counter = 0;
                $sum = 0;
                foreach ($exports as $export) {
                    $sheet->appendRow(14 + $counter, array($counter + 1, $export->raw->name, 'кг.', $export->quantity, '', $counter + 1, $export->raw->name, 'кг.', $export->quantity));
                    $counter++;
                    $sum += $export->quantity;
                }

                $sheet->appendRow(14 + $counter, array('ИТОГО', '', '', $sum, '', 'ИТОГО', '', '', $sum));
                $sheet->appendRow(15 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(16 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(17 + $counter, array('', 'Отправитель Нач. Участка :         Хамидов А.', '          _______________________', '', '', '', 'Отправитель Нач. Участка :         Хамидов А.', '          _______________________', ''));
                $sheet->appendRow(18 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(19 + $counter, array('', 'Получатель Зав. Склад :               Шарипов Т.', '          _______________________', '', '', '', 'Получатель Зав. Склад :                Шарипов Т.', '          _______________________', ''));
                $sheet->appendRow(20 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(21 + $counter, array('', 'Проверил Кладовщик:                 Файзиев Ш.', '          _______________________', '', '', '', 'Проверил Кладовщик:                  Файзиев Ш.', '          _______________________', ''));
                $sheet->appendRow(22 + $counter, array('', '', '', '', '', '', '', '', ''));
                $sheet->appendRow(23 + $counter, array('', 'Материальный Бух:', '          _______________________', '', '', '', 'Материальный Бух:', '          _______________________', ''));

                $sheet->mergeCells('A' . (14 + $counter) . ':C' . (14 + $counter));
                $sheet->mergeCells('F' . (14 + $counter) . ':H' . (14 + $counter));

                for ($i = 17 + $counter; $i <= 23 + $counter; $i = $i + 2) {
                    $sheet->mergeCells('C' . ($i) . ':D' . ($i));
                    $sheet->mergeCells('H' . ($i) . ':I' . ($i));
                }
                $sheet->cells('A14:I' . (14 + $counter), function ($cells) {
                    $cells->setFontSize(14);
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                });
                $sheet->cells('A' . (14 + $counter) . ':I' . (14 + $counter), function ($cells) {
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('A' . (17 + $counter) . ':I' . (23 + $counter), function ($cells) {
                    $cells->setFontSize(16);
                    $cells->setValignment('center');
                });
                $sheet->setBorder('A14:D' . (14 + $counter), 'thin');
                $sheet->setBorder('F14:I' . (14 + $counter), 'thin');
                $sheet->setWidth('A', 5);
                $height = array();
                for ($i = 14; $i < 23 + $counter; $i++) {
                    if ($i <= $i + $counter)
                        $height[$i] = 20;
                    else
                        $height[$i] = 16;

                }
                $sheet->setHeight($height);
            });

        })->save('xlsx', storage_path('excel/exports'));

        // Load the workbooks to merge in a collection.
        // This example is assuming they're stored in the Laravel storage folder.
        $workbooks = collect([
            'excel/exports/allegro_requests_template.xlsx',
            'excel/exports/techno_requests_template.xlsx',
            'excel/exports/magic_requests_template.xlsx',
        ])->map(function ($filename) {
            return Excel::load(storage_path($filename));
        });

        // Create merged workbook
        Excel::create('requests', function ($excel) use ($workbooks) {
            // For each workbook to be merged
            $workbooks->each(function ($workbook) use ($excel) {
                // Get all the sheets
                collect($workbook->getAllSheets())->each(function ($sheet) use ($excel) {
                    // And add them to the merged workbook
                    $excel->addExternalSheet($sheet);
                });
            });
        })->save('xlsx', storage_path('excel/exports')); // save merged workbook to storage/exports/workbookC.xlsx

        return response()->download(storage_path('excel/exports/requests.xlsx'))->deleteFileAfterSend(true);
    }
}


