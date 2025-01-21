<?php

namespace App\Exports;

use App\Models\Departments;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class PRDeptCode implements FromQuery, WithMapping, ShouldAutoSize, WithHeadings, WithEvents, WithTitle
{
    use Exportable;

    public function query()
    {
        return Departments::query()->orderBy('department_name', 'ASC');
    }

    public function map($department): array
    {
        return [
            $department->department_name,
            $department->department_code,
        ];
    }

    public function headings(): array
    {
        return [
            'A1'=>'Department',
            'B1'=>'Code',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) { 
                $event->sheet->getStyle('A1:B1')->applyFromArray([
                    'font'=> [
                        'bold'=>true
                    ]
                ]);
                $event->sheet->getStyle('A1:B1')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('B')->getAlignment()->setHorizontal('center');
            }
        ];
    }

    public function title(): string
    {
        return 'Department Code';
    }
}
