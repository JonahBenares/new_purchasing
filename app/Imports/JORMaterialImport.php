<?php

namespace App\Imports;

use App\Models\JORHead;
use App\Models\JORNotes;
use App\Models\JORMaterialDetails;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
class JORMaterialImport implements ToModel, WithHeadingRow
{
    public $data;
    // public function sheets(): array
    // {
    //     return [
    //         'Sheet1' => $this,
    //     ];
    // }

    public function  __construct($jor_head_id)
    {
        $this->jor_head_id =$jor_head_id;
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
        return 20;
    }
    public function model(array $row)
    {
        if(count($row)!=0){
            $item_no=$row['item_no'] ?? '';
            $qty=$row['qty'] ?? '';
            $uom=$row['uom'] ?? '';
            $pn_no=$row['pn_no'] ?? '';
            $description=$row['item_description'] ?? '';
            $wh_stocks=$row['wh_stocks'] ?? '';
            $date_needed=$row['date_needed'] ?? 0;
            $date_needed_disp=date('Y-m-d',strtotime($this->transformDate($date_needed)));
            if($item_no!='' && $item_no!='Notes:'){
                $jor_no=JORHead::where('id',$this->jor_head_id)->value('jor_no');
                $materialdetails['jor_head_id']=$this->jor_head_id;
                $materialdetails['quantity']=$qty;
                $materialdetails['uom']=$uom;
                $materialdetails['pn_no']=$pn_no;
                $materialdetails['item_description']=$description;
                $materialdetails['wh_stocks']=$wh_stocks;
                $materialdetails['date_needed']=$date_needed_disp;
                $materialdetails['status']='Draft';
                $jor_material_details_id=JORMaterialDetails::create($materialdetails);
            }
            if($item_no=='Notes:' &&  $qty!=''){
                $jornotes['jor_head_id']=$this->jor_head_id;
                $jornotes['notes']=$qty;
                JORNotes::create($jornotes);
            }
        }
    }
}
