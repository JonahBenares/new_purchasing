<?php

namespace App\Imports;

use App\Models\PRHead;
use App\Models\PRDetails;
use App\Models\PRSeries;
use App\Models\PrReportDetails;
use App\Models\Departments;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
class PRImport implements WithMappedCells, ToModel, WithHeadingRow
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
            'purchase_request'  => 'C7',
            'department' => 'I7',
            'department_code' => 'I8',
            'date_prepared' => 'C8',
            'date_issued' => 'C9',
            'site_pr' => 'C10',
            'requestor' => 'I9',
            'urgency' => 'I10',
            'purpose' => 'C11',
            'enduse' => 'C12',
        ];
    }

    public function headingRow(): int
    {
        return 6;
    }

    public function model(array $row)
    {
        if(count($row)!=0){
            if($row['purchase_request']!=''){
                $year= date("Y", strtotime($this->transformDate($row['date_prepared'])));
                $year_short = date("y",strtotime($this->transformDate($row['date_prepared'])));
                $series_rows = PRSeries::where('year',$year)->count();
                if($series_rows==0){
                    $pr_series='0001';
                    $pr_no = $row['department_code'].$year_short."-".$pr_series;
                } else {
                    $max_series=PRSeries::where('year',$year)->max('series');
                    $pr_series=$max_series+1;
                    $pr_no = $row['department_code'].$year_short."-".Str::padLeft($pr_series, 4,'000');
                }
                $series['year']=$year;
                $series['series']=$pr_series;
                $pr_series=PRSeries::create($series);
                if($pr_series){
                    $department_id=Departments::where('department_name','LIKE','%'.$row['department'].'%')->value('id');
                    $prhead['location']=$row['purchase_request'];
                    $prhead['date_prepared']=date('Y-m-d',strtotime($this->transformDate($row['date_prepared'])));
                    $prhead['pr_date']=date('Y-m-d',strtotime($this->transformDate($row['date_issued'])));
                    $prhead['pr_no']=$pr_no;
                    $prhead['location']=$row['purchase_request'];
                    $prhead['site_pr']=$row['site_pr'];
                    $prhead['department_id']=$department_id;
                    $prhead['department_name']=$row['department'];
                    $prhead['dept_code']=$row['department_code'];
                    $prhead['requestor']=$row['requestor'];
                    $prhead['urgency']=$row['urgency'];
                    $prhead['purpose']=$row['purpose'];
                    $prhead['enduse']=$row['enduse'];
                    $prhead['petty_cash']=0;
                    $prhead['process_code']='';
                    $prhead['user_id']= $this->user_id;
                    $prhead['method']='Upload';
                    $prhead['status']='Draft';
                    $pr_head_id=PRHead::create($prhead);
                    $this->data = $prhead;
                    $this->id = $pr_head_id->id;
                }
            }
        }
    }
}
