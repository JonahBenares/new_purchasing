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
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class JORTemplate implements ShouldAutoSize, WithHeadings, WithEvents, WithMultipleSheets, WithTitle, WithCustomStartCell, WithDrawings, WithStrictNullComparison
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
        return 'A15';
    }

    public function headings(): array
    {
        return [
            // 'A15'=>'Item No.',
            // 'B15'=>'Scope of Work',
            // 'I15'=>'Qty',
            // 'J15'=>'UOM',
            // 'K15'=>'Unit Cost',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) { 
                $event->sheet->getStyle('R3')->applyFromArray([
                    'font'=> [
                        'color' => ['argb' => 'FFFF0000']
                    ]
                ]);
                $event->sheet->getStyle('A13:H13')->applyFromArray([
                    'font'=> [
                        'bold'=>true
                    ]
                ]);

                $event->sheet->getStyle('A2:K2')->applyFromArray([
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                ]);
                $event->sheet->getStyle('A5:K5')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                ]);
                $event->sheet->getStyle('A6:K6')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                ]);
                $event->sheet->getStyle('K2:K14')->applyFromArray([
                    'borders' => [
                        'right' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                ]);
                $event->sheet->getStyle('C7:E7')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                ]);
                $event->sheet->getStyle('C8:E8')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                ]);
                $event->sheet->getStyle('C9:E9')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                ]);
                $event->sheet->getStyle('C10:E10')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                ]);
                $event->sheet->getStyle('E7:E10')->applyFromArray([
                    'borders' => [
                        'right' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                ]);
                $event->sheet->getStyle('I7:K7')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                ]);
                $event->sheet->getStyle('I8:K8')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                ]);
                $event->sheet->getStyle('I9:K9')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                ]);
                $event->sheet->getStyle('I10:K10')->applyFromArray([
                    'borders' => [
                        'bottom' => [
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
                $event->sheet->getStyle('A13:B14')->applyFromArray([
                    'borders' => [
                        'left' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                ]);
                $event->sheet->getStyle('C10:K10')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                ]);
                $event->sheet->getStyle('C11:K11')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                ]);
                
                $event->sheet->getStyle('A12:K12')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                ]);

                $event->sheet->getStyle('H7:H10')->applyFromArray([
                    'borders' => [
                        'right' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                ]);

                $event->sheet->getStyle('A13:K13')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                ]);
                
                $event->sheet->getStyle('A13:A14')->applyFromArray([
                    'borders' => [
                        'right' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ]
                    ],
                ]);

                // $totalRows = $event->sheet->getHighestRow();
                for($l=15;$l<=20;$l++){
                    $event->sheet->getStyle('A'.$l.':K'.$l)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            ]
                        ]
                    ]);
                    $event->sheet->getStyle('A'.$l.':K'.$l)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ]
                    ]);
                    $event->sheet->mergeCells('B'.$l.':H'.$l);
                }
                for($m=22;$m<=27;$m++){
                    $event->sheet->getStyle('A'.$m.':K'.$m)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            ]
                        ]
                    ]);
                    $event->sheet->getStyle('A'.$m.':K'.$m)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ]
                    ]);
                    $event->sheet->mergeCells('E'.$m.':I'.$m);
                    $event->sheet->getStyle('K'.$m)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD2);
                    
                }
                $event->sheet->getDelegate()->getStyle('A15:K15')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => 'BDD7EE']
                    ]
                ]);
                $event->sheet->getDelegate()->getStyle('A22:K22')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => 'BDD7EE']
                    ]
                ]);
                $event->sheet->getStyle('C7:C10')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('G7:G10')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('I2')->getFont()->setBold(true);
                $event->sheet->getStyle('A6')->getFont()->setBold(true);
                $event->sheet->getStyle('A7:A14')->getFont()->setBold(true);
                $event->sheet->getStyle('F7:H10')->getFont()->setBold(true);
                $event->sheet->getStyle('B5')->getFont()->setBold(true);
                $event->sheet->getStyle('D5')->getFont()->setBold(true);
                $event->sheet->getStyle('E7')->getFont()->setBold(true);
                $event->sheet->getStyle('A21')->getFont()->setBold(true);
                $event->sheet->setCellValue('A6', 'JOB ORDER REQUEST');
                $event->sheet->setCellValue('A7', 'JO Request to:');
                $event->sheet->getStyle('A7:B7')->getAlignment()->setHorizontal('right');
                $event->sheet->mergeCells('A7:B7');
                $event->sheet->mergeCells('C7:E7');
                $event->sheet->setCellValue('A8', 'Date Prepared:');
                $event->sheet->getStyle('A8:B8')->getAlignment()->setHorizontal('right');
                $event->sheet->mergeCells('A8:B8');
                $event->sheet->mergeCells('C8:E8');
                $event->sheet->getStyle('C8:E8')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD2);
                $event->sheet->setCellValue('A9', 'Department:');
                $event->sheet->getStyle('A9:B9')->getAlignment()->setHorizontal('right');
                $event->sheet->mergeCells('A9:B9');
                $event->sheet->mergeCells('C9:E9');
                $event->sheet->setCellValue('A10', 'JO No.:');
                $event->sheet->getStyle('A10:B10')->getAlignment()->setHorizontal('right');
                $event->sheet->mergeCells('A10:B10');
                $event->sheet->mergeCells('C10:E10');
                $event->sheet->setCellValue('A11', 'Requested By:');
                $event->sheet->getStyle('A11:B11')->getAlignment()->setHorizontal('right');
                $event->sheet->mergeCells('A11:B11');
                $event->sheet->mergeCells('C11:K11');
                $event->sheet->setCellValue('A12', 'Purpose:');
                $event->sheet->getStyle('A12:B12')->getAlignment()->setHorizontal('right');
                $event->sheet->mergeCells('A12:B12');
                $event->sheet->mergeCells('C12:K12');
                $event->sheet->setCellValue('A13', 'Project/Activity:');
                $event->sheet->getStyle('A13')->getAlignment()->setHorizontal('right');
                $event->sheet->mergeCells('B13:K13');
                $event->sheet->getStyle('B13:K13')->getAlignment()->setHorizontal('left');
                $event->sheet->setCellValue('A14', 'General Description:');
                $event->sheet->getStyle('A14')->getAlignment()->setHorizontal('right');
                $event->sheet->mergeCells('B14:K14');
                $event->sheet->getStyle('B14:K14')->getAlignment()->setHorizontal('left');

                $event->sheet->setCellValue('H7', 'Duration:');
                $event->sheet->getStyle('H7')->getAlignment()->setHorizontal('right');
                $event->sheet->mergeCells('I7:K7');
                $event->sheet->getStyle('I7:K7')->getAlignment()->setHorizontal('center');
                $event->sheet->setCellValue('H8', 'Completion Date:');
                $event->sheet->getStyle('H8')->getAlignment()->setHorizontal('right');
                $event->sheet->mergeCells('I8:K8');
                $event->sheet->getStyle('I8:K8')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('I8:K8')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD2);
                $event->sheet->setCellValue('H9', 'Delivery Date:');
                $event->sheet->getStyle('H9')->getAlignment()->setHorizontal('right');
                $event->sheet->mergeCells('I9:K9');
                $event->sheet->getStyle('I9:K9')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('I9:K9')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD2);
                $event->sheet->setCellValue('H10', 'Urgency No.:');
                $event->sheet->getStyle('H10')->getAlignment()->setHorizontal('right');
                $event->sheet->mergeCells('I10:K10');
                $event->sheet->getStyle('I10:K10')->getAlignment()->setHorizontal('center');

                $event->sheet->setCellValue('A21', 'Materials:');
                $event->sheet->getStyle('A21')->getFont()->setBold(true);
                $event->sheet->setCellValue('A15', 'Item No');
                $event->sheet->setCellValue('B15', 'Scope of Work');
                $event->sheet->mergeCells('B15:H15');
                $event->sheet->setCellValue('I15', 'Qty');
                $event->sheet->setCellValue('J15', 'UOM');
                $event->sheet->setCellValue('K15', 'Unit Cost');

                $event->sheet->getStyle('A15:K15')->getAlignment()->setHorizontal('Center');
                $event->sheet->getStyle('A15:K15')->getFont()->setBold(true);

                $event->sheet->setCellValue('A22', 'Item No');
                $event->sheet->setCellValue('B22', 'Qty');
                $event->sheet->setCellValue('C22', 'UOM');
                $event->sheet->setCellValue('D22', 'Part No');
                $event->sheet->setCellValue('E22', 'Item Description');
                $event->sheet->mergeCells('E22:I22');
                $event->sheet->setCellValue('J22', 'WH Stocks');
                $event->sheet->setCellValue('K22', 'Date Needed');

                $event->sheet->getStyle('A22:K22')->getAlignment()->setHorizontal('Center');
                $event->sheet->getStyle('A22:K22')->getFont()->setBold(true);

                $event->sheet->setCellValue('C2', 'CENTRAL NEGROS POWER RELIABILITY, INC.');
                $event->sheet->setCellValue('C3', '(Main Office) 88 Corner Rizal-Mabini Sts. Bacolod City');
                $event->sheet->setCellValue('C4', '(Plant Site) Purok San Jose, Barangay Calumangan, Bago City');
                $event->sheet->setCellValue('C5', 'Telfax: (034) 436-1932');
                $event->sheet->getStyle('C2:H5')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle("C2")->getFont()->setBold(true)->setName('Arial Black');
                $event->sheet->getStyle("K2")->getFont()->setBold(true);
                $event->sheet->setCellValue('N2', 'Notes:');
                $event->sheet->getStyle("N2")->getFont()->setBold(true);
                $event->sheet->setCellValue('N3', 'Date Format for all dates should be:');
                // $event->sheet->getStyle("N3")->getFont()->setBold(true);
                $event->sheet->setCellValue('R3', 'YYYY-MM-DD');
                // $event->sheet->getStyle("R3")->getFont()->setBold(true);
                $event->sheet->setCellValue('N4', 'For Department Code refer to the Department Code sheet');
                // $event->sheet->getStyle("N4")->getFont()->setBold(true);
                $event->sheet->setCellValue('N5', 'For the Requestor refer to the Requestors Name sheet');
                // $event->sheet->getStyle("N5")->getFont()->setBold(true);
                $event->sheet->mergeCells('C2:H2');
                $event->sheet->mergeCells('C3:H3');
                $event->sheet->mergeCells('C4:H4');
                $event->sheet->mergeCells('C5:H5');
                $event->sheet->getStyle('A6:K6')->getAlignment()->setHorizontal('center');
                $event->sheet->mergeCells('A6:K6');
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(13);
            }
        ];
    }

    public function sheets(): array
    {
        return [
            new JORTemplate(),
            new PRDeptCode(),
        ];
    }

    public function title(): string
    {
        return 'JORequest Form';
    }
}
