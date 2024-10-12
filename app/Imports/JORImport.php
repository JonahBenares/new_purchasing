<?php

namespace App\Imports;

use App\Models\JORHead;
use App\Models\JORLaborDetails;
use App\Models\JORMaterialDetails;
use App\Models\JORSeries;
use App\Models\JORNotes;
use App\Models\Departments;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
class JORImport implements WithMappedCells, ToModel, WithHeadingRow
{
    public $data;
    public $id;
    public function  __construct($user_id)
    {
        $this->user_id =$user_id;
    }


    public function transformDate($value, $format = 'Y-m-d'){
        try {
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {
            return \Carbon\Carbon::createFromFormat($format, $value);
        }
    }

    public function mapping(): array
    {
        return [
            'jo_request'  => 'C7',
            'date_prepared' => 'C8',
            'department' => 'C9',
            'jo_no' => 'C10',
            'requestor' => 'C11',
            'purpose' => 'C12',
            'duration' => 'I7',
            'completion_date' => 'I8',
            'delivery_date' => 'I9',
            'urgency_no' => 'I10',
            'general_description' => 'B13',
        ];
    }

    public function headingRow(): int
    {
        return 6;
    }

    public function model(array $row)
    {
        if(count($row)!=0){
            $department_id=Departments::where('department_name',$row['department'])->value('id');
            $department_code=Departments::where('department_name',$row['department'])->value('department_code');
            if($row['jo_request']!=''){
                $year= date("Y", strtotime($this->transformDate($row['date_prepared'])));
                $year_short = date("y",strtotime($this->transformDate($row['date_prepared'])));
                $series_rows = JORSeries::where('year',$year)->count();
                if($series_rows==0){
                    $jor_series='0001';
                    $jor_no = $department_code.$year_short."-".$jor_series;
                } else {
                    $max_series=JORSeries::where('year',$year)->max('series');
                    $jor_series=$max_series+1;
                    $jor_no = $department_code.$year_short."-".Str::padLeft($jor_series, 4,'000');
                }
                $series['year']=$year;
                $series['series']=$jor_series;
                $jor_series_insert=JORSeries::create($series);
                if($jor_series_insert){
                    $jorhead['general_description']=$row['general_description'];
                    $jorhead['location']=$row['jo_request'];
                    $jorhead['date_prepared']=date('Y-m-d',strtotime($this->transformDate($row['date_prepared'])));
                    $jorhead['duration']=$row['duration'];
                    $jorhead['completion_date']=date('Y-m-d',strtotime($this->transformDate($row['completion_date'])));
                    $jorhead['delivery_date']=date('Y-m-d',strtotime($this->transformDate($row['delivery_date'])));
                    $jorhead['jor_no']=$jor_no;
                    $jorhead['site_jor']=$row['jo_no'];
                    $jorhead['department_id']=$department_id;
                    $jorhead['department_name']=$row['department'];
                    $jorhead['dept_code']=$department_code;
                    $jorhead['requestor']=$row['requestor'];
                    $jorhead['urgency']=$row['urgency_no'];
                    $jorhead['purpose']=$row['purpose'];
                    $jorhead['process_code']='';
                    $jorhead['user_id']= $this->user_id;
                    $jorhead['method']='Upload';
                    $jorhead['status']='Draft';
                    $jor_head_id=JORHead::create($jorhead);
                    $this->data = $jorhead;
                    $this->id = $jor_head_id->id;
                }
            }
        }
    }
}