<?php

namespace App\Imports;

use App\Models\PRHead;
use App\Models\PRDetails;
use App\Models\PRSeries;
use App\Models\PrReportDetails;
use App\Models\Departments;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Config;
class PRImport implements WithMappedCells, ToModel, WithHeadingRow
{
    public $data;
    public $id;
    public $dept_checker;
    public $req_checker;
    // public $site_checker;
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
            // 'department' => 'I7',
            'department' => 'G7',
            // 'site_pr' => 'G8',
            // 'site_pr' => 'I8',
            // 'department_code' => 'I8',
            'date_prepared' => 'C8',
            'date_issued' => 'C9',
            'requestor' => 'G8',
            // 'requestor' => 'I9',
            'urgency' => 'G9',
            // 'urgency' => 'I10',
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
            $department=trim($row['department']);
            $requestor=trim($row['requestor']);
            $requestor_id=User::where('name',$requestor)->value('id');
            $department_id=Departments::where('department_code',$department)->value('id');
            $department_name=Departments::where('department_code',$department)->value('department_name');
            $department_check=Departments::where('department_code',$department)->exists();
            $requestor_check=User::where('name',$requestor)->exists();
            // $sitepr_check=PRHead::where('site_pr',trim($row['site_pr']))->where(function ($query) {$query->where('status', 'Saved')->orWhere('status', 'Draft');})->exists();
            if($department!=''){
                $this->dept_checker = $department_check;
            }

            if($requestor!=''){
                $this->req_checker = $requestor_check;
            }

            // if(trim($row['site_pr'])!=''){
            //     $this->site_checker = $sitepr_check;
            // }
            
            if(Departments::where('department_code',$department)->exists() && User::where('name',$requestor)->exists()){
                // if(!PRHead::where('site_pr',trim($row['site_pr']))->where(function ($query) {$query->where('status', 'Saved')->orWhere('status', 'Draft');})->exists()){
                if($row['purchase_request']!=''){
                    $year= date("Y", strtotime($this->transformDate($row['date_prepared'])));
                    $year_short = date("y",strtotime($this->transformDate($row['date_prepared'])));
                    $series_rows = PRSeries::where('year',$year)->count();
                    $company=Config::get('constants.company');
                    if($series_rows==0){
                        $pr_series='0001';
                        $pr_no = $department.$year_short."-".$pr_series."-".$company;
                    } else {
                        $max_series=PRSeries::where('year',$year)->max('series');
                        $pr_series=$max_series+1;
                        $pr_no = $department.$year_short."-".Str::padLeft($pr_series, 4,'000')."-".$company;
                    }
                    $series['year']=$year;
                    $series['series']=$pr_series;
                    $pr_series=PRSeries::create($series);
                    if($pr_series){
                        $prhead['location']=$row['purchase_request'];
                        $prhead['date_prepared']=date('Y-m-d',strtotime($this->transformDate($row['date_prepared'])));
                        $prhead['date_issued']= ($row['date_issued']!='') ? date('Y-m-d',strtotime($this->transformDate($row['date_issued']))) : '';
                        $prhead['pr_no']=$pr_no;
                        // $prhead['site_pr']=$row['site_pr'];
                        $prhead['department_id']=$department_id;
                        $prhead['department_name']=$department_name;
                        $prhead['dept_code']=$department;
                        $prhead['requestor_id']=$requestor_id;
                        $prhead['requestor']=$requestor;
                        $prhead['urgency']=$row['urgency'] ?? 0;
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
                // }
            }
        }
    }
}
