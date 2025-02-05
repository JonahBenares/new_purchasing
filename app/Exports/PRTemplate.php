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
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class PRTemplate implements ShouldAutoSize, WithHeadings, WithEvents, WithMultipleSheets, WithTitle, WithCustomStartCell, WithDrawings, WithStrictNullComparison
{
    use Exportable;

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('/images/logo_cenpri.png'));
        $drawing->setHeight(35);
        $drawing->setOffsetY(-6);
        $drawing->setOffsetX(7);
        $drawing->setCoordinates('A3');

        return $drawing;
    }
    
    public function startCell(): string
    {
        return 'A13';
    }

    public function headings(): array
    {
        return [
            'A13'=>'Item No.',
            'B13'=>'Qty',
            'C13'=>'UOM',
            'D13'=>'Part No.',
            'E13'=>'Description',
            'F13'=>'WH Stocks',
            'G13'=>'Date Needed',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) { 
                $event->sheet->getStyle('O3')->applyFromArray([
                    'font'=> [
                        'color' => ['argb' => 'FFFF0000']
                    ]
                ]);
                $event->sheet->getStyle('A13:H13')->applyFromArray([
                    'font'=> [
                        'bold'=>true
                    ]
                ]);

                $event->sheet->getStyle('A2:H2')->applyFromArray([
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                ]);
                $event->sheet->getStyle('A5:H5')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                ]);
                $event->sheet->getStyle('A6:H6')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                ]);
                $event->sheet->getStyle('H2:H12')->applyFromArray([
                    'borders' => [
                        'right' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                ]);
                $event->sheet->getStyle('C7:C12')->applyFromArray([
                    'borders' => [
                        'left' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                ]);

                $event->sheet->getStyle('C7:D7')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                ]);
                $event->sheet->getStyle('C8:D8')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                ]);
                $event->sheet->getStyle('C9:D9')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                ]);
                $event->sheet->getStyle('C10:H10')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                ]);
                $event->sheet->getStyle('C11:H11')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                ]);
                
                $event->sheet->getStyle('C12:H12')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                ]);

                $event->sheet->getStyle('D7:D10')->applyFromArray([
                    'borders' => [
                        'right' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                ]);
                
                $event->sheet->getStyle('G7:G10')->applyFromArray([
                    'borders' => [
                        'left' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                ]);
                $event->sheet->getStyle('G7:H7')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                ]);
                $event->sheet->getStyle('G8:H8')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                ]);
                $event->sheet->getStyle('G9:H9')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                ]);
                $event->sheet->getStyle('G10:H10')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                ]);
                // $totalRows = $event->sheet->getHighestRow();
                for($i=13;$i<=18;$i++){
                    $event->sheet->getStyle('A'.$i.':H'.$i)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            ]
                        ]
                    ]);
                    $event->sheet->getStyle('A'.$i.':D'.$i)->applyFromArray([
                       'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ]
                    ]);
                    $event->sheet->getStyle('F'.$i.':G'.$i)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ]
                    ]);
                    $event->sheet->mergeCells('G'.$i.':H'.$i);
                }
                $event->sheet->getDelegate()->getStyle('A13:H13')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => 'BDD7EE']
                    ]
                ]);
                $event->sheet->getStyle('A13:K13')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('C7:C10')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('G7:G10')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('B1')->getFont()->setBold(true);
                $event->sheet->getStyle('I2')->getFont()->setBold(true);
                $event->sheet->getStyle('A6')->getFont()->setBold(true);
                $event->sheet->getStyle('A7:A12')->getFont()->setBold(true);
                $event->sheet->getStyle('F7:H12')->getFont()->setBold(true);
                $event->sheet->getStyle('B5')->getFont()->setBold(true);
                $event->sheet->getStyle('C7')->getFont()->setBold(true);
                $event->sheet->getStyle('D5')->getFont()->setBold(true);
                $event->sheet->getStyle('E7')->getFont()->setBold(true);
                $event->sheet->setCellValue('A6', 'PURCHASE REQUEST');
                $event->sheet->setCellValue('A7', 'Location:');
                $event->sheet->getStyle('A7:B7')->getAlignment()->setHorizontal('right');
                $event->sheet->mergeCells('A7:B7');
                $event->sheet->mergeCells('C7:D7');
                $event->sheet->setCellValue('A8', 'Date Prepared:');
                $event->sheet->getStyle('A8:B8')->getAlignment()->setHorizontal('right');
                $event->sheet->mergeCells('A8:B8');
                $event->sheet->mergeCells('C8:D8');
                $event->sheet->setCellValue('A9', 'Date Issued:');
                $event->sheet->getStyle('A9:B9')->getAlignment()->setHorizontal('right');
                $event->sheet->mergeCells('A9:B9');
                $event->sheet->mergeCells('C9:D9');
                $event->sheet->setCellValue('A10', '');
                $event->sheet->getStyle('A10:B10')->getAlignment()->setHorizontal('right');
                $event->sheet->mergeCells('A10:B10');
                $event->sheet->mergeCells('C10:D10');
                $event->sheet->setCellValue('A11', 'Purpose:');
                $event->sheet->getStyle('A11:B11')->getAlignment()->setHorizontal('right');
                $event->sheet->mergeCells('A11:B11');
                $event->sheet->mergeCells('C11:H11');
                $event->sheet->setCellValue('A12', 'End-use:');
                $event->sheet->getStyle('A12:B12')->getAlignment()->setHorizontal('right');
                $event->sheet->mergeCells('A12:B12');
                $event->sheet->mergeCells('C12:H12');

                $event->sheet->setCellValue('F7', 'Department:');
                $event->sheet->getStyle('F7')->getAlignment()->setHorizontal('right');
                $event->sheet->mergeCells('G7:H7');
                $event->sheet->setCellValue('F8', 'Requestor:');
                $event->sheet->getStyle('F8')->getAlignment()->setHorizontal('right');
                $event->sheet->mergeCells('G8:H8');
                $event->sheet->setCellValue('F9', 'Urgency:');
                $event->sheet->getStyle('F9')->getAlignment()->setHorizontal('right');
                $event->sheet->mergeCells('G9:H9');
                $event->sheet->setCellValue('F10', '');
                $event->sheet->getStyle('F10')->getAlignment()->setHorizontal('right');
                $event->sheet->mergeCells('G10:H10');
                // $event->sheet->setCellValue('F8', 'Site PR:');
                // $event->sheet->getStyle('F8')->getAlignment()->setHorizontal('right');
                // $event->sheet->mergeCells('G8:H8');
                // $event->sheet->setCellValue('F9', 'Requestor:');
                // $event->sheet->getStyle('F9')->getAlignment()->setHorizontal('right');
                // $event->sheet->mergeCells('G9:H9');
                // $event->sheet->setCellValue('F10', 'Urgency:');
                // $event->sheet->getStyle('F10')->getAlignment()->setHorizontal('right');
                // $event->sheet->mergeCells('G10:H10');

                $event->sheet->setCellValue('C2', 'CENTRAL NEGROS POWER RELIABILITY, INC.');
                $event->sheet->setCellValue('C3', '(Main Office) 88 Corner Rizal-Mabini Sts. Bacolod City');
                $event->sheet->setCellValue('C4', '(Plant Site) Purok San Jose, Barangay Calumangan, Bago City');
                $event->sheet->setCellValue('C5', 'Telfax: (034) 436-1932');
                $event->sheet->getStyle('C2:I5')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle("C2")->getFont()->setBold(true)->setName('Arial Black');
                $event->sheet->getStyle("K2")->getFont()->setBold(true);
                $event->sheet->setCellValue('K2', 'Notes:');

                $event->sheet->setCellValue('K3', 'Date format for all dates should be:');
                $event->sheet->setCellValue('O3', 'YYYY-MM-DD');
                $event->sheet->mergeCells('K3:N3');

                $event->sheet->setCellValue('K4', "For Department Code, please refer to the Department Code sheet");
                $event->sheet->mergeCells('K4:Q4');
                $event->sheet->setCellValue('K5', "For the Requestor, please refer to the Requestors Name sheet");
                $event->sheet->mergeCells('K5:Q5');
                $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(5);
                $event->sheet->mergeCells('C2:G2');
                $event->sheet->mergeCells('C3:G3');
                $event->sheet->mergeCells('C4:G4');
                $event->sheet->mergeCells('C5:G5');
                $event->sheet->getStyle('A6:H6')->getAlignment()->setHorizontal('center');
                $event->sheet->mergeCells('A6:H6');
                $event->sheet->mergeCells('G13:H13');
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(20);
            }
        ];
    }

    public function sheets(): array
    {
        return [
            new PRTemplate(),
            new PRDeptCode(),
            new PRRequestors(),
        ];
    }

    public function title(): string
    {
        return 'Purchase Request';
    }
}
