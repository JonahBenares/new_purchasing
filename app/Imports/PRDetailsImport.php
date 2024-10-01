<?php

namespace App\Imports;

use App\Models\PRHead;
use App\Models\PRDetails;
use App\Models\PrReportDetails;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Carbon;

class PRDetailsImport implements ToModel, WithHeadingRow
{
    public function transformDate($value, $format = 'Y-m-d'){
        try {
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {
            return \Carbon\Carbon::createFromFormat($format, $value);
        }
    }

    public function headingRow(): int
    {
        return 13;
    }

    public function model(array $row)
    {
        if(count($row)!=0){
            $item_no=$row['item_no'] ?? '';
            $qty=$row['qty'] ?? '';
            $uom=$row['uom'] ?? '';
            $part_no=$row['part_no'] ?? '';
            $description=$row['description'] ?? '';
            $wh_stocks=$row['wh_stocks'] ?? '';
            $date_needed=$row['date_needed'] ?? 0;
            $date_needed_disp=$this->transformDate($date_needed);
            if($item_no!=''){
                $pr_head_id=PRHead::max('id');
                $prdetails['pr_head_id']=$pr_head_id;
                $prdetails['quantity']=$qty;
                $prdetails['uom']=$uom;
                $prdetails['pn_no']=$part_no;
                $prdetails['item_description']=$description;
                $prdetails['wh_stocks']=$wh_stocks;
                $prdetails['date_needed']=$date_needed_disp;
                $prdetails['status']='Saved';
                PRDetails::create($prdetails);
            }
        }
    }
}
