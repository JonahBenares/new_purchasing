<?php

namespace App\Imports;

use App\Models\JORHead;
use App\Models\JORLaborDetails;
use App\Models\JORMaterialDetails;
use App\Models\JORNotes;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
class JORLaborImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    public $data;
    private $rows = 0;
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
        if($value!=''){
            try {
                return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
            } catch (\ErrorException $e) {
                return \Carbon\Carbon::createFromFormat($format, $value);
            }
        }
    }

    public function headingRow(): int
    {
        return 15;
    }
    public function model(array $row)
    {
        if(count($row)!=0){
            $item_no=$row['item_no'] ?? '';
            $scope_of_work=$row['scope_of_work'] ?? '';
            $qty=$row['qty'] ?? 0;
            $uom=$row['uom'] ?? '';
            $unit_cost=$row['unit_cost'] ?? 0;
            if($item_no!=''){  
                if ($item_no == 'Materials:') {
                    $this->rows++;
                }
                if($this->rows==0){
                    $labordetails['jor_head_id']=$this->jor_head_id;
                    $labordetails['scope_of_work']=$scope_of_work;
                    $labordetails['quantity']=$qty;
                    $labordetails['uom']=$uom;
                    $labordetails['unit_cost']=$unit_cost;
                    $labordetails['total_cost']=$unit_cost * $qty;
                    $labordetails['status']='Draft';
                    $jor_labor_details_id=JORlaborDetails::create($labordetails);
                }else if($this->rows==1){
                    if ($item_no != 'Materials:' && $item_no != 'Item No') {
                        if($item_no!='Notes:'){
                            $date_needed=$row[10] ?? '';
                            $date_needed_disp= ($date_needed!='') ? date('Y-m-d',strtotime($this->transformDate($date_needed))) : '';
                            $jor_no=JORHead::where('id',$this->jor_head_id)->value('jor_no');
                            $materialdetails['jor_head_id']=$this->jor_head_id;
                            $materialdetails['quantity']=$scope_of_work;
                            $materialdetails['uom']=$row[2] ?? '';
                            $materialdetails['pn_no']=$row[3] ?? '';
                            $materialdetails['item_description']=$row[4] ?? '';
                            $materialdetails['wh_stocks']=$row[9] ?? 0;
                            $materialdetails['date_needed']=$date_needed_disp;
                            $materialdetails['status']='Draft';
                            $jor_material_details_id=JORMaterialDetails::create($materialdetails);
                        }else if($item_no=='Notes:' &&  $scope_of_work!=''){
                            $jornotes['jor_head_id']=$this->jor_head_id;
                            $jornotes['notes']=$scope_of_work;
                            $jornotes['status']='Draft';
                            JORNotes::create($jornotes);
                        }
                    }
                }
            }
        }
    }
}
