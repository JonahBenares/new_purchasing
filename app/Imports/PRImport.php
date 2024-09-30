<?php

namespace App\Imports;

use App\Models\PRHead;
use App\Models\PRDetails;
use App\Models\PRSeries;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMappedCells;

class PRImport implements WithMappedCells, ToModel, WithHeadingRow
{

    public function mapping(): array
    {
        return [
            'purchase_request'  => 'C7',
            'department' => 'I7',
            'dept_code' => 'I8',
            'date_prepared' => 'C8',
            'date_issued' => 'C9',
            'requestor' => 'I9',
            'urgency' => 'I10',
            'purpose' => 'C11',
            'enduse' => 'C12',
            'item_no' => 'A14',
        ];
    }

    public function headingRow(): int
    {
        return 6;
    }

    public function model(array $row)
    {
        if(count($row)!=0){
            print_r($row);
        }
    }
}
