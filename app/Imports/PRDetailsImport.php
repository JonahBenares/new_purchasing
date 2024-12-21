<?php

namespace App\Imports;

use App\Models\PRHead;
use App\Models\PRDetails;
use App\Models\PrReportDetails;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Config;
class PRDetailsImport implements ToModel, WithHeadingRow, WithMultipleSheets
{
    public $data;
    public function sheets(): array
    {
        return [
            'Sheet1' => $this,
        ];
    }

    public function  __construct($pr_head_id)
    {
        $this->pr_head_id =$pr_head_id;
    }
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
            $qty=$row['qty'] ?? 0;
            $uom=$row['uom'] ?? '';
            $part_no=$row['part_no'] ?? '';
            $description=$row['description'] ?? '';
            $wh_stocks=$row['wh_stocks'] ?? 0;
            $date_needed=$row['date_needed'] ?? 0;
            $date_needed_disp=date('Y-m-d',strtotime($this->transformDate($date_needed)));
            if($item_no!=''){
                $company=Config::get('constants.company');
                $pr_no=PRHead::where('id',$this->pr_head_id)->value('pr_no')."-".$company;
                $prdetails['pr_head_id']=$this->pr_head_id;
                $prdetails['quantity']=$qty;
                $prdetails['uom']=$uom;
                $prdetails['pn_no']=$part_no;
                $prdetails['item_description']=$description;
                $prdetails['wh_stocks']=$wh_stocks;
                $prdetails['date_needed']=$date_needed_disp;
                $prdetails['status']='Draft';
                $pr_details_id=PRDetails::create($prdetails);
                //PR REPORT
                if($pr_details_id){
                    $prreport['pr_no']=$pr_no;
                    $prreport['pr_details_id']=$pr_details_id->id;
                    $prreport['item_description']=$description;
                    $prreport['pr_qty']=$qty;
                    $prreport['uom']=$uom;
                    $prreport['status']='Pending for RFQ';
                    PrReportDetails::create($prreport);
                }
            }
        }
    }
    // public function collection(Collection $rows) {
    //     $xyz = $rows->toArray();
    //     $this->data = $rows;
    //     return $xyz;
    // }
}
