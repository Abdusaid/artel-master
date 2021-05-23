<?php

namespace App\Http\Controllers;

use App\Import;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;

class WordController extends Controller
{
    public function notice(Request $request)
    {
        $import = Import::find($request->import_id);
        $date = new Carbon($import->created_at);
        $import_date = $date->format('d.m.y');
        $notice_template = new TemplateProcessor(public_path('storage/notice_template.docx'));
        $notice_template->setValue('company-name',$import->firm->company_name);
        $notice_template->setValue('product-name',$import->raw->name);
        $notice_template->setValue('serial-number', $import->serial_number ? $import->serial_number : "");
        $notice_template->setValue('import-quantity',$import->quantity);
        $notice_template->setValue('import-date',$import_date);
        $notice_template->setValue('supplier',$import->supplier ? $import->supplier : "");
        $notice_template->setValue('day',$date->day);
        $notice_template->setValue('month',$date->month);
        $notice_template->setValue('year',$date->year);

        $notice_template->saveAs(storage_path('word/exports/notice_template.docx'));

        return response()->download(storage_path('word/exports/notice_template.docx'))->deleteFileAfterSend(true);
    }
}
